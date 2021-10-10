<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MakeStepRequest extends FormRequest
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
            'gameId' => 'required|string',
            'playerId' => ['required', 'integer', Rule::in(1, 2)],
            'color' => ['required', 'string', Rule::in("blue", "green", "cyan", "red", "magenta", "yellow", "white")]
        ];
    }
}
