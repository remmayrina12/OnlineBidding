@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Your Notifications</h3>
    <ul id="notifications-list">
        @foreach ($notifications as $notification)
            <li class="{{ $notification->read_at ? '' : 'font-weight-bold' }}">
                {{ $notification->data['message'] }}
                <small>{{ $notification->created_at->diffForHumans() }}</small>
            </li>
        @endforeach
    </ul>
</div>
<script>
    window.userId = {{ auth()->id() }};
</script>
<script src="{{ asset('js/countdown.js') }}" defer></script>
@endsection
