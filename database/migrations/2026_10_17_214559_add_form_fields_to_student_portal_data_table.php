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
         Schema::table('student_portal_data', function (Blueprint $table) {
            $table->string('ic_number')->nullable()->after('student_id');
            $table->string('program_code')->nullable()->after('faculty');
            $table->string('phone')->nullable()->after('program_code');
            $table->string('study_status')->default('Full-time')->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('student_portal_data', function (Blueprint $table) {
            $table->dropColumn(['ic_number', 'program_code', 'phone', 'study_status']);
        });
    }
};
