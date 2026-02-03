<?php

use App\Models\BusRoute;
use App\Models\BusShape;
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
        Schema::create('bus_trips', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->foreignIdFor(BusRoute::class, 'route_id')->nullable();
            $table->foreignIdFor(BusShape::class, 'shape_id')->nullable();
            $table->integer('service_id')->nullable();
            $table->string('trip_short_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_trips');
    }
};
