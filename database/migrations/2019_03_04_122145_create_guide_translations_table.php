<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuideTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guide_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('guide_id')->unsigned();
            $table->string('name');
            $table->string('locale')->index();

            $table->unique(['guide_id','locale']);
            $table->foreign('guide_id')
                ->references('id')->on('guides')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guide_translations');
    }
}
