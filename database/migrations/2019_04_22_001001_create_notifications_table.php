<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trip_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->integer('supervisor_id')->unsigned();
            $table->integer('driver_id')->unsigned();
            $table->integer('guide_id')->unsigned();
            $table->text('message')->nullable();
            $table->text('headings')->nullable();

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
        Schema::dropIfExists('notifications');
    }
}
