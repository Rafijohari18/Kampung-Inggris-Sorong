<?php

namespace Database\Seeders\Users;

use App\Models\Users\User;
use App\Models\Users\UserInformation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            0 => [
                'name' => 'Developer 4VM',
                'email' => 'developer@4visionmedia.com',
                'email_verified' => 1,
                'email_verified_at' => now(),
                'username' => '4vmdev',
                'password' => Hash::make('myDev4vm#'),
                'active' => 1,
                'active_at' => now(),
                'roles' => 'super',
            ],
            1 => [
                'name' => 'Support 4VM',
                'email' => 'support@4visionmedia.com',
                'email_verified' => 1,
                'email_verified_at' => now(),
                'username' => '4vmsup',
                'password' => Hash::make('mySup4vm#'),
                'active' => 1,
                'active_at' => now(),
                'roles' => 'support',
            ],
            2 => [
                'name' => 'Client',
                'email' => 'client@gmail.com',
                'email_verified' => 0,
                'email_verified_at' => null,
                'username' => 'admweb',
                'password' => Hash::make('myWeb2020#'),
                'active' => 0,
                'active_at' => null,
                'roles' => 'client',
            ],
        ];

        foreach ($users as $key) {
            $user = User::create([
                'name' => $key['name'],
                'email' => $key['email'],
                'email_verified' => $key['email_verified'],
                'email_verified_at' => $key['email_verified_at'],
                'username' => $key['username'],
                'password' => $key['password'],
                'active' => $key['active'],
                'active_at' => $key['active_at'],
                'profile_photo_path' => [
                    'filename' => null,
                    'title' => null,
                    'alt' => null,
                ],
            ]);

            $user->assignRole($key['roles']);

            $info = UserInformation::create([
                'user_id' => $user->id,
                'general' => [
                    'date_of_birthday' => null,
                    'place_of_birthday' => null,
                    'gender' => null,
                    'address' => null,
                    'phone' => null,
                    'description' => null,
                ],
                'socmed' => [
                    'facebook' => null,
                    'instagram' => null,
                    'twitter' => null,
                    'pinterest' => null,
                    'linkedin' => null,
                ],
            ]);
        }
    }
}
