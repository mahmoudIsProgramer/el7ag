<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->integer('driver_id')->unsigned();
            $table->integer('carrier_id')->unsigned();
            $table->integer('guide_id')->unsigned();
            $table->integer('userVendor')->nullable();
            $table->bigInteger('number_bus')->unique();
            $table->bigInteger('plate_number')->unique();
            $table->integer('number_chairs')->nullable();
            $table->tinyInteger('status')->default(1);

            $table->foreign('company_id')
                ->references('id')->on('companies')->onDelete('cascade');

            $table->foreign('driver_id')
                ->references('id')->on('drivers')->onDelete('cascade');
            $table->foreign('carrier_id')
                ->references('id')->on('carriers')->onDelete('cascade');

            $table->foreign('carrier_id')
                ->references('id')->on('guides')->onDelete('cascade');
            
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
        Schema::dropIfExists('buses');
    }
}
