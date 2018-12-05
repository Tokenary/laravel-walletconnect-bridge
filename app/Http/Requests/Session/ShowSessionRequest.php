<?php

declare(strict_types=1);

namespace App\Http\Requests\Session;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\SessionRequestTrait;

class ShowSessionRequest extends BaseFormRequest
{
    use SessionRequestTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->getDefaultRules();
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return $this->getDefaultMessages();
    }
}
