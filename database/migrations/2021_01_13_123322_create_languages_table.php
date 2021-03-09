<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('iso_codes', 5);
            $table->string('country');
            $table->text('description')->nullable();
            $table->string('time_zone')->nullable();
            $table->string('gmt')->nullable();
            $table->string('faker_locale')->nullable();
            $table->text('icon')->nullable()
                ->comment('font awesome or images');
            $table->boolean('status')->default(false);
            $table->timestamps();
            $table->softDeletesTz('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
    }
}
