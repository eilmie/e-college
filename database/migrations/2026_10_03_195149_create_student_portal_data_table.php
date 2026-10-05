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
        Schema::create('student_portal_data', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->string('full_name');
            $table->string('faculty');
            $table->enum('gender', ['male', 'female']);
            $table->string('program_level'); // 'diploma' or 'degree'
            $table->unsignedTinyInteger('current_semester');
            $table->decimal('cgpa', 3, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_portal_data');
    }
};
