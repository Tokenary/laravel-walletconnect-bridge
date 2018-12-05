<?php

declare(strict_types=1);

namespace App\Http\Requests\Call;

use App\Http\Requests\BaseFormRequest;
use App\Http\Requests\CallRequestTrait;

class StatusCallRequest extends BaseFormRequest
{
    use CallRequestTrait;

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
