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
        Schema::create('myrt_restaurant_menu', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->float('price')->default(0.0)->index();
            $table->unsignedBigInteger('category_id')->index();
            $table->tinyInteger('status')->default(1)->comment('0 :inactive , 1:active')->index();
            $table->string('menu_image')->nullable()->index();
            $table->string('description')->nullable()->index();
            $table->tinyInteger('rating')->default(0)->index();
            $table->string('created_by')->nullable()->index();
            $table->string('updated_by')->nullable()->index();
            $table->softDeletes();            
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('myrt_category')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('myrt_restaurant_menu');
    }
};
