<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDestinationTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destination_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('destination_id')->unsigned();
            $table->string('name');
            $table->string('locale')->index();

            $table->unique(['destination_id','locale']);
            $table->foreign('destination_id')
                ->references('id')->on('destinations')->onDelete('cascade');
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
        Schema::dropIfExists('destination_translations');
    }
}
