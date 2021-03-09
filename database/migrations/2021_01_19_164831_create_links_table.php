<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug');
            $table->json('description')->nullable();
            $table->json('cover')->nullable();
            $table->json('banner')->nullable();
            $table->boolean('publish')->default(true);
            $table->unsignedBigInteger('custom_view_id')->nullable();
            $table->integer('list_limit')->nullable();
            $table->boolean('is_widget')->default(false);
            $table->bigInteger('viewer')->default(0);
            $table->integer('position')->nullable();
            $table->json('meta_data')->nullable();
            $table->json('custom_field')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('custom_view_id')->references('id')->on('template_view')
                ->onDelete('SET NULL');
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
        Schema::dropIfExists('links');
    }
}
