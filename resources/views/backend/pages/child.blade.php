@foreach ($childs as $child)
<tr>
    <td><i class="lab la-slack"></i></td>
    <td><i>{!! str_repeat('<i class="las la-minus"></i>', $level) !!} {!! Str::limit($child->fieldLang('title'), 60) !!}</i> <a href="{{ $child->routes($child->id, $child->slug) }}" target="_blank"><i class="las la-external-link-alt" style="font-size: 1.5em"></i></a></td>
    <td class="text-center"><span class="badge badge-info">{{ $child->viewer }}</span></td>
    <td class="text-center">
        @can ('page_child')
        <a href="javascript:void(0);" onclick="$(this).find('form').submit();" class="badge badge-{{ config('custom.label.publish.'.$child->publish)['color'] }}"
            title="Click to change status page">
            {{ config('custom.label.publish.'.$child->publish)['title'] }}
            <form action="{{ route('page.publish', ['id' => $child->id]) }}" method="POST">
                @csrf
                @method('PUT')
            </form>
        </a>
        @else
        <span class="badge badge-{{ config('custom.label.publish.'.$child->publish)['color'] }}">{{ config('custom.label.publish.'.$child->publish)['title'] }}</span>
        @endcan
    </td>
    <td class="text-center">
        <span class="badge badge-{{ config('custom.label.true_false.'.$child->public)['color'] }}">{{ config('custom.label.true_false.'.$child->public)['title'] }}</span>
    </td>
    <td>
        <span class="text-muted">by {{ $child->createBy->name }}</span><br>
        {{ $child->created_at->format('d F Y (H:i A)') }}
    </td>
    <td>
        <span class="text-muted">by {{  $child->updateBy->name }}</span><br>
        {{ $child->updated_at->format('d F Y (H:i A)') }}
    </td>
    <td>
        @if (auth()->user()->can('page_child') && $child->where('parent', $child->parent)->min('position') != $child->position)
        <a href="javascript:void(0);" onclick="$(this).find('form').submit();" class="btn icon-btn btn-sm btn-dark" data-toggle="tooltip" data-original-title="Click to up position">
            <i class="las la-arrow-up"></i>
            <form action="{{ route('page.position', ['id' => $child->id, 'position' => ($child->position - 1), 'parent' => $child->parent]) }}" method="POST">
                @csrf
                @method('PUT')
            </form>
        </a>
        @else
        <button type="button" class="btn icon-btn btn-sm btn-default" title="you are not allowed to take this action" disabled><i class="las la-arrow-up"></i></button>
        @endif
        @if (auth()->user()->can('page_child') && $child->where('parent', $child->parent)->max('position') != $child->position)
        <a href="javascript:void(0);" onclick="$(this).find('form').submit();" class="btn icon-btn btn-sm btn-dark" data-toggle="tooltip" data-original-title="Click to down position">
            <i class="las la-arrow-down"></i>
            <form action="{{ route('page.position', ['id' => $child->id, 'position' => ($child->position + 1), 'parent' => $child->parent]) }}" method="POST">
                @csrf
                @method('PUT')
            </form>
        </a>
        @else
        <button type="button" class="btn icon-btn btn-sm btn-default" title="you are not allowed to take this action" disabled><i class="las la-arrow-down"></i></button>
        @endif
    </td>
    <td>
        @can('fields')
        <a href="{{ route('field.form', ['id' => $child->id, 'module' => 'page']) }}" class="btn icon-btn btn-sm btn-secondary" title="setting addon field">
            <i class="las la-cog"></i>
        </a>
        @else
        <button class="btn icon-btn btn-sm btn-secondary" title="you are not allowed to take this action" disabled><i class="las la-cog"></i></button>
        @endcan
        @can('page_child')
        <a href="{{ route('page.create', ['parent' => $child->id]) }}" class="btn icon-btn btn-sm btn-success" title="add child page">
            <i class="las la-plus"></i>
        </a>
        @else
        <button class="btn icon-btn btn-sm btn-secondary" title="you are not allowed to take this action" disabled><i class="las la-plus"></i></button>
        @endcan
        @can('medias')
        <a href="{{ route('media.index', ['id' => $child->id, 'module' => 'page']) }}" class="btn icon-btn btn-sm btn-info" title="view media">
            <i class="las la-folder"></i>
        </a>
        @else
        <button class="btn icon-btn btn-sm btn-secondary" title="you are not allowed to take this action" disabled><i class="las la-folder"></i></button>
        @endcan
        @can('page_child')
        <a href="{{ route('page.edit', ['id' => $child->id]) }}" class="btn icon-btn btn-sm btn-primary" title="edit page">
            <i class="las la-pen"></i>
        </a>
        @else
        <button class="btn icon-btn btn-sm btn-secondary" title="you are not allowed to take this action" disabled><i class="las la-pen"></i></button>
        @endcan
        @can('page_child')
        <a href="javascript:;" data-id="{{ $child->id }}" class="btn icon-btn btn-sm btn-danger js-delete" title="delete page">
            <i class="las la-trash"></i>
        </a>
        @else
        <button class="btn icon-btn btn-sm btn-secondary" title="you are not allowed to take this action" disabled><i class="las la-trash"></i></button>
        @endcan
    </td>
</tr>
@if (count($child->childs))
    @include('backend.pages.child', ['childs' => $child->childs, 'level' => ($level+1)])
@endif
@endforeach
