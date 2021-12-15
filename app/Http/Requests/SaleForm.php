<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleForm extends FormRequest
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
            "*" => "array",
            "*.id" => "required|exists:items,id",
            "*.sold" => "required|date",
            "*.customer" => "required",
            "*.subtotal" => "required|numeric",
            "*.profit" => "required|numeric",
        ];
    }
}
