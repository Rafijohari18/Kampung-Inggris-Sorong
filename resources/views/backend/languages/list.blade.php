<div class="card-header with-elements">
    <h5 class="card-header-title mt-1 mb-0">Language List</h5>
    <div class="card-header-elements ml-auto">
        <a href="{{ route('language.index', ['is_trash' => 'yes']) }}" class="btn btn-secondary icon-btn-only-sm" title="trash language">
            <i class="las la-recycle"></i> <span>Trash</span>
        </a>
        <a href="{{ route('language.create') }}" class="btn btn-success icon-btn-only-sm" title="add language">
            <i class="las la-plus"></i> <span>Language</span>
        </a>
    </div>
</div>
<div class="table-responsive">
    <table id="user-list" class="table card-table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th style="width: 10px;">No</th>
                <th style="width: 100px;">Iso Codes</th>
                <th>Country</th>
                <th>Faker Locale</th>
                <th>Time Zone</th>
                <th>GMT</th>
                <th style="width: 150px;" class="text-center">Status</th>
                <th style="width: 215px;">Created</th>
                <th style="width: 215px;">Updated</th>
                <th style="width: 115px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @if ($data['languages']->total() == 0)
                <tr>
                    <td colspan="10" align="center">
                        <i>
                            <strong style="color:red;">
                            @if (Request::get('l') || Request::get('s') || Request::get('q'))
                            ! Language not found :( !
                            @else
                            ! Language not record !
                            @endif
                            </strong>
                        </i>
                    </td>
                </tr>
            @endif
            @foreach ($data['languages'] as $item)
                <tr>
                    <td>{{ $data['no']++ }} </td>
                    <td><span class="badge badge-primary">{{ $item->iso_codes }}</span></td>
                    <td>{{ $item->country }}</td>
                    <td><code>{{ $item->faker_locale ?? '-' }}</code></td>
                    <td>{{ $item->time_zone ?? '-' }}</td>
                    <td>{{ $item->gmt ?? '-' }}</td>
                    <td class="text-center">
                        <a href="javascript:void(0);" onclick="$(this).find('form').submit();" class="badge badge-{{ config('custom.label.active.'.$item->status)['color'] }}"
                            title="Click to change language status">
                            {{ config('custom.label.active.'.$item->status)['title'] }}
                            <form action="{{ route('language.status', ['id' => $item->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                            </form>
                        </a>
                    </td>
                    <td>{{ $item->created_at->format('d F Y (H:i A)') }}</td>
                    <td>{{ $item->updated_at->format('d F Y (H:i A)') }}</td>
                    <td>
                        <a href="{{ route('language.edit', ['id' => $item->id]) }}" class="btn btn-primary icon-btn btn-sm" title="edit language">
                            <i class="las la-pen"></i>
                        </a>
                        @if ($item->iso_codes != 'id' || $item->iso_codes != 'en')
                        <button type="button" class="btn btn-danger icon-btn btn-sm modal-delete"
                            data-toggle="modal"
                            data-target="#modals-delete"
                            data-id="{{ $item->id }}"
                            data-name="{{ $item->country }}" title="delete language">
                            <i class="las la-trash-alt"></i>
                        </button>
                        @else
                        <button type="button" class="btn btn-secondary icon-btn btn-sm" title="delete language" disabled>
                            <i class="las la-trash-alt"></i>
                        </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
