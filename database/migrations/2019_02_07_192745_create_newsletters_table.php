<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewslettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('newsletters'))
            Schema::create('newsletters', function (Blueprint $table) {
                $table->increments('id');
                $table->string('email')->unique();
                $table->string('token',65);
                $table->boolean('active')->default(false);
                $table->integer('pos')->default(0);
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
//        Schema::dropIfExists('newsletters');
    }
}
