<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug')->unique();
            $table->json('body');
            $table->json('after_body')->nullable();
            $table->boolean('publish')->default(true);
            $table->boolean('is_widget')->default(false);
            $table->boolean('show_form')->default(true);
            $table->boolean('show_map')->default(true);
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->json('banner')->nullable();
            $table->json('custom_field')->nullable();
            $table->bigInteger('viewer')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')
                ->onDelete('SET NULL');
            $table->foreign('updated_by')->references('id')->on('users')
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
        Schema::dropIfExists('inquiries');
    }
}
