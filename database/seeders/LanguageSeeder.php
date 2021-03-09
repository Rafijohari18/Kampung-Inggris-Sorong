<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $language = [
            0 => [
                'iso_codes' => 'id',
                'country' => 'Indonesia',
                'faker_locale' => 'id_ID',
                'time_zone' => 'Asia/Jakarta',
                'status' => 1,
            ],
            1 => [
                'iso_codes' => 'en',
                'country' => 'English',
                'faker_locale' => 'en_US',
                'time_zone' => 'UTC',
                'status' => (config('custom.language.multiple') == true) ? 1 : 0,
            ],
        ];

        foreach ($language as $key) {
            Language::create([
                'iso_codes' => $key['iso_codes'],
                'country' => $key['country'],
                'faker_locale' => $key['faker_locale'],
                'time_zone' => $key['time_zone'],
                'status' => $key['status'],
            ]);
        }
    }
}
