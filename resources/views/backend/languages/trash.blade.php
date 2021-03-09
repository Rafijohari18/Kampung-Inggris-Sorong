<div class="card-header with-elements">
    <h5 class="card-header-title mt-1 mb-0">Language Trash List</h5>
    <div class="card-header-elements ml-auto">
        <a href="{{ route('language.index') }}" class="btn btn-secondary icon-btn-only-sm" title="back list language">
            <i class="las la-arrow-left"></i> <span>Back</span>
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
                <th style="width: 215px;">Deleted</th>
                <th style="width: 115px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @if ($data['languages']->total() == 0)
                <tr>
                    <td colspan="5" align="center">
                        <i>
                            <strong style="color:red;">
                            @if (Request::get('l') || Request::get('s') || Request::get('q'))
                            ! Language Trash not found :( !
                            @else
                            ! Language Trash not record !
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
                    <td>{{ $item->deleted_at->format('d F Y (H:i A)') }}</td>
                    <td>
                        <button type="button" class="btn btn-success icon-btn btn-sm restore" onclick="$(this).find('#form-restore').submit();" title="restore language" data-id="{{ $item->id }}">
                            <i class="las la-trash-restore-alt"></i>
                            <form action="{{ route('language.restore', ['id' => $item->id])}}" method="POST" id="form-restore-{{ $item->id }}">
                                @csrf
                                @method('PUT')
                            </form>
                        </button>
                        <button type="button" class="btn btn-danger icon-btn btn-sm permanent-delete" data-id="{{ $item->id }}" title="delete permanant">
                            <i class="las la-ban"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
