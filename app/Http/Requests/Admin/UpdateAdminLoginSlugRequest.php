<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminLoginSlugRequest extends FormRequest
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
            'login_slug' => [
                'required',
                'string',
                'alpha_dash',
                'min:2',
                'max:50',
                Rule::notIn([
                    'api',
                    'dashboard',
                    'storage',
                    'up',
                    'build',
                    'assets',
                    'css',
                    'js',
                ]),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'login_slug.required' => 'Slug URL login admin wajib diisi.',
            'login_slug.alpha_dash' => 'Slug URL login admin hanya boleh berisi huruf, angka, tanda strip (-), atau garis bawah (_).',
            'login_slug.min' => 'Slug URL login admin minimal 2 karakter.',
            'login_slug.max' => 'Slug URL login admin maksimal 50 karakter.',
            'login_slug.not_in' => 'Slug URL yang dipilih adalah rute sistem yang dilindungi dan tidak dapat digunakan.',
        ];
    }
}
