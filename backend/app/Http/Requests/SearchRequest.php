<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'search' => ['required', 'string', 'regex:/^[^<>]*$/', 'max:' . config('constants.SEARCH_MAX_LENGTH')],
        ];
    }

    public function messages()
    {
        return [
            'search.required' => 'The search field is required.',
            'search.string' => 'The search field must be a string.',
            'search.regex' => 'The search field contains invalid characters.',
            'search.max' => 'The search field may not be greater than :max characters.',
        ];
    }
}
