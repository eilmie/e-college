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
    Schema::create('rooms', function (Blueprint $table) {
        $table->id();
        $table->string('block'); // e.g. "Block A"
        $table->string('room_number');
        $table->unsignedTinyInteger('floor');
        $table->unsignedTinyInteger('capacity'); // e.g. 2 for a double room
        $table->enum('gender', ['male', 'female']);
        $table->enum('status', ['available', 'full', 'maintenance'])->default('available');
        $table->timestamps();

        $table->unique(['block', 'room_number']);
    });
}

public function down(): void
{
    Schema::dropIfExists('rooms');
}
};
