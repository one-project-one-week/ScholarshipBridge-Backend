<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScholarshipRequest extends FormRequest
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
            'title' => 'required',
            'description' => 'required',
            'organization' => 'required',
            'eligibility'  => 'required',
            'category'  => 'required',
            'program'  => 'required',
            'field'  => 'required',
            'country'  => 'required',
            'deadline'  => 'required',
            'apply_link'  => 'required',
            'photo_link'  => 'required',
        ];
    }
}
