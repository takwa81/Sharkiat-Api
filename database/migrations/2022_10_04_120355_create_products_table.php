<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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
            $table->string('name');
            $table->decimal('price');
            $table->decimal('discount',5,2)->nullable();
            $table->decimal('sale_price',5,2)->nullable();
            $table->integer('quantity')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_appear_home')->nullable()->default(false);
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->date('expire_date')->nullable();
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
}
