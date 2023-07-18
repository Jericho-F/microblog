<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
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
        $maxLength = config('constants.CHARACTER_MAX_LENGTH');
        return [
            'content' => [
                'nullable',
                function ($attribute, $value, $fail) use ($maxLength){
                    $processedValue = preg_replace("/\R/u", "\n", $value);
                    if (strlen($processedValue) > $maxLength) {
                        $fail("The $attribute field should not exceed $maxLength characters.");
                    }
                },
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Message according to the rules.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'content.max' => 'Maximum of 140 characters only.',
            'image.mime' => 'Only jpeg, jpg, png, gif formats only.',
            'image.max' => 'The image must not be greater than 2048 kilobytes.',
        ];
    }
}
