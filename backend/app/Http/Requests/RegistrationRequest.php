<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:150|regex:/^[a-zA-Z.\s]+$/',
            'last_name' => 'required|string|max:150|regex:/^[a-zA-Z.\s]+$/',
            'username' => 'required|string|min:8|max:150|regex:/^[^.<>]*$/',
            'email' => [
                'required',
                'string',
                'email',
                'max:150',
                Rule::unique('users'), // Assuming the users table is used
                'regex:/^[^<>]*$/'
            ],
            'birthdate' => [
                'required',
                'date',
                'before:' . now()->subYears(18)->format('Y-m-d'),
                'after:' . now()->subYears(100)->format('Y-m-d'),
            ],
            'mobile_no' => 'required|string|max:20|regex:/^[^.<>]*$/',
            'lot_block' => 'required|string|max:200|regex:/^[^.<>]*$/',
            'street' => 'required|string|max:100|regex:/^[^.<>]*$/',
            'city' => 'required|string|max:100|regex:/^[^.<>]*$/',
            'province' => 'required|string|max:200|regex:/^[^.<>]*$/',
            'country' => 'required|string|max:100|regex:/^[^.<>]*$/',
            'zip_code' => 'required|string|max:20|regex:/^[^.<>]*$/',
            'password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'The first name is required.',
            'first_name.string' => 'The first name must be a string.',
            'first_name.max' => 'The first name may not be greater than :max characters.',
            'first_name.regex' => 'The first name format is invalid. Only letters, dots, and spaces are allowed.',

            'last_name.required' => 'The last name is required.',
            'last_name.string' => 'The last name must be a string.',
            'last_name.max' => 'The last name may not be greater than :max characters.',
            'last_name.regex' => 'The last name format is invalid. Only letters, dots, and spaces are allowed.',

            'username.required' => 'The username is required.',
            'username.string' => 'The username must be a string.',
            'username.min' => 'The username must be at least :min characters.',
            'username.max' => 'The username may not be greater than :max characters.',
            'username.regex' => 'The username format is invalid. Special characters like "<" and ">" are not allowed.',

            'email.required' => 'The email is required.',
            'email.string' => 'The email must be a string.',
            'email.email' => 'The email format is invalid.',
            'email.max' => 'The email may not be greater than :max characters.',
            'email.unique' => 'The email has already been taken.',
            'email.regex' => 'The email format is invalid. Special characters like "<" and ">" are not allowed.',

            'birthdate.required' => 'The birthdate is required.',
            'birthdate.date' => 'The birthdate must be a valid date.',
            'birthdate.before' => 'You must be at least 18 years old to register.',
            'birthdate.after' => 'Please enter a valid birthdate.',

            'mobile_no.required' => 'The mobile number is required.',
            'mobile_no.string' => 'The mobile number must be a string.',
            'mobile_no.max' => 'The mobile number may not be greater than :max characters.',
            'mobile_no.regex' => 'The mobile number format is invalid. Special characters like "<" and ">" are not allowed.',

            'lot_block.required' => 'The lot/block is required.',
            'lot_block.string' => 'The lot/block must be a string.',
            'lot_block.max' => 'The lot/block may not be greater than :max characters.',
            'lot_block.regex' => 'The lot/block format is invalid. Special characters like "<" and ">" are not allowed.',

            'street.required' => 'The street is required.',
            'street.string' => 'The street must be a string.',
            'street.max' => 'The street may not be greater than :max characters.',
            'street.regex' => 'The street format is invalid. Special characters like "<" and ">" are not allowed.',

            'city.required' => 'The city is required.',
            'city.string' => 'The city must be a string.',
            'city.max' => 'The city may not be greater than :max characters.',
            'city.regex' => 'The city format is invalid. Special characters like "<" and ">" are not allowed.',

            'province.required' => 'The province is required.',
            'province.string' => 'The province must be a string.',
            'province.max' => 'The province may not be greater than :max characters.',
            'province.regex' => 'The province format is invalid. Special characters like "<" and ">" are not allowed.',

            'country.required' => 'The country is required.',
            'country.string' => 'The country must be a string.',
            'country.max' => 'The country may not be greater than :max characters.',
            'country.regex' => 'The country format is invalid. Special characters like "<" and ">" are not allowed.',

            'zip_code.required' => 'The zip code is required.',
            'zip_code.string' => 'The zip code must be a string.',
            'zip_code.max' => 'The zip code may not be greater than :max characters.',
            'zip_code.regex' => 'The zip code format is invalid. Special characters like "<" and ">" are not allowed.',

            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least :min characters.',
            'password.letters' => 'The password must contain at least one letter.',
            'password.mixed_case' => 'The password must contain both uppercase and lowercase letters.',
            'password.numbers' => 'The password must contain at least one number.',
            'password.symbols' => 'The password must contain at least one symbol.',
            'password.uncompromised' => 'The password is compromised and not allowed.',
        ];
    }

}
