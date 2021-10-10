<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGameRequest extends FormRequest
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
            'width'=>'required|integer|min:5|max:99|regex:/^\d*[13579]$/',
            'height'=>'required|integer|min:5|max:99|regex:/^\d*[13579]$/',
        ];
    }
}
