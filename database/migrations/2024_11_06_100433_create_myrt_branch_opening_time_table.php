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
        Schema::create('myrt_branch_opening_time', function (Blueprint $table) {
            $table->id();
            $table->enum('day', ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'])->index(); ;        
            $table->time('start_time')->nullable()->index(); ;
            $table->time('end_time')->nullable()->index(); ;
            $table->boolean('is_offday')->default(false);
            $table->unsignedBigInteger('branch_id')->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->softDeletes();    
            $table->timestamps();
            
            $table->foreign('branch_id')->references('id')->on('myrt_branch')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('myrt_branch_opening_time');
    }
};
