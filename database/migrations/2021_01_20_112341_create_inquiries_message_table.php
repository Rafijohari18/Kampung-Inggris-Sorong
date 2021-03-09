<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiriesMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiries_message', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inquiry_id');
            $table->ipAddress('ip_address');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->json('custom_field')->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('exported')->default(false);
            $table->timestamp('submit_time');

            $table->foreign('inquiry_id')->references('id')->on('inquiries')
            ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inquiries_message');
    }
}
