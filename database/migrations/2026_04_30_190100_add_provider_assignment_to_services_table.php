<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->foreignId('provider_id')->nullable()->after('price')->constrained('users')->nullOnDelete();
            $table->foreignId('creator_user_id')->nullable()->after('provider_id')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropConstrainedForeignId('creator_user_id');
            $table->dropConstrainedForeignId('provider_id');
        });
    }
};
