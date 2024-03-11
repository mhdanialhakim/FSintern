<?php

use App\Models\User;
use App\Models\Driver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Driver::class);
            $table->boolean('is_started')->default(false);
            $table->boolean('is_complete')->default(false);
            $table->json('origin')->nullable();
            $table->json('destination')->nullable();
            $table->string('destination_name')->nullable();
            $table->string('driver_location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
