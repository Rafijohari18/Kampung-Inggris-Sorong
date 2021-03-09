<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->integer('parent')->default(0);
            $table->json('title');
            $table->string('slug');
            $table->json('intro')->nullable();
            $table->json('content')->nullable();
            $table->json('cover')->nullable();
            $table->json('banner')->nullable();
            $table->boolean('publish')->default(true);
            $table->boolean('public')->default(true);
            $table->unsignedBigInteger('custom_view_id')->nullable();
            $table->bigInteger('viewer')->default(0);
            $table->boolean('is_widget')->default(false);
            $table->integer('position');
            $table->json('meta_data')->nullable();
            $table->json('custom_field')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletesTz('deleted_at', 0);

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
        Schema::dropIfExists('pages');
    }
}
