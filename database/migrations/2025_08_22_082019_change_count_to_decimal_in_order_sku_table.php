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
        Schema::table('order_sku', function (Blueprint $table) {
            $table->decimal('count', 8, 3)->change(); // меняем int на decimal
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_sku', function (Blueprint $table) {
            $table->integer('count')->change(); // возвращаем обратно int
        });
    }
};
