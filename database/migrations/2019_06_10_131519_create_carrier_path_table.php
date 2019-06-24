<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarrierPathTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrier_path', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('carrier_id')->unsigned();
            $table->integer('path_id')->unsigned();
            $table->integer('price');


            $table->foreign('carrier_id')
                ->references('id')->on('carriers')->onDelete('cascade');

            $table->foreign('path_id')
            ->references('id')->on('paths')->onDelete('cascade');
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
        Schema::dropIfExists('carrier_path');
    }
}
