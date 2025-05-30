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
        Schema::create('sku_property_option', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('property_option_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('sku_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::dropIfExists('sku_property_option');
}

};
