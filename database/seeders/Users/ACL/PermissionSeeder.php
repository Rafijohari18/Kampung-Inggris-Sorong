<?php

namespace Database\Seeders\Users\ACL;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            0 => [
                'parent' => 0,
                'name' => 'users'
            ],
            1 => [
                'parent' => 1,
                'name' => 'user_create'
            ],
            2 => [
                'parent' => 1,
                'name' => 'user_edit'
            ],
            3 => [
                'parent' => 1,
                'name' => 'user_delete'
            ],
            4 => [
                'parent' => 0,
                'name' => 'configurations'
            ],
            5 => [
                'parent' => 0,
                'name' => 'commons'
            ],
            6 => [
                'parent' => 0,
                'name' => 'filemanager'
            ],
            7 => [
                'parent' => 0,
                'name' => 'visitor'
            ],
            8 => [
                'parent' => 0,
                'name' => 'tags'
            ],
            9 => [
                'parent' => 9,
                'name' => 'tag_create'
            ],
            10 => [
                'parent' => 9,
                'name' => 'tag_edit'
            ],
            11 => [
                'parent' => 9,
                'name' => 'tag_delete'
            ],
            12 => [
                'parent' => 0,
                'name' => 'comments'
            ],
            13 => [
                'parent' => 13,
                'name' => 'comment_create'
            ],
            14 => [
                'parent' => 13,
                'name' => 'comment_edit'
            ],
            15 => [
                'parent' => 13,
                'name' => 'comment_delete'
            ],
            16 => [
                'parent' => 0,
                'name' => 'fields'
            ],
            17 => [
                'parent' => 0,
                'name' => 'medias'
            ],
            18 => [
                'parent' => 0,
                'name' => 'pages'
            ],
            19 => [
                'parent' => 19,
                'name' => 'page_create'
            ],
            20 => [
                'parent' => 19,
                'name' => 'page_edit'
            ],
            21 => [
                'parent' => 19,
                'name' => 'page_delete'
            ],
            22 => [
                'parent' => 0,
                'name' => 'content_sections'
            ],
            23 => [
                'parent' => 23,
                'name' => 'content_section_create'
            ],
            24 => [
                'parent' => 23,
                'name' => 'content_section_edit'
            ],
            25 => [
                'parent' => 23,
                'name' => 'content_section_delete'
            ],
            26 => [
                'parent' => 0,
                'name' => 'content_categories'
            ],
            27 => [
                'parent' => 27,
                'name' => 'content_category_create'
            ],
            28 => [
                'parent' => 27,
                'name' => 'content_category_edit'
            ],
            29 => [
                'parent' => 27,
                'name' => 'content_category_delete'
            ],
            30 => [
                'parent' => 0,
                'name' => 'content_posts'
            ],
            31 => [
                'parent' => 31,
                'name' => 'content_post_create'
            ],
            32 => [
                'parent' => 31,
                'name' => 'content_post_edit'
            ],
            33 => [
                'parent' => 31,
                'name' => 'content_post_delete'
            ],
            34 => [
                'parent' => 0,
                'name' => 'banner_categories'
            ],
            35 => [
                'parent' => 35,
                'name' => 'banner_category_create'
            ],
            36 => [
                'parent' => 35,
                'name' => 'banner_category_edit'
            ],
            37 => [
                'parent' => 35,
                'name' => 'banner_category_delete'
            ],
            38 => [
                'parent' => 0,
                'name' => 'banners'
            ],
            39 => [
                'parent' => 39,
                'name' => 'banner_create'
            ],
            40 => [
                'parent' => 39,
                'name' => 'banner_edit'
            ],
            41 => [
                'parent' => 39,
                'name' => 'banner_delete'
            ],
            42 => [
                'parent' => 0,
                'name' => 'catalog_categories'
            ],
            43 => [
                'parent' => 43,
                'name' => 'catalog_category_create'
            ],
            44 => [
                'parent' => 43,
                'name' => 'catalog_category_edit'
            ],
            45 => [
                'parent' => 43,
                'name' => 'catalog_category_delete'
            ],
            46 => [
                'parent' => 0,
                'name' => 'catalog_products'
            ],
            47 => [
                'parent' => 47,
                'name' => 'catalog_product_create'
            ],
            48 => [
                'parent' => 47,
                'name' => 'catalog_product_edit'
            ],
            49 => [
                'parent' => 47,
                'name' => 'catalog_product_delete'
            ],
            50 => [
                'parent' => 47,
                'name' => 'catalog_product_image'
            ],
            51 => [
                'parent' => 0,
                'name' => 'albums'
            ],
            52 => [
                'parent' => 52,
                'name' => 'album_create'
            ],
            53 => [
                'parent' => 52,
                'name' => 'album_edit'
            ],
            54 => [
                'parent' => 52,
                'name' => 'album_delete'
            ],
            55 => [
                'parent' => 52,
                'name' => 'album_photo'
            ],
            56 => [
                'parent' => 0,
                'name' => 'playlists'
            ],
            57 => [
                'parent' => 57,
                'name' => 'playlist_create'
            ],
            58 => [
                'parent' => 57,
                'name' => 'playlist_edit'
            ],
            59 => [
                'parent' => 57,
                'name' => 'playlist_delete'
            ],
            60 => [
                'parent' => 57,
                'name' => 'playlist_video'
            ],
            61 => [
                'parent' => 0,
                'name' => 'links'
            ],
            62 => [
                'parent' => 62,
                'name' => 'link_create'
            ],
            63 => [
                'parent' => 62,
                'name' => 'link_edit'
            ],
            64 => [
                'parent' => 62,
                'name' => 'link_delete'
            ],
            65 => [
                'parent' => 62,
                'name' => 'link_media'
            ],
            66 => [
                'parent' => 0,
                'name' => 'inquiries'
            ],
            67 => [
                'parent' => 67,
                'name' => 'inquiry_create'
            ],
            68 => [
                'parent' => 67,
                'name' => 'inquiry_edit'
            ],
            69 => [
                'parent' => 67,
                'name' => 'inquiry_delete'
            ],
            70 => [
                'parent' => 67,
                'name' => 'inquiry_detail'
            ],
            71 => [
                'parent' => 19,
                'name' => 'page_child'
            ],
        ];

        foreach ($permissions as $key) {
            Permission::create([
                'parent' => $key['parent'],
                'name' => $key['name'],
                'guard_name' => 'web',
                'default_data' => 1,
            ]);
        }
    }
}
