<?php

namespace Database\Seeders\Users\ACL;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'super',
            'support',
            'client',
        ];

        foreach ($roles as $value) {
            Role::create([
                'name' => $value,
                'guard_name' => 'web',
                'default_data' => 1,
            ]);
        }
    }
}
