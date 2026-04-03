<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Carbon;

class AppointmentController extends Controller
{
    public function index(): View {
        return view('appointments.index', [
            'appointments' => request()->user()->appointments()->with('service')->latest('scheduled_at')->get()
        ]);
    }

    public function create(): View {
        return view('appointments.create', [
            'services' => Service::all()
        ]);
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse {
        $service = Service::findOrFail((int) $request->validated('service_id'));
        $scheduledAt = Carbon::parse($request->validated('scheduled_at'));
        $endTime = $scheduledAt->copy()->addMinutes($service->duration_minutes);

        // Conflict Detection Logic (MVP)
        $appointmentsOfDay = Appointment::with('service')
            ->where('service_id', $service->id)
            ->where('status', AppointmentStatus::Booked->value)
            ->whereDate('scheduled_at', $scheduledAt->toDateString())
            ->get();

        foreach ($appointmentsOfDay as $appt) {
            $apptStart = Carbon::parse($appt->scheduled_at);
            $apptEnd = $apptStart->copy()->addMinutes($appt->service->duration_minutes);

            // Check if times overlap
            if ($scheduledAt->lt($apptEnd) && $endTime->gt($apptStart)) {
                return back()->withInput()->withErrors([
                    'scheduled_at' => __('This time slot conflicts with an existing booking.')
                ]);
            }
        }

        $request->user()->appointments()->create([
            'service_id' => $service->id,
            'scheduled_at' => $scheduledAt,
            'status' => AppointmentStatus::Booked->value,
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment booked successfully.');
    }
    
    public function destroy(Appointment $appointment): RedirectResponse {
        if ($appointment->user_id !== request()->user()->id) {
            abort(403);
        }
        
        $appointment->update(['status' => AppointmentStatus::Cancelled->value]);
        return back()->with('success', 'Appointment cancelled.');
    }
}
