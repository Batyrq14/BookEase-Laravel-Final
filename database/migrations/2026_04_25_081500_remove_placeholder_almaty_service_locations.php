<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('services')) {
            return;
        }

        DB::table('services')
            ->where('address', 'Almaty, Kazakhstan')
            ->where('latitude', 43.2389490)
            ->where('longitude', 76.8897090)
            ->update([
                'address' => null,
                'latitude' => null,
                'longitude' => null,
            ]);
    }

    public function down(): void
    {
        // Intentionally left blank; this only removes generated placeholder values.
    }
};
