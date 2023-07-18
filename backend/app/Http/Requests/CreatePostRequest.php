<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
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
                function ($attribute, $value, $fail) use ($maxLength) {
                    $processedValue = preg_replace("/\R/u", "\n", $value);
                    if (strlen($processedValue) > config('constants.CHARACTER_MAX_LENGTH')) {
                        $fail("The $attribute field should not exceed $maxLength characters.");
                    }
                },
            ],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,gif,png', 'max:2048'],
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
            'content.max' => 'The content field should not exceed 140 characters.',
            'image.max' => 'The image must not be greater than 2048 kilobytes.',
            'image.image' => 'The image must be a jpg, jpeg, gif, or png format.',
            'image.mimes' => 'The image must be a file of type: jpg, jpeg, gif, or png.',
        ];
    }
}

