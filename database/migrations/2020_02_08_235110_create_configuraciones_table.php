<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app_name');
            $table->string('email_from_address')->nullable();
            $table->string('email_to_contact')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('recaptcha_public')->nullable();
            $table->string('recaptcha_secret')->nullable();
            $table->text('h_script')->nullable();
            $table->text('f_script')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuraciones');
    }
}
