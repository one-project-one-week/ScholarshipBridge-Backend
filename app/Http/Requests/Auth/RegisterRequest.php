<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'address' => 'nullable',
            'state' => 'nullable',
            'phone' => 'numeric|nullable',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name Field is Required',
            'email.required' => 'Email Field is Required',
            'password.required' => 'Password Field is Required'
        ];
    }
}
