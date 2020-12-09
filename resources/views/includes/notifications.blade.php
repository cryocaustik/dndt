@if (count($errors->all()) > 0)
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="fa fa-ban"></i> Error</h4>

        <p class="message">
            @if(is_array($errors->all()))
                @foreach ($errors->all() as $m)
                    {{ $m }}<br>
                @endforeach
            @else
                {{ $errors->all() }}
            @endif
        </p>
    </div>
@endif

@if ($message = session('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4><i class="fa fa-check-circle"></i> Success</h4>

        <p class="message">
            @if(is_array($message))
                @foreach ($message as $m)
                    {{ $m }}<br>
                @endforeach
            @else
                {{ $message }}
            @endif
        </p>
    </div>
@endif

@if ($message = session('status'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4><i class="fa fa-check-circle"></i> Status</h4>

        <p class="message">
            @if(is_array($message))
                @foreach ($message as $m)
                    {{ $m }}<br>
                @endforeach
            @else
                {{ $message }}
            @endif
        </p>
    </div>
@endif

@if ($message = session('error'))
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="fa fa-ban"></i> Error</h4>

        <p class="message">
            @if(is_array($message))
                @foreach ($message as $m)
                    {{ $m }}<br>
                @endforeach
            @else
                {{ $message }}
            @endif
        </p>
    </div>
@endif

@if ($message = session('warning'))
    <div class="alert alert-warning alert-block">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4><i class="fa fa-exclamation-circle"></i> Warning</h4>

        <p class="message">
            @if(is_array($message))
                @foreach ($message as $m)
                    {{ $m }}<br>
                @endforeach
            @else
                {{ $message }}
            @endif
        </p>
    </div>
@endif

@if ($message = session('notice'))
    <div class="alert alert-warning alert-block">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4><i class="fa fa-exclamation-circle"></i> Notice</h4>

        <p class="message">
            @if(is_array($message))
                @foreach ($message as $m)
                    {{ $m }}<br>
                @endforeach
            @else
                {{ $message }}
            @endif
        </p>
    </div>
@endif

@if ($message = session('info'))
    <div class="alert alert-info alert-block">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4><i class="fa fa-info-circle"></i> Info</h4>

        <p class="message">
            @if(is_array($message))
                @foreach ($message as $m)
                    {{ $m }}
                @endforeach
            @else
                {{ $message }}
            @endif
        </p>
    </div>
@endif
