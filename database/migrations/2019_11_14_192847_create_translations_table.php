<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('translations'))
            Schema::create('translations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('translable_id')->nullable();
                $table->string('translable_type')->nullable();
                $table->string('locale')->default('es');
                $table->string('friendly_url')->nullable();
                $table->string('name')->nullable();
                $table->text('description')->nullable();
                $table->binary('long_description')->nullable();
                $table->boolean('active')->default(1);
                $table->integer('pos')->default(0);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('translations');
    }
}
