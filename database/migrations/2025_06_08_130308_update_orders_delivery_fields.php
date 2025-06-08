<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Удаляем старые поля
            $table->dropColumn(['address', 'latitude', 'longitude']);

            // Добавляем новые поля
            $table->string('delivery_city')->nullable();
            $table->string('delivery_street')->nullable();
            $table->string('delivery_home')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Восстанавливаем старые поля
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Удаляем новые поля
            $table->dropColumn(['delivery_city', 'delivery_street', 'delivery_home']);
        });
    }
};
