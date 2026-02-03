<?php

use App\Models\BusStop;
use App\Models\BusTrip;
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
        Schema::create('bus_stop_times', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(BusTrip::class, 'trip_id')->nullable();
            $table->string('arrival_time')->nullable();
            $table->string('departure_time')->nullable();
            $table->foreignIdFor(BusStop::class, 'stop_id')->nullable();
            $table->integer('stop_sequence')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_stop_times');
    }
};
