<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->foreignId('academic_year_id')->nullable()->after('id')->constrained('academic_years')->nullOnDelete();
            $table->foreignId('room_id')->nullable()->after('class_id')->constrained('rooms')->nullOnDelete();
            $table->string('day_of_week')->default('monday')->after('room_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropForeign(['room_id']);
            $table->dropColumn(['academic_year_id', 'room_id', 'day_of_week']);
        });
    }
};
