<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dateTime('ends_at')->nullable()->after('scheduled_at');
        });

        // Backfill ends_at for existing rows using service duration.
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement(<<<'SQL'
                UPDATE appointments
                SET ends_at = scheduled_at + (
                    SELECT (s.duration_minutes || ' minutes')::interval
                    FROM services s WHERE s.id = appointments.service_id
                )
                WHERE ends_at IS NULL
            SQL);
        } else {
            // SQLite fallback (used in tests).
            DB::statement(<<<'SQL'
                UPDATE appointments
                SET ends_at = datetime(scheduled_at, '+' || (
                    SELECT s.duration_minutes FROM services s WHERE s.id = appointments.service_id
                ) || ' minutes')
                WHERE ends_at IS NULL
            SQL);
        }

        // Composite index for the overlap query.
        Schema::table('appointments', function (Blueprint $table) {
            $table->index(
                ['service_id', 'status', 'scheduled_at', 'ends_at'],
                'appointments_overlap_idx',
            );
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex('appointments_overlap_idx');
            $table->dropColumn('ends_at');
        });
    }
};
