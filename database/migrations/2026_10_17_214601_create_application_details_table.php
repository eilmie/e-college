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
       Schema::create('application_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('room_application_id')->unique()->constrained()->cascadeOnDelete();

        // A. Student
        $table->string('student_phone', 20);
        $table->string('blood_group', 3)->nullable();
        $table->enum('student_type', ['umum', 'biasa', 'beruniform'])->nullable();
        $table->boolean('practical_next_semester')->default(false);
        $table->date('practical_from')->nullable();
        $table->date('practical_to')->nullable();

        // B. Family
        $table->string('father_name');
        $table->string('father_occupation')->nullable();
        $table->unsignedInteger('father_income')->nullable();
        $table->string('father_employer_name')->nullable();
        $table->string('father_employer_phone', 20)->nullable();
        $table->string('mother_name')->nullable();
        $table->string('mother_occupation')->nullable();
        $table->unsignedInteger('mother_income')->nullable();
        $table->string('mother_employer_name')->nullable();
        $table->string('mother_employer_phone', 20)->nullable();
        $table->string('home_address_1');
        $table->string('home_address_2')->nullable();
        $table->string('postcode', 5);
        $table->string('city');
        $table->string('state');
        $table->string('guardian_relationship');
        $table->string('guardian_phone', 20);

        // C. Marital status
        $table->boolean('is_married')->default(false);
        $table->string('spouse_name')->nullable();
        $table->string('spouse_occupation')->nullable();
        $table->unsignedInteger('spouse_income')->nullable();
        $table->string('spouse_phone', 20)->nullable();

        // D. Family standing
        $table->unsignedTinyInteger('birth_order')->nullable();
        $table->unsignedTinyInteger('dependants_private_uni')->default(0);
        $table->unsignedTinyInteger('dependants_public_uni')->default(0);
        $table->unsignedTinyInteger('dependants_secondary')->default(0);
        $table->unsignedTinyInteger('dependants_primary')->default(0);
        $table->unsignedTinyInteger('dependants_preschool')->default(0);
        $table->unsignedTinyInteger('dependants_not_in_school')->default(0);

        // E. Vehicle
        $table->boolean('has_vehicle')->default(false);
        $table->string('vehicle_type')->nullable();
        $table->string('vehicle_model')->nullable();
        $table->string('vehicle_plate', 20)->nullable();

        // F. Emergency contact
        $table->string('emergency_name');
        $table->string('emergency_phone', 20);
        $table->string('emergency_relationship');

        // Medical (the uploaded file itself stays on room_applications)
        $table->string('medical_condition')->nullable();

        $table->timestamp('declared_at')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_details');
    }
};
