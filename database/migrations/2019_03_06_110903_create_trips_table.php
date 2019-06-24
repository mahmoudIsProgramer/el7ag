<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->integer('supervisor_id')->unsigned()->nullable();
            $table->integer('user_vendor_id')->unsigned()->nullable();
            $table->integer('guide_id')->unsigned()->nullable();
            $table->integer('driver_id')->unsigned();
            $table->integer('bus_id')->unsigned();
            $table->integer('path_id')->unsigned();

            $table->bigInteger('number_passenger')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('status')->default(1);
            $table->decimal('price',14,2)->default(1);


            $table->foreign('company_id')
                ->references('id')->on('companies')->onDelete('cascade');

            $table->foreign('supervisor_id')
                ->references('id')->on('supervisors')->onDelete('cascade');

            $table->foreign('user_vendor_id')
                ->references('id')->on('user_vendors')->onDelete('cascade');

            $table->foreign('guide_id')
                ->references('id')->on('guides')->onDelete('cascade');

            $table->foreign('path_id')
                ->references('id')->on('paths')->onDelete('cascade');

            $table->foreign('driver_id')
                ->references('id')->on('drivers')->onDelete('cascade');

            $table->foreign('bus_id')
                ->references('id')->on('buses')->onDelete('cascade');
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
        Schema::dropIfExists('trips');
    }
}
