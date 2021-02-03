<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Affiliates\IndexRequest;
use App\Http\Requests\Affiliates\StoreFileRequest;
use App\Http\Requests\Affiliates\ShowRequest;
use App\Http\Requests\Affiliates\UpdateRequest;
use App\Http\Requests\Affiliates\DestroyRequest;
use App\Http\Requests\Affiliates\ProximityRequest;

use App\Affiliate;

use App\Http\Resources\Affiliate as AffiliateResource;

use App\Helpers\GreatCircleDistanceHelper;

class AffiliateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Http\Requests\Affiliates\IndexRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        /**
         * Request paramters are taken here or else assigned default values.
         */
        $perPage      = (int) $request->input('per_page', 30);
        $orderBy      = $request->input('order_by', 'id');
        $sortBy       = $request->input('sort_by', 'asc');
        $search       = $request->input('search', null);
        $searchFields = $request->input(
            'search_fields',
            ['affiliate_id', 'name', 'latitude', 'longitude']
        );

        /**
         * The initial query is built here and further query builders will be
         * added throughout based on the request parameters above.
         */
        $affiliates = Affiliate::orderBy($orderBy, $sortBy);

        if ($request->filled('search')) {
            $affiliates->search($search, $searchFields);
        }

        /**
         * The collection is made into a resource, the last filtering is applied,
         * and the result is returned.
         */
        return AffiliateResource::collection(
            $affiliates->paginate($perPage)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Http\Requests\Affiliates\StoreFileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFile(StoreFileRequest $request)
    {
        $file = $request->file('file');

        $fp = @fopen($file, 'r');

        /**
         * Return an error message if the file cannot be read for whatever
         * potential reason - error messaging here could be greatly expanded
         * upon and nailed down to provide the user with much more detailed
         * information in order to assist them with the issues with their file.
         */
        if (!$fp) {
            return response()->json([
                'message' => 'Cannot Read File'
            ], 400);
        }

        /**
         * Here we build an array by exploding the text file by each line (i.e.
         * each individual line will now become an element in the array).
         */
        $affiliateArray = explode("\n", fread($fp, filesize($file)));

        /**
         * We will push the validated affiliates into this array so we are not
         * required to json_decode the affiliates again after the below loop and
         * waste resources.
         */
        $validatedArray = [];

        foreach ($affiliateArray as &$affiliate) {
            $lineCounter = 1;
            $affiliate   = json_decode($affiliate);

            /**
             * Here we will conduct the necessary validation to make sure
             * the file contains all of the required fields in order to create
             * an affiliate.
             */
            if (isset($affiliate->affiliate_id) === false || empty($affiliate->affiliate_id) === true) {
                return response()->json([
                    'message' => 'affiliate_id required on line: ' . $lineCounter
                ], 422);
            }

            if (isset($affiliate->name) === false || empty($affiliate->name) === true) {
                return response()->json([
                    'message' => 'name required on line: ' . $lineCounter
                ], 422);
            }

            if (isset($affiliate->longitude) === false || empty($affiliate->longitude) === true) {
                return response()->json([
                    'message' => 'longitude required at line: ' . $lineCounter
                ], 422);
            }

            if (isset($affiliate->latitude) === false || empty($affiliate->latitude) === true) {
                return response()->json([
                    'message' => 'latitude required at line: ' . $lineCounter
                ], 422);
            }

            array_push($validatedArray, $affiliate);

            $lineCounter++;
        }

        /**
         * At this point, we know all the affiliates in our array are valid (i.e.
         * they all have the required values set) and are ready to be inserted into
         * the database.
         */
        foreach ($validatedArray as $affiliate) {
            Affiliate::create([
                'affiliate_id' => $affiliate->affiliate_id,
                'name'         => $affiliate->name,
                'latitude'     => $affiliate->latitude,
                'longitude'    => $affiliate->longitude
            ]);
        }

        $message = 'Affiliate Successfully Created';
        if (count($validatedArray) > 0) {
            $message = 'Affiliates Successfully Created';
        }

        return response()->json([
            'message' => $message
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Http\Requests\Affiliates\ShowRequest  $request
     * @param  \App\Affiliate  $affiliate
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, Affiliate $affiliate)
    {
        return new AffiliateResource($affiliate);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Http\Requests\Affiliates\UpdateRequest  $request
     * @param  \App\Affiliate  $affiliate
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Affiliate $affiliate)
    {
        $affiliate->update($request->all());

        return (new AffiliateResource($affiliate))->additional([
            'message' => 'Affiliate Successfully Updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Http\Requests\Affiliates\DestroyRequest  $request
     * @param  \App\Affiliate  $affiliate
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, Affiliate $affiliate)
    {
        $affiliate->delete();

        return response()->json([
            'message' => 'Affiliate Successfully Deleted'
        ], 200);
    }

    /**
     * Display a listing of the resource in terms of their proximity to
     * the office in Dublin.
     *
     * @param  \Http\Requests\Affiliates\ProximityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function proximity(ProximityRequest $request)
    {
        /**
         * Request paramters are taken here or else assigned default values.
         */
        $maxDistance = $request->input('max_distance', 100);

        $affiliates = Affiliate::orderBy('id', 'asc')->get();

        $data = [];

        foreach ($affiliates as $affiliate) {
            $metreDistance = GreatCircleDistanceHelper::haversineGreatCircleDistance(
                $affiliate->longitude,
                $affiliate->latitude,
                -6.2535495,
                53.3340285
            );

            $kilometreDistance = $metreDistance / 1000;
            if ($kilometreDistance < $maxDistance) {
                array_push($data, $affiliate);
            }
        }

        return response()->json([
            'data' => $data
        ], 200);
    }
}
