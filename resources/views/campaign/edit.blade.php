@extends('layouts.grids.12')

@section('title', 'Edit Campaign')

@section('page_header', 'Edit Campaign')

@section('full')
    <div class="card card-primary">
        <div class="card-header">Edit {{ $campaign->name }}</div>
        <form action="{{ route('campaign.update') }}" method="post">
            <div class="card-body">
                @csrf
                <input name="id" type="hidden" class="form-control" value="{{ $campaign->id }}">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input name="name" type="text" class="form-control" value="{{ $campaign->name }}">
                </div>
                <div class="form-group">
                    <label for="dm">Dungeon Master</label>
                    <input name="dm" type="text" class="form-control" value="{{ $campaign->dm }}">
                </div>
                <div class="form-group">
                    <label for="owner">Owner</label>
                    <input name="owner" type="text" class="form-control" value="{{ $campaign->owner->username }}">
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="active" name="active"
                            {{ $campaign->active ? 'checked' : '' }}
                        >
                        <label class="custom-control-label" for="active">Is Active?</label>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary btn-block" type="submit">Update</button>
                <hr>
                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-block">Cancel</a>
                <a href="javascript:void(0)" data-toggle="tooltip" data-id="{{ $campaign->id }}"
                   data-original-title="Delete" class="btn btn-danger btn-block">Delete</a>
            </div>
        </form>
    </div>
    <pre><code>{{ $campaign }}</code></pre>
@endsection
