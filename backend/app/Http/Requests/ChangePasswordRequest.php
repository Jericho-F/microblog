<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
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
            'current_password' => 'required',
            'password' => [
                'required', 'different:current_password',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'password_confirmation' => 'required|same:password'
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
            'current_password.required' => 'The current password field is required.',
            'password.required' => 'The new password field is required.',
            'password.different' => 'The new password must be different from the current password.',
            'password_confirmation.required' => 'The password confirmation field is required.',
            'password_confirmation.same' => 'The password confirmation does not match the new password.',
            'password.min' => 'The new password must be at least :min characters.',
            'password.letters' => 'The new password must contain at least one letter.',
            'password.mixed_case' => 'The new password must contain both uppercase and lowercase letters.',
            'password.numbers' => 'The new password must contain at least one number.',
            'password.symbols' => 'The new password must contain at least one symbol.',
        ];
    }
}
