<div class="card-header with-elements">
    <h5 class="card-header-title mt-1 mb-0">User Trash List</h5>
    <div class="card-header-elements ml-auto">
        <a href="{{ route('user.index') }}" class="btn btn-secondary icon-btn-only-sm" title="back to list user">
            <i class="las la-arrow-left"></i> <span>Back</span>
        </a>
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
                <th>Status</th>
                <th style="width: 215px;">Last Activity</th>
                <th style="width: 215px;">Deleted</th>
                <th style="width: 110px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @if ($data['users']->total() == 0)
                <tr>
                    <td colspan="9" align="center">
                        <i>
                            <strong style="color:red;">
                            @if (Request::get('l') || Request::get('r') || Request::get('a') || Request::get('q'))
                            ! User trash not found :( !
                            @else
                            ! User trash not record !
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
                    <td>
                        <span class="badge badge-success">ACTIVE</span>
                    </td>
                    <td>{{ !empty($item->session) ? $item->session->last_activity->format('d F Y (H:i A)') : '-' }}</td>
                    <td>{{ $item->deleted_at->format('d F Y (H:i A)') }}</td>
                    <td>
                        @if (auth()->user()->can('user_edit') && $item->roles[0]->id >= auth()->user()->roles[0]->id && ($item->id != auth()->user()->id))
                        <button type="button" class="btn btn-success icon-btn btn-sm restore" onclick="$(this).find('#form-restore').submit();" title="restore user" data-id="{{ $item->id }}">
                            <i class="las la-trash-restore-alt"></i>
                            <form action="{{ route('user.restore', ['id' => $item->id])}}" method="POST" id="form-restore-{{ $item->id }}">
                                @csrf
                                @method('PUT')
                            </form>
                        </button>
                        @else
                        <button type="button" class="btn btn-secondary icon-btn btn-sm" title="restore user" disabled>
                            <i class="las la-trash-restore-alt"></i>
                        </button>
                        @endif
                        @if (auth()->user()->can('user_delete') && $item->roles[0]->id >= auth()->user()->roles[0]->id && ($item->id != auth()->user()->id))
                        <button type="button" class="btn btn-danger icon-btn btn-sm permanent-delete" data-id="{{ $item->id }}" title="delete permanant">
                            <i class="las la-ban"></i>
                        </button>
                        @else
                        <button type="button" class="btn btn-secondary icon-btn btn-sm" title="delete permanant" disabled>
                            <i class="las la-ban"></i>
                        </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
