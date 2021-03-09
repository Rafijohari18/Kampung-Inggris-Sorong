<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_sections', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug');
            $table->json('description')->nullable();
            $table->boolean('public')->default(true);
            $table->boolean('cover_required')->default(false)
                ->comment('For cover posts');
            $table->string('order_field');
            $table->string('order_by', 4);
            $table->string('extra')->nullable();
            $table->unsignedBigInteger('list_view_id')->nullable();
            $table->unsignedBigInteger('detail_view_id')->nullable();
            $table->integer('limit_category')->nullable();
            $table->integer('limit_post')->nullable();
            $table->integer('post_selection')->nullable();
            $table->boolean('is_widget')->default(false);
            $table->json('banner')->nullable();
            $table->bigInteger('viewer')->default(0);
            $table->json('custom_field')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletesTz('deleted_at', 0);

            $table->foreign('list_view_id')->references('id')->on('template_view')
                ->onDelete('SET NULL');
            $table->foreign('detail_view_id')->references('id')->on('template_view')
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
        Schema::dropIfExists('content_sections');
    }
}
