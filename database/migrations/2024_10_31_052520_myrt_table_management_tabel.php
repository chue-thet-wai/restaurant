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
        Schema::create('myrt_table_management', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('table_id')->index();
            $table->unsignedBigInteger('timeslot_id')->index();
            $table->date('reservation_date')->index(); 
            $table->boolean('is_available')->default(true)->index(); 
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->timestamps();  

            $table->foreign('table_id')->references('id')->on('myrt_tables')->onDelete('cascade');
            $table->foreign('timeslot_id')->references('id')->on('myrt_time_slots')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('myrt_table_management');
    }
};
