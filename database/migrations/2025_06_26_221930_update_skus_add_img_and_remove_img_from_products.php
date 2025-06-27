<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('skus', function (Blueprint $table) {
            $table->string('image')->nullable()->after('price'); // добавляем к SKU
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image'); // удаляем из Product
        });
    }

    public function down(): void
    {
        Schema::table('skus', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('image')->nullable();
        });
    }
};

