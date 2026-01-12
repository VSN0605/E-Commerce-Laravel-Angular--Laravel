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
        Schema::create('products', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('product_name');
            $table->string('product_description');
            $table->integer('product_price');
            $table->string('product_image');
            $table->integer('product_quantity');
            $table->string('product_company');
            $table->foreignId('category_id')
                ->constrained('category')
                ->restrictOnDelete();
            $table->string('created_by');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
