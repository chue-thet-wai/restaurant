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
        Schema::create('myrt_order_menu', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 255)->index();
            $table->unsignedBigInteger('menu_id')->index();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->string('remark')->nullable()->index();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('order_id')->references('order_id')->on('myrt_reservations')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('myrt_restaurant_menu')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('myrt_order_menu');
    }
};
