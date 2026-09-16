<?php

namespace App\Http\Requests\Admin;

use App\Enums\CertificateStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'certificate_number' => [
                'required', 'string', 'max:80',
                Rule::unique('certificates', 'certificate_number')->ignore($this->route('certificate')),
            ],
            'recipient_name' => ['required', 'string', 'max:150'],
            'ic_number' => ['nullable', 'string', 'max:30'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'course_title' => ['required', 'string', 'max:180'],
            'issued_at' => ['required', 'date'],
            'expires_at' => ['nullable', 'date', 'after:issued_at'],
            'status' => ['required', Rule::enum(CertificateStatus::class)],
            'grade' => ['nullable', 'string', 'max:40'],
        ];
    }
}
