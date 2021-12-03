<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemForm extends FormRequest
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
            "date" => "required|date",
            "supplier" => "required",
            "manufacturer" => "required",
            "model" => "required",
            "colour" => "required",
            "battery" => "required|numeric|between:0,100",
            "grade" => "required",
            "issues" => "sometimes",
            "cost" => "required|numeric",
            "imei" => "required",
            "selling_price" => "sometimes|sumeric",
        ];
    }
}
