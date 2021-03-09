<div class="card-header with-elements">
    <h5 class="card-header-title mt-1 mb-0">User List</h5>
    <div class="card-header-elements ml-auto">
        <a href="{{ route('user.index', ['is_trash' => 'yes']) }}" class="btn btn-secondary icon-btn-only-sm" title="trash user">
            <i class="las la-recycle"></i> <span>Trash</span>
        </a>
        @can('user_create')
        <a href="{{ route('user.create') }}" class="btn btn-success icon-btn-only-sm" title="add user">
            <i class="las la-plus"></i> <span>User</span>
        </a>
        @endcan
    </div>
</div>

<div class="table-responsive">
    <table id="user-list" class="table card-table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th style="width: 10px;">No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Roles</th>
                <th class="text-center">Status</th>
                <th style="width: 215px;">Last Activity</th>
                <th style="width: 215px;">Created</th>
                <th style="width: 215px;">Updated</th>
                <th style="width: 140px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @if ($data['users']->total() == 0)
                <tr>
                    <td colspan="10" align="center">
                        <i>
                            <strong style="color:red;">
                            @if (Request::get('l') || Request::get('r') || Request::get('a') || Request::get('q'))
                            ! User not found :( !
                            @else
                            ! User not record !
                            @endif
                            </strong>
                        </i>
                    </td>
                </tr>
            @endif
            @foreach ($data['users'] as $item)
                <tr>
                    <td>{{ $data['no']++ }} </td>
                    <td>{{  $item->name  }}</td>
                    <td>
                        <a href="mailto:{{ $item->email }}">{{ $item->email }}</a>
                        <i class="las la-{{ config('custom.label.email_verified.'.$item->email_verified)['icon'] }} text-{{ config('custom.label.email_verified.'.$item->email_verified)['color'] }} ml-1" title="{{ config('custom.label.email_verified.'.$item->email_verified)['title'] }}"></i>
                    </td>
                    <td>{{ $item->username }}</td>
                    <td><span class="badge badge-primary">{{ strtoupper($item->roles[0]->name) }}</span></td>
                    <td class="text-center">
                        @if (auth()->user()->can('user_edit') && $item->roles[0]->id >= auth()->user()->roles[0]->id && ($item->id != auth()->user()->id))
                        <a href="javascript:void(0);" onclick="$(this).find('form').submit();" class="badge badge-{{ config('custom.label.active.'.$item->active)['color'] }}"
                            title="Click to activate user">
                            {{ config('custom.label.active.'.$item->active)['title'] }}
                            <form action="{{ route('user.activate', ['id' => $item->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                            </form>
                        </a>
                        @else
                        <span class="badge badge-success">ACTIVE</span>
                        @endif
                    </td>
                    <td>{{ !empty($item->session) ? $item->session->last_activity->format('d F Y (H:i A)') : '-' }}</td>
                    <td>{{ $item->created_at->format('d F Y (H:i A)') }}</td>
                    <td>{{ $item->updated_at->format('d F Y (H:i A)') }}</td>
                    <td>
                        @if (auth()->user()->can('user_edit') && $item->roles[0]->id >= auth()->user()->roles[0]->id && ($item->id != auth()->user()->id))
                        <a href="{{ route('user.log', ['id' => $item->id]) }}" class="btn btn-warning icon-btn btn-sm" title="user logs">
                            <i class="las la-user-clock"></i>
                        </a>
                        <a href="{{ route('user.edit', ['id' => $item->id]) }}" class="btn btn-primary icon-btn btn-sm" title="edit user">
                            <i class="las la-pen"></i>
                        </a>
                        @else
                        <button type="button" class="btn btn-secondary icon-btn btn-sm" title="you are not allowed to take this action" disabled>
                            <i class="las la-user-clock"></i>
                        </button>
                        <button type="button" class="btn btn-secondary icon-btn btn-sm" title="you are not allowed to take this action" disabled>
                            <i class="las la-pen"></i>
                        </button>
                        @endif
                        @if (auth()->user()->can('user_delete') && $item->roles[0]->id >= auth()->user()->roles[0]->id && ($item->id != auth()->user()->id))
                        <button type="button" class="btn btn-danger icon-btn btn-sm modal-delete"
                            data-toggle="modal"
                            data-target="#modals-delete"
                            data-id="{{ $item->id }}"
                            data-name="{{ $item->name }}" title="delete user">
                            <i class="las la-trash-alt"></i>
                        </button>
                        @else
                        <button type="button" class="btn btn-secondary icon-btn btn-sm" title="you are not allowed to take this action" disabled>
                            <i class="las la-trash-alt"></i>
                        </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
