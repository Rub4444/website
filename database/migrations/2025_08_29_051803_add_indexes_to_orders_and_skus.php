<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {


        Schema::table('products', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('code');
        });

    }

    public function down(): void {

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['code']);

        });
    }
};
