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
        Schema::table('classes', function (Blueprint $table) {
            $table->foreignId('academic_year_id')->nullable()->after('major')->constrained('academic_years')->nullOnDelete();
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->string('code')->nullable()->unique()->after('id');
            $table->integer('credit_hours')->default(2)->after('name');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->integer('max_hours_per_week')->default(30)->after('nip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('max_hours_per_week');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['code', 'credit_hours']);
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });
    }
};
