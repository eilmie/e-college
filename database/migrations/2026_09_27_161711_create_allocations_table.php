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
    Schema::create('allocations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('room_id')->constrained()->cascadeOnDelete();
        $table->foreignId('room_application_id')->nullable()->constrained()->nullOnDelete();
        $table->string('semester');
        $table->timestamp('allocated_at')->useCurrent();
        $table->enum('status', ['active', 'ended'])->default('active');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('allocations');
}
};
