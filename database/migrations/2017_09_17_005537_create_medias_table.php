<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group', 20);
            $table->string('url', 500);
            $table->string('extension', 10);
            $table->string('type', 10);
            $table->binary('metas');
            $table->string('video_id');
            $table->string('video_thumbnail');
            $table->integer('mediable_id');
            $table->string('mediable_type');
            $table->boolean('active')->default(1);
            $table->integer('pos')->default(0);
            $table->timestamps();
        });

        DB::update("ALTER TABLE medias AUTO_INCREMENT = 1000;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medias');
    }
}
