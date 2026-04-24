<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentCalendarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'month' => ['required', 'date_format:Y-m'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.required' => 'Please choose a service before loading the calendar.',
            'service_id.exists' => 'The selected service is invalid.',
            'month.required' => 'The calendar month is required.',
            'month.date_format' => 'The calendar month must use the YYYY-MM format.',
        ];
    }
}
