@extends('layouts.grids.12')

@section('title', 'Create New Campaign')

@section('page_header', 'Create New Campaign')

@section('full')
    <div class="card card-primary">
        <div class="card-header">New Campaign</div>
        <form action="{{ route('campaign.store') }}" method="post">
            <div class="card-body">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input name="name" type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label for="dm">Dungeon Master</label>
                    <input name="dm" type="text" class="form-control" placeholder="optional">
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="active" name="active" checked>
                        <label class="custom-control-label" for="active">Is Active?</label>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary btn-block" type="submit">Create</button>
                <hr>
                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-block">Cancel</a>
            </div>
        </form>
    </div>
@endsection
