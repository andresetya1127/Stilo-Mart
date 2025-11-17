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
        // Check if index exists before dropping
        $indexes = \DB::select("SHOW INDEX FROM products WHERE Key_name = 'products_barcode_unique'");
        if (count($indexes) > 0) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropUnique(['barcode']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unique('barcode');
        });
    }
};
