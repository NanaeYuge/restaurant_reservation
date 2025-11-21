<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->dateTime('reserved_at');
            $table->unsignedTinyInteger('num_of_guests');
            $table->string('status', 20)->default('booked');
            $table->timestamps();
            $table->index(['shop_id','reserved_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('reservations'); }
};
