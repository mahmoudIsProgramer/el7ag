<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestPauseTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_pause_trips', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trip_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->integer('supervisor_id')->unsigned();
            $table->integer('driver_id')->unsigned();
            $table->integer('guide_id')->unsigned();
            $table->integer('bus_id')->unsigned();
            $table->integer('path_id')->unsigned();
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->foreign('trip_id')
                ->references('id')->on('trips')->onDelete('cascade');

            $table->foreign('company_id')
                ->references('id')->on('companies')->onDelete('cascade');

            $table->foreign('supervisor_id')
                ->references('id')->on('supervisors')->onDelete('cascade');

            $table->foreign('driver_id')
                ->references('id')->on('drivers')->onDelete('cascade');

            $table->foreign('guide_id')
                ->references('id')->on('guides')->onDelete('cascade');

            $table->foreign('bus_id')
                ->references('id')->on('buses')->onDelete('cascade');

            $table->foreign('path_id')
                ->references('id')->on('paths')->onDelete('cascade');


            $table->integer('status')->default(1);
            $table->enum('type',['padding','yes','no'])->default('padding');
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
        Schema::dropIfExists('request_pause_trips');
    }
}
