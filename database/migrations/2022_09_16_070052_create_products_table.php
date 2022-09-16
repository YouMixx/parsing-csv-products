<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique();
            $table->string('product_code')->nullable();
            $table->string('language')->nullable();
            $table->string('category')->nullable();
            $table->decimal('last_price', 9, 2)->nullable();
            $table->decimal('price', 9, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->string('product_name')->nullable();
            $table->longText('description')->nullable();
            $table->string('seo_name')->nullable();
            $table->string('short_description')->nullable();
            $table->enum('status', ['A', 'D'])->nullable();
            $table->string('vendor')->nullable();
            $table->longText('features')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
