@extends('layouts.grids.12')

@section('title', 'Invite')

@section('page_header', 'Create Invite Link')

@include('includes.bootstrap-select')

@section('full')
    <div class="card">
        <div class="card-header">
            New Invite Generator
        </div>
        <form action="">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="campaign-select">Campaign</label>
                    <select name="campaign-select" id="campaign-select" class="form-control selectpicker" data-live-search="true">
                        @foreach($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" data-tokens="{{ $campaign->name }}" >
                                {{ $campaign->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="expires">Expires in</label>
                    <select name="expires" id="expires" class="form-control">
                        <option value="5m">5 minutes</option>
                        <option value="30m" selected>30 minutes</option>
                        <option value="1h">1 hour</option>
                        <option value="1d">1 day</option>
                        <option value="7d">7 days</option>
                    </select>
                </div>
                <div class="form-group" hidden>
                    <label for="invite-link">Invite Link</label>
                    <input type="text" class="form-control" name="invite-link">
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary btn-block" type="submit">Create</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-block">Cancel</a>
            </div>
        </form>
    </div>
@endsection

