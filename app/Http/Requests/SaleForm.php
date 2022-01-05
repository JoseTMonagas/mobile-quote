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
            "subtotal" => "required|numeric",
            "discount" => "required|numeric",
            "flatTax" => "required|numeric",
            "tax" => "required|numeric",
            "total" => "required|numeric",
            "items" => "required|array|min:1",
            "items.*.id" => "required|exists:items,id",
            "items.*.sold" => "required|date",
            "items.*.customer" => "required",
            "items.*.profit" => "required|numeric",
        ];
    }
}
