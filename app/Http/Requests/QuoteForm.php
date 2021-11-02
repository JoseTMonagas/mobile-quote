<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuoteForm extends FormRequest
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
            "name" => "sometimes",
            "internal_number" => "sometimes",
            "store_margin" => "sometimes|numeric",
            "items" => "required|array|min:1",
            "items.*.device" => "required",
            "items.*.quantity" => "required|numeric|gte:1",
            "items.*.condition" => "required|alpha",
            "items.*.issues" => "sometimes|array",
            "items.*.value" => "required|numeric",
            "items.*.serialNumber" => "sometimes"
        ];
    }
}
