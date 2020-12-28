<div class="btn-group btn-group-sm">
    @can('invite', $campaign)
    <div class="btn btn-success" id="invite-btn" data-id="{{ $campaign->id }}"
         onclick="inviteModal($(this).data('id'))">
        <i class="fas fa-user-plus"></i>
    </div>
    @endcan
    @can('update', $campaign)
    <a href="{{ route('campaign.edit', $campaign->id) }}" data-toggle="tooltip" data-original-title="Edit"
       class="edit btn btn-warning btn-sm">
        <i class="far fa-edit"></i>
    </a>
    @endcan
    @can('delete', $campaign)
    <a href="javascript:void(0)" data-toggle="tooltip"  data-id="{{ $campaign->id }}" data-original-title="Delete"
       class="btn btn-danger btn-sm" id="delete-campaign">
        <i class="far fa-trash-alt"></i>
    </a>
    @endcan
</div>
