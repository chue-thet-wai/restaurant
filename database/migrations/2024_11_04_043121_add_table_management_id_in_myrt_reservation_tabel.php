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
        Schema::table('myrt_reservations', function (Blueprint $table) {
            $table->unsignedBigInteger('table_management_id')->nullable()->after('seat_count'); 
           // $table->unsignedBigInteger('timeslot_id')->nullable()->after('table_management_id'); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('myrt_reservation_tabel', function (Blueprint $table) {
            $table->dropColumn('table_management_id');
        });
    }
};
