<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_customers', function (Blueprint $table) {
            $table->increments('customer_id');
            $table->string('customer_name');
            $table->string('email');
            $table->string('tel_num', 14);
            $table->string('address');
            $table->boolean('is_active')
                ->default(1)
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
        Schema::dropIfExists('mst_customers');
    }
}
