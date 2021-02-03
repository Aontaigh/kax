<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'affiliate_id',
        'name',
        'latitude',
        'longitude'
    ];

    /*
    |--------------------------------------------------------------------------
    | MODEL SCOPES
    |--------------------------------------------------------------------------
    |
    | Local scopes allow you to define common sets of query constraints that
    | you may easily re-use throughout your application. For example, you may
    | need to frequently retrieve all users that are 'enabled. To define a
    | scope, prefix an eloquent model method with 'scope'.
    |
    */

    /**
     * Scope a query to search through Affiliates.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  String  $search
     * @param  mixed  $searchFields
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(
        $query,
        $search,
        $searchFields = ['affiliate_id', 'name', 'latitude', 'longitude']
    ) {
        if (empty($searchFields) === true) {
            $searchFields = ['affiliate_id', 'latitude', 'longitude'];
        }
        
        return $query->where(function ($query) use ($search, $searchFields) {
            foreach ($searchFields as $searchField) {
                $query->orWhere(
                    $searchField,
                    'like',
                    '%'.$search.'%'
                );
            }
        });
    }
}
