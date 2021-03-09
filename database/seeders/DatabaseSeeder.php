<?php

namespace Database\Seeders;

use Database\Seeders\Users\ACL\PermissionSeeder;
use Database\Seeders\Users\ACL\RoleHasPermissionSeeder;
use Database\Seeders\Users\ACL\RoleSeeder;
use Database\Seeders\Users\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleHasPermissionSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(ConfigurationSeeder::class);
    }
}
