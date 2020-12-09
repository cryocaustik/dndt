@extends('layouts.grids.12')

@section('title', 'Permissions')

@section('page_header', 'Campaign Permissions')

@include('includes.datatables')

@section('full')
    <div class="card">
        <div class="card-header">
            Campaign Permissions
            <div class="card-tools">
                <a href="{{ route('campaign.permission.invite') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-user-plus"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered dataTable" id="permissions-table"></table>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function(){
            $('#permissions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('campaign.permission.list') }}",
                    type: 'GET'
                },
                columns: [
                    { data: 'id', name: 'id', title: 'ID'},
                    { data: 'campaign_id', name: 'campaign_id', title: 'campaign_id'},
                    { data: 'campaign_name', name: 'campaign_name', title: 'campaign_name'},
                    { data: 'user_id', name: 'user_id', title: 'user_id' },
                    { data: 'username', name: 'username', title: 'username' },
                    { data: 'permission', name: 'permission', title: 'Permission' },
                    { data: 'actions', name: 'actions', title: ''}
                ]
            })
        });
    </script>
@endpush


