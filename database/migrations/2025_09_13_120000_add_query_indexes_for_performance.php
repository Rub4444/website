<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Индексы для частых запросов: WHERE/JOIN по order_id, sku_id, category_id, code, status+created_at.
     */
    public function up(): void
    {
        Schema::table('order_sku', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('sku_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('code');
        });

        Schema::table('skus', function (Blueprint $table) {
            $table->index('product_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('order_sku', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['sku_id']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['code']);
        });

        Schema::table('skus', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
        });
    }
};
