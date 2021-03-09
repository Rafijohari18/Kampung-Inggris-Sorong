<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogueProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogue_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('catalog_category_id');
            $table->json('title');
            $table->string('slug');
            $table->json('intro')->nullable();
            $table->json('content')->nullable();
            $table->integer('price')->nullable();
            $table->integer('quantity')->nullable();
            $table->boolean('is_discount')->default(false);
            $table->integer('discount')->nullable();
            $table->json('cover')->nullable();
            $table->json('banner')->nullable();
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

            $table->foreign('catalog_category_id')->references('id')
                ->on('catalogue_categories')->cascadeOnDelete();
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
        Schema::dropIfExists('catalogue_products');
    }
}
