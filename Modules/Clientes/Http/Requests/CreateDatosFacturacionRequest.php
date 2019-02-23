<?php

namespace Modules\Clientes\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreateDatosFacturacionRequest extends BaseFormRequest
{
    public function rules()
    {
        return [];
    }

    public function translationRules()
    {
        return [];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }

    public function translationMessages()
    {
        return [];
    }
}
