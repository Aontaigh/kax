<?php

namespace App\Http\Requests\Affiliates;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\Varchar;

class UpdateRequest extends FormRequest
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
        return [
            'affiliate_id' => ['sometimes', 'required', 'integer'],
            'latitude'     => ['sometimes', 'required', new Varchar, 'max:255'],
            'longitude'    => ['sometimes', 'required', new Varchar, 'max:255'],
        ];
    }
}
