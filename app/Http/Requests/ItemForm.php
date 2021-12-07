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
            "items" => "required|array|min:1",
            "items.*.supplier" => "required",
            "items.*.manufacturer" => "required",
            "items.*.model" => "required",
            "items.*.issues" => "required|array|min:0",
            "items.*.colour" => "sometimes",
            "items.*.battery" => "sometimes|between:0,100",
            "items.*.grade" => "sometimes",
            "items.*.cost" => "sometimes|numeric",
            "items.*.imei" => "sometimes",
            "items.*.date" => "sometimes|date",
            "items.*.selling_price" => "sometimes|numeric",
        ];
    }
}
