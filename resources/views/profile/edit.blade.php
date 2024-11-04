@extends('layouts.app')

@section('content')
    <h1>Edit Profile</h1>

    {{-- <form action="{{ route('profile.update', $profile->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ $profile->name }}" class="form-control">
        </div>

        <!-- Add other profile fields as needed -->

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form> --}}
@endsection
