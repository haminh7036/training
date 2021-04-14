<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_products', function (Blueprint $table) {
            $table->string('product_id', 20)
                ->primary();
            $table->string('product_name');
            $table->string('product_image')
                ->nullable();
            $table->unsignedDecimal('product_price', 10)
                ->default(0);
            $table->tinyInteger('is_sales')
                ->default(1);
            $table->text('description')
                ->nullable();
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
        Schema::dropIfExists('mst_products');
    }
}
