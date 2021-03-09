<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($data['pages'] as $page)
<sitemap>
    <loc>{{ $page->routes($page->id, $page->slug) }}</loc>
    <lastmod>{{ $page->updated_at }}</lastmod>
</sitemap>
@endforeach
<sitemap>
    <loc>{{ config('custom.language.multiple') == true ? route('page.list', ['locale' => app()->getLocale()]) : route('page.list') }}</loc>
</sitemap>
@foreach ($data['sections'] as $section)
<sitemap>
    <loc>{{ $section->routes($section->id, $section->slug) }}</loc>
    <lastmod>{{ $section->updated_at }}</lastmod>
</sitemap>
@endforeach
<sitemap>
    <loc>{{ config('custom.language.multiple') == true ? route('section.list', ['locale' => app()->getLocale()]) : route('section.list') }}</loc>
</sitemap>
@foreach ($data['categories'] as $category)
<sitemap>
    <loc>{{ $category->routes($category->id, $category->slug) }}</loc>
    <lastmod>{{ $category->updated_at }}</lastmod>
</sitemap>
@endforeach
<sitemap>
    <loc>{{ config('custom.language.multiple') == true ? route('category.list', ['locale' => app()->getLocale()]) : route('category.list') }}</loc>
</sitemap>
@foreach ($data['posts'] as $post)
<sitemap>
    <loc>{{ $post->routes($post->id, $post->slug) }}</loc>
    <lastmod>{{ $post->updated_at }}</lastmod>
</sitemap>
@endforeach
<sitemap>
    <loc>{{ config('custom.language.multiple') == true ? route('post.list', ['locale' => app()->getLocale()]) : route('post.list') }}</loc>
</sitemap>
<sitemap>
    <loc>{{ config('custom.language.multiple') == true ? route('catalog.view', ['locale' => app()->getLocale()]) : route('catalog.view') }}</loc>
</sitemap>
@foreach ($data['catalog_categories'] as $catCategory)
<sitemap>
    <loc>{{ $catCategory->routes($catCategory->id, $catCategory->slug) }}</loc>
    <lastmod>{{ $catCategory->updated_at }}</lastmod>
</sitemap>
@endforeach
<sitemap>
    <loc>{{ config('custom.language.multiple') == true ? route('catalog.category.list', ['locale' => app()->getLocale()]) : route('catalog.category.list') }}</loc>
</sitemap>
@foreach ($data['catalog_products'] as $catProduct)
<sitemap>
    <loc>{{ $catProduct->routes($catProduct->id, $catProduct->slug) }}</loc>
    <lastmod>{{ $catProduct->updated_at }}</lastmod>
</sitemap>
@endforeach
<sitemap>
    <loc>{{ config('custom.language.multiple') == true ? route('catalog.product.list', ['locale' => app()->getLocale()]) : route('catalog.product.list') }}</loc>
</sitemap>
@foreach ($data['albums'] as $album)
<sitemap>
    <loc>{{ $album->routes($album->id, $album->slug) }}</loc>
    <lastmod>{{ $album->updated_at }}</lastmod>
</sitemap>
@endforeach
<sitemap>
    <loc>{{ config('custom.language.multiple') == true ? route('album.list', ['locale' => app()->getLocale()]) : route('album.list') }}</loc>
</sitemap>
@foreach ($data['playlists'] as $playlist)
<sitemap>
    <loc>{{ $playlist->routes($playlist->id, $playlist->slug) }}</loc>
    <lastmod>{{ $playlist->updated_at }}</lastmod>
</sitemap>
@endforeach
<sitemap>
    <loc>{{ config('custom.language.multiple') == true ? route('playlist.list', ['locale' => app()->getLocale()]) : route('playlist.list') }}</loc>
</sitemap>
@foreach ($data['links'] as $link)
<sitemap>
    <loc>{{ $link->routes($link->id, $link->slug) }}</loc>
    <lastmod>{{ $link->updated_at }}</lastmod>
</sitemap>
@endforeach
<sitemap>
    <loc>{{ config('custom.language.multiple') == true ? route('link.list', ['locale' => app()->getLocale()]) : route('link.list') }}</loc>
</sitemap>
@foreach ($data['inquiries'] as $inquiry)
<sitemap>
    <loc>{{ $inquiry->routes($inquiry->slug) }}</loc>
    <lastmod>{{ $inquiry->updated_at }}</lastmod>
</sitemap>
@endforeach
<sitemap>
    <loc>{{ config('custom.language.multiple') == true ? route('inquiry.list', ['locale' => app()->getLocale()]) : route('inquiry.list') }}</loc>
</sitemap>
</sitemapindex>

