<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Varchar implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $type = gettype($value);

        if ($type === 'string' || $type === 'double' || $type === 'integer' || $type === 'NULL' || $type === 'boolean') {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute must be a valid varchar value';
    }
}
