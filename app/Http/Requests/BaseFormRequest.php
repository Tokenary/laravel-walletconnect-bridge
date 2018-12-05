<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseFormRequest extends FormRequest
{
    /**
     * Add parameters to be validated.
     *
     * @return array
     */
    public function validationData()
    {
        return array_replace_recursive(
            parent::all(),
            $this->route()->parameters()
        );
    }
}
