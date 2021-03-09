<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnotherColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')
                ->after('email')
                ->unique();
            $table->boolean('email_verified')
                ->after('username')
                ->default(false);
            $table->boolean('active')
                ->after('email_verified_at')
                ->default(false);
            $table->timestamp('active_at')
                ->after('active')
                ->nullable();
            $table->bigInteger('userable_id')
                ->after('profile_photo_path')
                ->nullable();
            $table->string('userable_type')
                ->after('userable_id')
                ->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_verified', 'username', 'active', 'active_at',
            'userable_id', 'userable_type', 'deleted_at');
        });
    }
}
