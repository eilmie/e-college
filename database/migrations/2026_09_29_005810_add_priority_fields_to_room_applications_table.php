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
        Schema::table('room_applications', function (Blueprint $table) {
            $table->dropColumn('priority_score');
            $table->enum('medical_status', ['none', 'pending', 'verified', 'rejected'])->default('none')->after('semester');
            $table->string('medical_proof_path')->nullable()->after('medical_status');
            $table->decimal('merit_score', 5, 2)->default(0)->after('medical_proof_path');
            $table->boolean('has_disciplinary_record')->default(false)->after('merit_score');
        });
    }

    public function down(): void
    {
        Schema::table('room_applications', function (Blueprint $table) {
            $table->dropColumn(['medical_status', 'medical_proof_path', 'merit_score', 'has_disciplinary_record']);
            $table->unsignedInteger('priority_score')->default(0);
        });
    }
};
