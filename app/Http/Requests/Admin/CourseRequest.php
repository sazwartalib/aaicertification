<?php

namespace App\Http\Requests\Admin;

use App\Enums\CourseLevel;
use App\Enums\DeliveryMode;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
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
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:180'],
            'slug' => [
                'required', 'string', 'max:200', 'alpha_dash',
                Rule::unique('courses', 'slug')->ignore($this->route('course')),
            ],
            'summary' => ['required', 'string', 'max:300'],
            'description' => ['required', 'string'],
            'level' => ['required', Rule::enum(CourseLevel::class)],
            'delivery_mode' => ['required', Rule::enum(DeliveryMode::class)],
            'duration' => ['required', 'string', 'max:80'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'accreditation_body' => ['nullable', 'string', 'max:150'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => filled($this->input('slug'))
                ? Str::slug($this->string('slug')->toString())
                : Str::slug($this->string('title')->toString()),
            'is_featured' => $this->boolean('is_featured'),
            'is_published' => $this->boolean('is_published'),
            'sort_order' => $this->integer('sort_order'),
        ]);
    }
}
