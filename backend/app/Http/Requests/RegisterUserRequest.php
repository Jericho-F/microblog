<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'first_name' => 'required|string|max:150|regex:/^[a-zA-Z.\s]+$/',
            'last_name' => 'required|string|max:150|regex:/^[a-zA-Z.\s]+$/',
            'username' => 'required|string|min:8|max:150|regex:/^[^.<>]*$/',
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users', 'regex:/^[^<>]*$/'],
            'birthdate' => [
                'required',
                'date',
                'before:' . Carbon::now()->subYears(18)->format('Y-m-d'),
                'after:' . Carbon::now()->subYears(100)->format('Y-m-d'),
            ],
            'mobile_no' => 'required|string|max:20|regex:/^[^.<>]*$/',
            'lot_block' => 'required|string|max:200|regex:/^[^.<>]*$/',
            'street' => 'required|string|max:100|regex:/^[^.<>]*$/',
            'city' => 'required|string|max:100|regex:/^[^.<>]*$/',
            'province' => 'required|string|max:200|regex:/^[^.<>]*$/',
            'country' => 'required|string|max:100|regex:/^[^.<>]*$/',
            'zip_code' => 'required|string|max:20|regex:/^[^.<>]*$/',
            'password' => [
                'required', 'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
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
            'first_name.required' => 'This :attribute is required.',
            'first_name.max' => 'This field should not exceed 150 characters.',
            'first_name.regex' => 'This field contains invalid characters.',

            'last_name.required' => 'This :attribute is required.',
            'last_name.max' => 'This field should not exceed 150 characters.',
            'last_name.regex' => 'This field contains invalid characters.',

            'username.required' => 'This :attribute is required.',
            'username.max' => 'This field should not exceed 150 characters.',
            'username.regex' => 'This field contains invalid characters.',

            'email.required' => 'This field is required.',
            'email.email' => 'This must be an email type.',
            'email.max' => 'This field should not exceed 150 characters.',
            'email.unique' => 'This email is already taken.',
            'email.regex' => 'This field contains invalid characters.',

            'birthdate.required' => 'This :attribute is required.',
            'birthdate.before_or_equal' => 'Your age must be at least 18 years old.',
            'birthdate.date' => 'This must be a valid date.',

            'mobile_no.required' => 'This :attribute is required.',
            'mobile_no.max' => 'This field should not exceed 20 characters.',
            'mobile_no.regex' => 'This field contains invalid characters.',

            'lot_block.required' => 'This :attribute is required.',
            'lot_block.max' => 'This field should not exceed 150 characters.',
            'lot_block.regex' => 'This field contains invalid characters.',

            'street.required' => 'This :attribute is required.',
            'street.max' => 'This field should not exceed 150 characters.',
            'street.regex' => 'This field contains invalid characters.',

            'city.required' => 'This :attribute is required.',
            'city.max' => 'This field should not exceed 150 characters.',
            'city.regex' => 'This field contains invalid characters.',

            'province.required' => 'This :attribute is required.',
            'province.max' => 'This field should not exceed 150 characters.',
            'province.regex' => 'This field contains invalid characters.',

            'country.required' => 'This :attribute is required.',
            'country.max' => 'This field should not exceed 150 characters.',
            'country.regex' => 'This field contains invalid characters.',

            'zip_code.required' => 'This :attribute is required.',
            'zip_code.max' => 'This field should not exceed 150 characters.',
            'zip_code.regex' => 'This field contains invalid characters.',

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

    public function validated($key = null, $default = null)
    {
        return $this->validator->validate($this->all());
    }
}
