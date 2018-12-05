<?php

declare(strict_types=1);

namespace App\Http\Requests\Call;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\SessionRequestTrait;

class StoreCallRequest extends BaseFormRequest
{
    use SessionRequestTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge([
            'encryptionPayload' => 'required',
        ], $this->getDefaultRules());
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return array_merge([
            'encryptionPayload.*' => 'Incorrect input parameters',
        ], $this->getDefaultMessages());
    }
}
