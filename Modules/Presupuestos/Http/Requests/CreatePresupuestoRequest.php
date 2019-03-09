<?php

namespace Modules\Presupuestos\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreatePresupuestoRequest extends BaseFormRequest
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
