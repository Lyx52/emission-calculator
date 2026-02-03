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
        Schema::create('cached_stop_directions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(BusTrip::class, 'trip_id')->nullable();
            $table->foreignIdFor(BusStop::class, 'from_stop_id')->nullable();
            $table->foreignIdFor(BusStop::class, 'to_stop_id')->nullable();
            $table->text('response')->default('{}');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cached_stop_directions');
    }
};
