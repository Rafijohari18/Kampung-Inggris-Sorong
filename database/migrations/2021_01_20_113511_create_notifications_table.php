<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_from')->nullable();
            $table->unsignedBigInteger('user_to')->nullable();
            $table->tinyInteger('type')->default(0);
            $table->string('icon');
            $table->string('color');
            $table->string('title');
            $table->text('content');
            $table->string('link');
            $table->boolean('read')->default(0);
            $table->timestamps();

            $table->foreign('user_from')->references('id')->on('users')
                ->onDelete('SET NULL');
            $table->foreign('user_to')->references('id')->on('users')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
