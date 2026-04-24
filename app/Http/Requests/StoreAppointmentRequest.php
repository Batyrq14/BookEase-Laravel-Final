<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Throwable;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => ['required', 'exists:services,id'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $scheduledAt = $this->input('scheduled_at');

            if (! is_string($scheduledAt) || $scheduledAt === '') {
                return;
            }

            try {
                $bookingDate = Carbon::parse($scheduledAt);
            } catch (Throwable) {
                return;
            }

            if ($bookingDate->isWeekend()) {
                $validator->errors()->add(
                    'scheduled_at',
                    'Appointments cannot be booked on weekends.',
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'service_id.required' => 'Please choose a service.',
            'service_id.exists' => 'The selected service is invalid.',
            'scheduled_at.required' => 'Please choose a booking date and time.',
            'scheduled_at.date' => 'The booking date and time must be a valid date.',
            'scheduled_at.after' => 'Appointments must be booked for a future date and time.',
            'notes.max' => 'Notes may not be greater than 1000 characters.',
        ];
    }
}
