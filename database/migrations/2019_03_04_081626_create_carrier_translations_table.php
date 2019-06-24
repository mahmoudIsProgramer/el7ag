<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarrierTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrier_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('carrier_id')->unsigned();
            $table->string('name');
            $table->string('locale')->index();

            $table->unique(['carrier_id','locale']);
            $table->foreign('carrier_id')
                ->references('id')->on('carriers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carrier_translations');
    }
}
