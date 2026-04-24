<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use RuntimeException;

/**
 * Thrown when a booking request conflicts with an existing appointment.
 *
 * Renders a 409 Conflict for API requests and redirects back with errors for web requests.
 */
class SlotUnavailableException extends RuntimeException
{
    public function __construct(
        string $message = 'This time slot conflicts with an existing booking.',
        public readonly ?int $serviceId = null,
        public readonly ?Carbon $requestedStart = null,
        public readonly ?Carbon $requestedEnd = null,
    ) {
        parent::__construct($message);
    }

    /**
     * Named constructor for conflict scenarios — attaches diagnostic data.
     */
    public static function forConflict(int $serviceId, Carbon $start, Carbon $end): self
    {
        return new self(
            message: __('This time slot conflicts with an existing booking.'),
            serviceId: $serviceId,
            requestedStart: $start,
            requestedEnd: $end,
        );
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
                'error' => 'slot_unavailable',
                'details' => [
                    'service_id' => $this->serviceId,
                    'requested_start' => $this->requestedStart?->toIso8601String(),
                    'requested_end' => $this->requestedEnd?->toIso8601String(),
                ],
            ], 409);
        }

        return back()
            ->withInput()
            ->withErrors(['scheduled_at' => $this->getMessage()]);
    }

    /**
     * Don't report expected business-logic exceptions to the error log.
     */
    public function report(): false
    {
        return false;
    }
}
