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
        Schema::table('hiking_trails', function (Blueprint $table) {
            $table->string('gpx_file_path')->nullable()->after('name');
            $table->integer('max_elevation')->nullable()->after('distance_km');
            $table->integer('min_elevation')->nullable()->after('max_elevation');
            $table->json('coordinates')->nullable()->after('map_geojson');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hiking_trails', function (Blueprint $table) {
            $table->dropColumn(['gpx_file_path', 'max_elevation', 'min_elevation', 'coordinates']);
        });
    }
};
