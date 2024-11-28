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
        Schema::create('myrt_reservations', function (Blueprint $table) {
            $table->id();  
            $table->string('order_id')->unique()->index();  
            $table->unsignedBigInteger('branch_id')->index();  
            $table->tinyInteger('status')->default(1)->comment('1 :Pending , 2:Confirm , 3: Reject')->index(); 
            $table->string('name')->nullable()->index(); 
            $table->string('phone')->nullable()->index(); 
            $table->string('email')->nullable()->index();  
            $table->date('date')->index(); 
            $table->time('time')->index();  
            $table->integer('seat_count')->index();  
            $table->longText('reject_note')->nullable();
            $table->string('receipt')->nullable()->index();
            $table->string('created_by')->nullable()->index();
            $table->string('updated_by')->nullable()->index();
            $table->timestamps();  

            $table->foreign('branch_id')->references('id')->on('myrt_branch')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
