<div class="btn-group btn-group-sm">
{{--    @can('update', $permission)--}}
        <a href="
{{--            {{ route('campaign.permission.edit', $permission->id) }}--}}
            " class="btn btn-warning">
            <i class="far fa-edit"></i>
        </a>
{{--    @endcan--}}
{{--    @can('delete', $permission)--}}
        <a href="javascript:void(0)" data-toggle="tooltip"  data-id="{{ $inventory->id }}" data-original-title="Delete"
           class="btn btn-danger" id="delete-permission">
            <i class="far fa-trash-alt"></i>
        </a>
{{--    @endcan--}}
</div>
