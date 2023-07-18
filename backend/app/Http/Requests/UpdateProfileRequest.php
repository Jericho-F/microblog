<?php

namespace App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'first_name' => 'nullable|string|max:150|regex:/^[a-zA-Z.\s]+$/',
            'last_name' => 'nullable|string|max:150|regex:/^[a-zA-Z.\s]+$/',
            'username' => 'nullable|string|min:8|max:150|regex:/^[^.<>]*$/',
            'birthdate' => [
                'nullable',
                'date',
                'before:' . Carbon::now()->subYears(18)->format('Y-m-d'),
                'after:' . Carbon::now()->subYears(100)->format('Y-m-d'),
            ],
            'mobile_no' => 'nullable|string|max:20|regex:/^[^.<>]*$/',
            'lot_block' => 'nullable|string|max:200|regex:/^[^.<>]*$/',
            'street' => 'nullable|string|max:100|regex:/^[^.<>]*$/',
            'city' => 'nullable|string|max:100|regex:/^[^.<>]*$/',
            'province' => 'nullable|string|max:200|regex:/^[^.<>]*$/',
            'country' => 'nullable|string|max:100|regex:/^[^.<>]*$/',
            'zip_code' => 'nullable|string|max:20|regex:/^[^.<>]*$/',
            'image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
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
            'first_name.required' => 'The first name field is required.',
            'first_name.string' => 'The first name must be a string.',
            'first_name.max' => 'The field should not exceed :max characters.',
            'first_name.regex' => 'The first name format is invalid.',

            'last_name.required' => 'The last name field is required.',
            'last_name.string' => 'The last name must be a string.',
            'last_name.max' => 'The field should not exceed :max characters.',
            'last_name.regex' => 'The last name format is invalid.',

            'username.required' => 'The username field is required.',
            'username.string' => 'The username must be a string.',
            'username.min' => 'The username must be at least :min characters.',
            'username.max' => 'The field should not exceed :max characters.',
            'username.regex' => 'The username format is invalid.',

            'birthdate.required' => 'The birthdate field is required.',
            'birthdate.date' => 'The birthdate must be a valid date.',
            'birthdate.before' => 'You must be at least 18 years old.',
            'birthdate.after' => 'The birthdate is too far in the past.',

            'mobile_no.required' => 'The mobile number field is required.',
            'mobile_no.string' => 'The mobile number must be a string.',
            'mobile_no.max' => 'The field should not exceed :max characters.',
            'mobile_no.regex' => 'The mobile number format is invalid.',

            'lot_block.required' => 'The lot/block field is required.',
            'lot_block.string' => 'The lot/block must be a string.',
            'lot_block.max' => 'The field should not exceed :max characters.',
            'lot_block.regex' => 'The lot/block format is invalid.',

            'street.required' => 'The street field is required.',
            'street.string' => 'The street must be a string.',
            'street.max' => 'The field should not exceed :max characters.',
            'street.regex' => 'The street format is invalid.',

            'city.required' => 'The city field is required.',
            'city.string' => 'The city must be a string.',
            'city.max' => 'The field should not exceed :max characters.',
            'city.regex' => 'The city format is invalid.',

            'province.required' => 'The province field is required.',
            'province.string' => 'The province must be a string.',
            'province.max' => 'The field should not exceed :max characters.',
            'province.regex' => 'The province format is invalid.',

            'country.required' => 'The country field is required.',
            'country.string' => 'The country must be a string.',
            'country.max' => 'The field should not exceed :max characters.',
            'country.regex' => 'The country format is invalid.',

            'zip_code.required' => 'The zip code field is required.',
            'zip_code.string' => 'The zip code must be a string.',
            'zip_code.max' => 'The field should not exceed :max characters.',
            'zip_code.regex' => 'The zip code format is invalid.',

            'image.image' => 'The image must be an image file.',
            'image.mimes' => 'The image must be a file of type: :values.',
            'image.max' => 'The image may not be greater than :max kilobytes.',
        ];
    }
}
