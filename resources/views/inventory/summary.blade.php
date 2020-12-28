@extends('layouts.grids.12')

@section('title', 'Inventory Summary')2

@section('page_header', 'Inventory Summary')

@include('includes.datatables')
@include('includes.vuejs')

@section('full')
    <div id="inventory-app">
        <div class="card">
            <div class="card-header">
                Summary
            </div>
            <div class="card-body">
                <textarea name="" id="" cols="30" rows="10" v-model="inventory"></textarea>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Summary
            <div class="card-tools">
                <select name="campaign" id="campaign-select" class="form-control selectpicker">
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}" data-tokens="{{ $campaign->name }}" >
                            {{ $campaign->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="dataTables_wrapper dt-bootstrap4">
                <table class="table table-hover dataTable" id="summary-table"></table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        const InventoryApp = new Vue({
            el: '#inventory-app',
            data: {
                endpoint: `{{ route('inventory.summary', '') }}/`,
                inventory: [],
                campaigns: [],
            },
            methods: {
                getInventory: function(campaign_id){
                    let inventory = $.ajax({
                        method: 'get',
                        url: `${this.endpoint}${campaign_id}`,
                        success: function(data) {
                            this.inventory = data
                        },
                        error: function(err){
                            console.log(`failed getting inventory`)
                        }
                    })
                }
            },
            mounted(){
                this.getInventory(1);
            }
        });
        // Vue.createApp(InventoryApp).mount('#inventory-app');
    </script>
    <script>
        $(document).ready(function(){
            let campaign_id = $('#campaign_id').val();
            $('#summary-table').DataTable({
                processing: true,
                serverSide: true,
                // responsive: true,
                ajax: {
                    url: `{{ route('inventory.summary', '') }}/${campaign_id}`,
                    type: 'GET'
                },
                columns: [
                    { data: 'id', name: 'id', title: 'ID'},
                    { data: 'item', name: 'item', title: 'Item'},
                    { data: 'description', name: 'description', title: 'Description'},
                    { data: 'quantity', name: 'quantity', title: 'Quantity'},
                    { data: 'acquired_at', name: 'acquired_at', title: 'Acquired At'},
                    { data: 'held_by', name: 'held_by', title: 'Held By'},
                    { data: 'claimed', name: 'claimed', title: 'Claimed?'},
                    { data: 'claimed_by', name: 'claimed_by', title: 'Claimed By'},
                    { data: 'in_bag_of_holding', name: 'in_bag_of_holding', title: 'In Bag of Holding?'}
                    // { data: 'actions', name: 'actions', title: ''}
                ]
            })
        });
    </script>
@endpush
