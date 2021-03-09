<?php

namespace Database\Seeders\Users\ACL;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleHasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolePermission = [
            0 => [
                'permission_id' => 1,
            ],
            1 => [
                'permission_id' => 2,
            ],
            2 => [
                'permission_id' => 3,
            ],
            3 => [
                'permission_id' => 4,
            ],
            4 => [
                'permission_id' => 5,
            ],
            5 => [
                'permission_id' => 6,
            ],
            6 => [
                'permission_id' => 7,
            ],
            7 => [
                'permission_id' => 8,
            ],
            8 => [
                'permission_id' => 9,
            ],
            9 => [
                'permission_id' => 10,
            ],
            10 => [
                'permission_id' => 11,
            ],
            11 => [
                'permission_id' => 12,
            ],
            12 => [
                'permission_id' => 13,
            ],
            13 => [
                'permission_id' => 14,
            ],
            14 => [
                'permission_id' => 15,
            ],
            15 => [
                'permission_id' => 16,
            ],
            16 => [
                'permission_id' => 17,
            ],
            17 => [
                'permission_id' => 18,
            ],
            18 => [
                'permission_id' => 19,
            ],
            19 => [
                'permission_id' => 20,
            ],
            20 => [
                'permission_id' => 21,
            ],
            21 => [
                'permission_id' => 22,
            ],
            22 => [
                'permission_id' => 23,
            ],
            23 => [
                'permission_id' => 24,
            ],
            24 => [
                'permission_id' => 25,
            ],
            25 => [
                'permission_id' => 26,
            ],
            26 => [
                'permission_id' => 27,
            ],
            27 => [
                'permission_id' => 28,
            ],
            28 => [
                'permission_id' => 29,
            ],
            29 => [
                'permission_id' => 30,
            ],
            30 => [
                'permission_id' => 31,
            ],
            31 => [
                'permission_id' => 32,
            ],
            32 => [
                'permission_id' => 33,
            ],
            33 => [
                'permission_id' => 34,
            ],
            34 => [
                'permission_id' => 35,
            ],
            35 => [
                'permission_id' => 36,
            ],
            36 => [
                'permission_id' => 37,
            ],
            37 => [
                'permission_id' => 38,
            ],
            38 => [
                'permission_id' => 39,
            ],
            39 => [
                'permission_id' => 40,
            ],
            40 => [
                'permission_id' => 41,
            ],
            41 => [
                'permission_id' => 42,
            ],
            42 => [
                'permission_id' => 43,
            ],
            43 => [
                'permission_id' => 44,
            ],
            44 => [
                'permission_id' => 45,
            ],
            45 => [
                'permission_id' => 46,
            ],
            46 => [
                'permission_id' => 47,
            ],
            47 => [
                'permission_id' => 48,
            ],
            48 => [
                'permission_id' => 49,
            ],
            49 => [
                'permission_id' => 50,
            ],
            50 => [
                'permission_id' => 51,
            ],
            51 => [
                'permission_id' => 52,
            ],
            52 => [
                'permission_id' => 53,
            ],
            53 => [
                'permission_id' => 54,
            ],
            54 => [
                'permission_id' => 55,
            ],
            55 => [
                'permission_id' => 56,
            ],
            56 => [
                'permission_id' => 57,
            ],
            57 => [
                'permission_id' => 58,
            ],
            58 => [
                'permission_id' => 59,
            ],
            59 => [
                'permission_id' => 60,
            ],
            60 => [
                'permission_id' => 61,
            ],
            61 => [
                'permission_id' => 62,
            ],
            62 => [
                'permission_id' => 63,
            ],
            63 => [
                'permission_id' => 64,
            ],
            64 => [
                'permission_id' => 65,
            ],
            65 => [
                'permission_id' => 66,
            ],
            66 => [
                'permission_id' => 67,
            ],
            67 => [
                'permission_id' => 68,
            ],
            68 => [
                'permission_id' => 69,
            ],
            69 => [
                'permission_id' => 70,
            ],
            70 => [
                'permission_id' => 71,
            ],
            71 => [
                'permission_id' => 72,
            ],
        ];

        foreach ($rolePermission as $key) {
            DB::table('role_has_permissions')->insert([
                'role_id' => 1,
                'permission_id' => $key['permission_id']
            ]);
        }
    }
}
