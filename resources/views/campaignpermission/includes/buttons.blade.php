<div class="btn-group btn-group-sm">
    <a href="{{ route('campaign.permission.edit', $permission->id) }}" class="btn btn-warning">
        <i class="far fa-edit"></i>
    </a>
    <a href="javascript:void(0)" data-toggle="tooltip"  data-id="{{ $permission->id }}" data-original-title="Delete"
       class="btn btn-danger" id="delete-permission">
        <i class="far fa-trash-alt"></i>
    </a>
</div>
