<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortadasTable extends Migration
{
    public function up()
    {
        Schema::create('portadas', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('url')->nullable();
            $table->boolean('target')->default(0);

            $table->boolean('active')->default(1);
            $table->integer('pos')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('portadas');
    }
}
