<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_logins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_user');
            $table->dateTime('data_start');
            $table->dateTime('data_end')->nullable();
            $table->string('ip');
            $table->string('navegador')->nullable();
            $table->timestamps();

            $table->foreign("fk_user")->references("id")->on("users")->onDelete("CASCADE")->onUpdate("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_logins');
    }
};
