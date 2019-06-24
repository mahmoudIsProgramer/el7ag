<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->integer('userVendor')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('address')->nullable();
            $table->string('ssn')->nullable();
            $table->string('nationality')->nullable();
            $table->date('birthday')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('user_token')->nullable();
            $table->text('firebaseToken')->nullable();
            
            $table->string('deviceType')->nullable();

            $table->string('player_ids')->nullable();
            $table->foreign('company_id')
                ->references('id')->on('companies')->onDelete('cascade');

            $table->rememberToken();
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
        Schema::dropIfExists('members');
    }
}
