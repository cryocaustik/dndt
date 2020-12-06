@extends('layouts.grids.12')

@section('title')
    Campaigns
@endsection

@section('full')
    Campaigns
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>DM</th>
                <th>Owner</th>
                <th>Active</th>
            </tr>
        </thead>
        <tbody>
            @foreach($campaigns as $campaign)
                <tr>
                    <td>{{ $campaign->id }}</td>
                    <td>{{ $campaign->name }}</td>
                    <td>{{ $campaign->dm }}</td>
                    <td>{{ $campaign->owner()->value('name', 'username') }}</td>
                    <th>{{ $campaign->active }}</th>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
