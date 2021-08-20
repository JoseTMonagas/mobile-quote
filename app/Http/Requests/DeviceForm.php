<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceForm extends FormRequest
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
            "model" => "required",
            "brand" => "required",
            "base_price" => "required",
            "excellent_factor" => "required",
            "good_factor" => "required",
            "acceptable_factor" => "required",
            "broken_factor" => "required",
        ];
    }
}
