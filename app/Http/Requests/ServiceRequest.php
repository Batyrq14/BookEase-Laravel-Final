<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'price' => ['required', 'numeric', 'min:0'],
            'address' => ['nullable', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'category_id' => ['nullable', Rule::exists('categories', 'id')],
        ];

        if ($this->user()?->isAdmin()) {
            $rules['provider_id'] = [
                'nullable',
                Rule::exists('users', 'id')->where(
                    fn ($query) => $query->where('role', UserRole::Provider->value),
                ),
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter a service name.',
            'duration_minutes.required' => 'Please enter the service duration.',
            'duration_minutes.integer' => 'The service duration must be a whole number of minutes.',
            'duration_minutes.max' => 'The service duration may not exceed 1440 minutes.',
            'price.required' => 'Please enter a service price.',
            'price.numeric' => 'The service price must be a valid number.',
            'provider_id.required' => 'Please assign a provider to this service.',
            'latitude.between' => 'Latitude must be between -90 and 90.',
            'longitude.between' => 'Longitude must be between -180 and 180.',
        ];
    }
}
