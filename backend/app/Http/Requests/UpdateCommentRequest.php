<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
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
            'comment' => [
                'required',
                function ($attribute, $value, $fail) use ($maxLength) {
                    $processedValue = preg_replace("/\R/u", "\n", $value);
                    if (strlen($processedValue) > $maxLength) {
                        $fail("The $attribute field should not exceed $maxLength characters.");
                    }
                },
            ],
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
            'comment.max' => 'The comment must not exceed 140 characters.',
        ];
    }
}
