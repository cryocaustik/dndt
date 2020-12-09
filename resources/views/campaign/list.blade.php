@extends('layouts.grids.12')

@section('title')
    Campaigns
@endsection

@section('page_header')
    Campaigns
@endsection
@section('page_description')
    View and Edit Campaigns

@endsection

@include('includes.datatables')

@section('full')
    <div class="card">
        <div class="card-header">
            Campaigns
            <a href="{{ route('campaign.create') }}" class="btn btn-success btn-sm float-right">
                <i class="fas fa-plus"></i>
                Add
            </a>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered dataTable" id="campaigns-table"></table>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function(){
            $('#campaigns-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('campaign.list') }}",
                    type: 'GET'
                },
                columns: [
                    { data: 'id', name: 'id', title: 'ID'},
                    { data: 'name', name: 'name', title: 'Name'},
                    { data: 'dm', name: 'dm', title: 'Dungeon Master' },
                    { data: 'owner', name: 'owner', title: 'Campaign Owner' },
                    { data: 'active', name: 'active', title: 'Is Active?'},
                    { data: 'actions', name: 'actions', title: ''}
                ]
            })
        });
        $('body').on('click', '#delete-campaign', function(){
            let campaign_id = $(this).data("id");
            if(confirm("Are You sure want to delete? This will delete all permissions, inventories, etc. associated with the campaign!"))
            {
                $.ajax({
                    type: 'POST',
                    url: `{{ route('campaign.delete', '') }}/${campaign_id}`,
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        var oTable = $('#campaigns-table').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });

    </script>
@endpush

@include('campaignpermission.includes.invite')
