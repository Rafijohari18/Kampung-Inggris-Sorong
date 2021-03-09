<div id="layout-sidenav" class="{{ isset($layout_sidenav_horizontal) ? 'layout-sidenav-horizontal sidenav-horizontal container-p-x flex-grow-0' : 'layout-sidenav sidenav-vertical' }} sidenav bg-sidenav-theme">

    <!-- Brand demo (see assets/css/demo/demo.css) -->
    <div class="app-brand demo">
        <span class="app-brand-logo demo">
            <img src="{{ $config['logo'] }}" alt="{{ $config['website_name'] }} Logo">
        </span>
        <a href="javascript:void(0)" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
          <i class="las la-thumbtack"></i>
        </a>
    </div>

    <div class="sidenav-divider mt-0"></div>

    <!-- Inner -->
    <ul class="sidenav-inner{{ empty($layout_sidenav_horizontal) ? ' py-1' : '' }}">

        <!-- Dashboard -->
        <li class="sidenav-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
            <a href="{{ route('backend.dashboard') }}" class="sidenav-link" title="Dashbaord"><i class="sidenav-icon las la-tachometer-alt"></i><div>Dashboard</div></a>
        </li>

        @if (auth()->user()->can('tags') || auth()->user()->can('comments'))
        <!-- Data Master -->
        <li class="sidenav-item {{ (Request::is('admin/management/tag*') || Request::is('admin/management/comment*') || Request::is('admin/managament/template*')) ? 'open active' : '' }}">
            <a href="javascript:void(0)" class="sidenav-link sidenav-toggle" title="Data Master"><i class="sidenav-icon ion las la-database"></i>
              <div>Data Master</div>
            </a>
            <ul class="sidenav-menu">
              @can('tags')
              <li class="sidenav-item {{ Request::is('admin/management/tag*') ? 'active' : '' }}">
                <a href="{{ route('tag.index') }}" class="sidenav-link" title="Tags">
                  <div>Tags</div>
                </a>
              </li>
              @endcan
              @can('comments')
              <li class="sidenav-item {{ Request::is('admin/management/comment*') ? 'active' : '' }}">
                <a href="{{ route('comment.index') }}" class="sidenav-link" title="Comment">
                  <div>Comment</div>
                </a>
              </li>
              @endcan
              @role('super')
              <li class="sidenav-item {{ Request::is('admin/managament/template*') ? 'active' : '' }}">
                <a href="{{ route('template.index') }}" class="sidenav-link" title="Template View">
                  <div>Template View</div>
                </a>
              </li>
              @endrole
            </ul>
        </li>
        @endif

        @if (auth()->user()->can('pages') || auth()->user()->can('sections') || auth()->user()->can('banner_categories') || auth()->user()->can('catalog_categories')
            || auth()->user()->can('albums') || auth()->user()->can('playlists') || auth()->user()->can('links') || auth()->user()->can('inquiries'))
        <!-- Module -->
        <li class="sidenav-divider mb-1"></li>
        <li class="sidenav-header small font-weight-semibold">MODULE</li>
        @endif

        @can('pages')
        <!-- Pages -->
        <li class="sidenav-item {{ Request::is('admin/management/page*') ? 'active' : '' }}">
            <a href="{{ route('page.index') }}" class="sidenav-link" title="Pages"><i class="sidenav-icon las la-bars"></i><div>Pages</div></a>
        </li>
        @endcan
        @can('content_sections')
        <!-- Content -->
        <li class="sidenav-item {{ Request::is('admin/management/section*') ? 'active' : '' }}">
            <a href="{{ route('section.index') }}" class="sidenav-link" title="Section"><i class="sidenav-icon las la-pen-square"></i><div>Content</div></a>
        </li>
        @endcan
        @can('banner_categories')
        <!-- banner -->
        <li class="sidenav-item {{ Request::is('admin/management/banner*') ? 'active' : '' }}">
            <a href="{{ route('banner.category.index') }}" class="sidenav-link" title="Banner"><i class="sidenav-icon las la-images"></i><div>Banner</div></a>
        </li>
        @endcan
        @can('catalog_categories')
        <!-- catalogue -->
        <li class="sidenav-item {{ Request::is('admin/management/catalog*') ? 'active' : '' }}">
            <a href="{{ route('catalog.category.index') }}" class="sidenav-link" title="Catalog"><i class="sidenav-icon las la-store-alt"></i><div>Catalogue</div></a>
        </li>
        @endcan
        @if (auth()->user()->can('albums') || auth()->user()->can('playlist'))
        <!-- gallery -->
        <li class="sidenav-item {{ (Request::is('admin/management/gallery*')) ? 'open active' : '' }}">
            <a href="javascript:void(0)" class="sidenav-link sidenav-toggle" title="Gallery"><i class="sidenav-icon las la-photo-video"></i>
              <div>Gallery</div>
            </a>

            <ul class="sidenav-menu">
                @can('albums')
                <li class="sidenav-item {{ Request::is('admin/management/gallery/album*') ? 'active' : '' }}">
                    <a href="{{ route('gallery.album.index') }}" class="sidenav-link" title="Photo">
                        <div>Photo</div>
                    </a>
                </li>
                @endcan
                @can('playlists')
                <li class="sidenav-item {{ Request::is('admin/management/gallery/playlist*') ? 'active' : '' }}">
                    <a href="{{ route('gallery.playlist.index') }}" class="sidenav-link" title="Video">
                        <div>Video</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endif
        @can('links')
        <!-- links -->
        <li class="sidenav-item {{ Request::is('admin/management/link*') ? 'active' : '' }}">
            <a href="{{ route('link.index') }}" class="sidenav-link" title="Links"><i class="sidenav-icon las la-link"></i><div>Links</div></a>
        </li>
        @endcan
        @can('inquiries')
        <!-- links -->
        <li class="sidenav-item {{ Request::is('admin/management/inquiry*') ? 'active' : '' }}">
            <a href="{{ route('inquiry.index') }}" class="sidenav-link" title="Inquiry"><i class="sidenav-icon las la-envelope"></i>
                <div>Inquiry</div>
                @php
                    $newMessage = \App\Models\Inquiry\InquiryMessage::where('status', 0)->count();
                @endphp
                @if ($newMessage > 0)
                <div class="pl-1 ml-auto">
                    <div class="badge badge-danger">{{ $newMessage }}</div>
                </div>
                @endif
            </a>
        </li>
        @endcan

        @if (auth()->user()->can('visitor') || auth()->user()->can('filemanager'))
        <!-- Setting -->
        <li class="sidenav-divider mb-1"></li>
        <li class="sidenav-header small font-weight-semibold">EXTRA</li>
        @endif

        @can('visitor')
        @if (!empty(env('ANALYTICS_VIEW_ID')))
        <!-- Visitor -->
        <li class="sidenav-item {{ Request::is('admin/visitor*') ? 'active' : '' }}">
            <a href="{{ route('visitor') }}" class="sidenav-link" title="Visitor"><i class="sidenav-icon las la-user-plus"></i><div>Visitor</div></a>
        </li>
        @endif
        @endcan
        @can('filemanager')
        <!-- Filemanager -->
        <li class="sidenav-item {{ Request::is('admin/filemanager*') ? 'active' : '' }}">
            <a href="{{ route('filemanager') }}" class="sidenav-link" title="File Manager"><i class="sidenav-icon las la-folder"></i><div>File Manager</div></a>
        </li>
        @endcan

        @if (auth()->user()->can('users') || auth()->user()->can('configurations') || auth()->user()->can('commons'))
        <!-- Setting -->
        <li class="sidenav-divider mb-1"></li>
        <li class="sidenav-header small font-weight-semibold">SETTING</li>
        @endif

        @can('users')
        <!-- Management user -->
        <li class="sidenav-item {{ (Request::is('admin/management/user*') || Request::is('admin/management/role*') || Request::is('admin/management/permission*')) ? 'open active' : '' }}">
            <a href="javascript:void(0)" class="sidenav-link sidenav-toggle" title="Management Users"><i class="sidenav-icon las la-users"></i>
              <div>Management Users</div>
            </a>

            <ul class="sidenav-menu">
              @role ('super')
              <li class="sidenav-item {{ (Request::is('admin/management/role*') || Request::is('admin/management/permission*')) ? 'open active' : '' }}">
                  <a href="javascript:void(0)" class="sidenav-link sidenav-toggle" title="Access Control List">
                    <div>Access Control List</div>
                  </a>

                  <ul class="sidenav-menu">
                      <!-- Internal -->
                      <li class="sidenav-item {{ Request::is('admin/management/role*') ? 'active' : '' }}">
                          <a href="{{ route('role.index') }}" class="sidenav-link" title="Roles">
                            <div>Roles</div>
                          </a>
                      </li>
                      <!-- Mitra -->
                      <li class="sidenav-item {{ Request::is('admin/management/permission*') ? 'active' : '' }}">
                          <a href="{{ route('permission.index') }}" class="sidenav-link" title="Permission">
                            <div>Permission</div>
                          </a>
                      </li>
                  </ul>
              </li>
              @endrole
              @can('users')
              <li class="sidenav-item {{ Request::is('admin/management/user*') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}" class="sidenav-link" title="Users">
                  <div>Users</div>
                </a>
              </li>
              @endcan
            </ul>
        </li>
        @endcan

        @if (auth()->user()->can('configurations') || auth()->user()->can('commons'))
        <!-- Configuration -->
        <li class="sidenav-item {{ (Request::is('admin/configuration*') && Request::segment(3) != 'maintenance') ? 'open active' : '' }}">
            <a href="javascript:void(0)" class="sidenav-link sidenav-toggle" title="Configuration"><i class="sidenav-icon las la-cogs"></i>
              <div>Configurations</div>
            </a>

            <ul class="sidenav-menu">
                @can('configurations')
                <li class="sidenav-item {{ Request::is('admin/configuration/web*') ? 'active' : '' }}">
                    <a href="{{ route('configuration.web') }}" class="sidenav-link" title="Web Config">
                        <div>Web Config</div>
                    </a>
                </li>
                @endcan
                @can('commons')
                <li class="sidenav-item {{ Request::is('admin/configuration/common*') ? 'active' : '' }}">
                    <a href="{{ route('configuration.common', ['lang' => app()->getLocale()]) }}" class="sidenav-link" title="Common">
                        <div>Common</div>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endif

        @role ('super')
        <!-- Language -->
        <li class="sidenav-item {{ Request::is('admin/management/language*') ? 'active' : '' }}">
            <a href="{{ route('language.index') }}" class="sidenav-link" title="Language"><i class="sidenav-icon las la-language"></i><div>Language</div></a>
        </li>
        <!-- Maintenance -->
        <li class="sidenav-item {{ Request::is('admin/configuration/maintenance*') ? 'active' : '' }}">
            <a href="{{ route('configuration.maintenance') }}" class="sidenav-link" title="Miantenance"><i class="sidenav-icon las la-tools"></i><div>Maintenance Mode</div></a>
        </li>
        @endrole
    </ul>
</div>
