<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserVendorTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_vendor_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_vendor_id')->unsigned();
            $table->string('name');
            $table->string('locale')->index();

            $table->unique(['user_vendor_id','locale']);
            $table->foreign('user_vendor_id')->references('id')->on('user_vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_vendor_translations');
    }
}
