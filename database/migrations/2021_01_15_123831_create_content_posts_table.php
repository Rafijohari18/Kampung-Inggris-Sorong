<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('category_id');
            $table->json('title');
            $table->string('slug');
            $table->json('intro')->nullable();
            $table->json('content')->nullable();
            $table->json('cover')->nullable();
            $table->json('banner')->nullable();
            $table->year('publish_year');
            $table->tinyInteger('publish_month');
            $table->boolean('publish')->default(true);
            $table->boolean('public')->default(true);
            $table->boolean('selection')->default(false);
            $table->unsignedBigInteger('custom_view_id')->nullable();
            $table->bigInteger('viewer')->default(0);
            $table->boolean('is_widget')->default(false);
            $table->integer('position')->nullable();
            $table->json('meta_data')->nullable();
            $table->json('custom_field')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletesTz('deleted_at', 0);

            $table->foreign('section_id')->references('id')
                ->on('content_sections')->cascadeOnDelete();
            $table->foreign('category_id')->references('id')
                ->on('content_categories')->cascadeOnDelete();
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
        Schema::dropIfExists('content_posts');
    }
}
