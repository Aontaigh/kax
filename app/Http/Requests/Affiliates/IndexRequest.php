<?php

namespace App\Http\Requests\Affiliates;

use Illuminate\Foundation\Http\FormRequest;

use App\Affiliate;

use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

use App\Rules\Varchar;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $columnsArray = Schema::getColumnListing((new Affiliate)->getTable());

        return [
            'per_page'        => 'integer',
            'order_by'        => Rule::in($columnsArray),
            'sort_by'         => Rule::in(['asc', 'desc']),
            'search'          => [new Varchar, 'max:255'],
            'search_fields'   => 'array',
            'search_fields.*' => Rule::in($columnsArray)
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $attributesArray  = Schema::getColumnListing((new Affiliate)->getTable());
        $attributesString = $str = implode(', ', $attributesArray);

        return [
            'order_by.in'        => 'order_by must be: ' . $attributesString,
            'search_fields.*.in' => 'search_fields must be: ' . $attributesString
        ];
    }
}
