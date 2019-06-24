<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paths', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->integer('from')->nullable()->unsigned();
            $table->integer('to')->nullable()->unsigned();
            // $table->decimal('price')->nullable();
            $table->tinyInteger('status')->default(1);

            $table->foreign('from')
                ->references('id')->on('destinations')->onDelete('cascade');
            $table->foreign('to')
                ->references('id')->on('destinations')->onDelete('cascade');

            $table->foreign('company_id')
                ->references('id')->on('companies')->onDelete('cascade');
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
        Schema::dropIfExists('paths');
    }
}
