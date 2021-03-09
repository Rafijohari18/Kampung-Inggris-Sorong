<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleryPlaylistsVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_playlists_video', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('playlist_id');
            $table->string('thumbnail')->nullable();
            $table->string('file')->nullable();
            $table->string('file_type')->nullable()->comment('Mime Type');
            $table->bigInteger('file_size')->nullable()->comment('Byte');
            $table->json('title')->nullable();
            $table->json('description')->nullable();
            $table->string('youtube_id')->nullable();
            $table->integer('position');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('playlist_id')->references('id')->on('gallery_playlists')
                ->cascadeOnDelete();
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
        Schema::dropIfExists('gallery_playlists_video');
    }
}
