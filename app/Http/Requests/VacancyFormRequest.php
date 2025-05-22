<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VacancyFormRequest extends FormRequest
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
            'position' => ['required', 'max:50'],
            'name' => ['required', 'max:50'],
            'mail' => ['required', 'email:rfc,dns'],
            'phone' => ['required', 'digits_between:9,14'],
            'cv' => ['required', 'file', 'mimes:pdf,docx', 'max:2048'],
            'cover_letter' => ['required', 'file', 'mimes:pdf,docx,txt', 'max:1024'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.*
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors(),
            'success' => false,
            'status' => 422,
        ], 422));
    }
}
