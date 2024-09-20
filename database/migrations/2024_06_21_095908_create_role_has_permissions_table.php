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
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            
            // Add custom names for the foreign key constraints
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->name('fk_role_permissions_role_id');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade')->name('fk_role_permissions_permission_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_has_permissions');
    }
};