<?php
declare(strict_types=1);
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'name'             => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'price'            => ['required', 'numeric', 'min:0'],
            'address'          => ['nullable', 'string', 'max:500'],
            'latitude'         => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'        => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }
}
