<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('trip_members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->unsigned();
            $table->integer('trip_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->enum('type',['driver','guide'])->unsigned();
            $table->integer('user_id')->nullable();
            $table->integer('sendDriver')->default(0);
            $table->integer('sendGuide')->default(0);

            $table->foreign('member_id')
                ->references('id')->on('members')->onDelete('cascade');

            $table->foreign('trip_id')
                ->references('id')->on('trips')->onDelete('cascade');

            $table->foreign('company_id')
                ->references('id')->on('companies')->onDelete('cascade');

            $table->integer('status')->default(0);
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_members');
    }
}
