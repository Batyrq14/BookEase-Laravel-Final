<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreProviderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isCreateMode = $this->input('mode') === 'create';

        return [
            'mode' => ['required', Rule::in(['create', 'invite'])],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'password' => [$isCreateMode ? 'required' : 'nullable', 'confirmed', Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'mode.required' => 'Please choose whether to create or invite the provider.',
            'password.required' => 'Please set a password when creating a provider account directly.',
        ];
    }
}
