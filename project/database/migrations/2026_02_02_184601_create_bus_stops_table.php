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
        Schema::create('bus_stops', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('stop_code')->nullable();
            $table->string('stop_name');
            $table->string('stop_desc')->nullable();
            $table->double('stop_lat')->nullable();
            $table->double('stop_lon')->nullable();
            $table->double('zone_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_stops');
    }
};
