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
        Schema::create('bus_routes', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('agency_id')->nullable();
            $table->string('route_short_name')->nullable();
            $table->string('route_long_name')->nullable();
            $table->string('route_desc')->nullable();
            $table->integer('route_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_routes');
    }
};
