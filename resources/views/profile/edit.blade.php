@extends('layouts.app')
@section('content')

<style>
.custom-modal {
    max-width: 50%;  /* Adjust the width of the modal */
}

.custom-modal .modal-body img {
    max-width: 100%;  /* Ensure the image fills the modal */
    width: 400px;
}

</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Edit Profile</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Profile Picture with Modal Preview -->
                        <div class="form-group text-center">
                            <label for="profile_picture">Profile Picture</label><br>
                                @if($user->info && $user->info->profile_picture)
                                    <!-- Thumbnail image with a click event to open modal -->
                                    <a href="#" data-toggle="modal" data-target="#profilePictureModal">
                                        <img src="{{ asset('storage/' . $user->info->profile_picture) }}" class="rounded-circle img-fluid mb-3" alt="Profile Picture" style="width: 100px; height: 100px;">
                                    </a>
                                @else
                                    <img src="{{ asset('assets/—Pngtree—vector add user icon_4101348.png') }}" class="rounded-circle img-fluid mb-3" alt="Default Picture" style="width: 100px; height: 100px;">
                                @endif
                            <input type="file" class="form-control" name="profile_picture">
                        </div>

                        <!-- Name -->
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" name="email" value="{{ $user->email }}" required>
                        </div>

                        <!-- Address -->
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control" name="address">{{ $user->info->address ?? '' }}</textarea>
                        </div>

                        <!-- Valid ID -->
                        <div class="form-group">
                            <label for="valid_id">Valid ID</label><br>
                            @if($user->info->valid_id && $user->info->valid_id)
                                <a href="{{ asset('storage/' . $user->info->valid_id) }}" target="_blank">View Current ID</a>
                            @endif
                            <input type="file" class="form-control" name="valid_id">
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                    </form>

                    <!-- Form to handle profile picture deletion -->
                    <form id="remove_profile_picture" action="{{ route('profile.update', $user->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="remove_profile_picture" value="true">
                    </form>

                    <!-- Form to handle valid ID deletion -->
                    <form id="remove_valid_id" action="{{ route('profile.update', $user->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="remove_valid_id" value="true">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div class="modal fade" id="profilePictureModal" tabindex="-1" aria-labelledby="profilePictureModalLabel" aria-hidden="true">
    <!-- Add 'custom-modal' class here for custom styling -->
    <div class="modal-dialog modal-dialog-centered custom-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profilePictureModalLabel">Profile Picture Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <!-- Display the full-size image with custom scaling -->
                <img src="{{ asset('storage/' . $user->info->profile_picture) }}" class="img-fluid" alt="Profile Picture" style="max-width: 100%; height: auto;">
            </div>
        </div>
    </div>
</div>

@endsection