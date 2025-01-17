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

                        <!-- Contact Number -->
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input class="form-control" name="contact_number" value=" {{ $user->info->contact_number ?? '' }}">
                        </div>

                        <!-- Address -->
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control" name="address">{{ $user->info->address ?? '' }}</textarea>
                        </div>

                        <!-- Valid ID -->
                        <div class="form-group">
                            <label for="valid_id">Valid ID</label><br>
                            @if($user->info && $user->info->valid_id)
                                <a href="{{ asset('storage/' . $user->info->valid_id) }}" target="_blank">View Current ID</a>
                            @endif
                            <input type="file" class="form-control" name="valid_id">
                        </div>
                        @if(auth()->user() && auth()->user()->role === 'auctioneer')
                            <a href="{{ route('markLocation.create', $user->id) }}" class="btn btn-primary">Create Location</a> <br>
                        @endif

                        <button type="submit" class="btn btn-primary mt-3" onclick="return confirm('Are you sure you want to save it?')">Save Changes</button>
                    </form>

                    <!-- Password Update Form -->
                    <form action="{{ route('profile.updatePassword') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <div class="input-group">
                                <input type="password" name="current_password" id="current_password" class="form-control" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to change your password?')">Change Password</button>
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
                <img src="{{ isset($user->info) && $user->info->profile_picture ? asset('storage/' . $user->info->profile_picture) : asset('default-profile-picture.png') }}" class="img-fluid" alt="Profile Picture">
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

        // Toggle the icon (requires Font Awesome or similar library)
        const icon = event.target.querySelector('i');
        if (icon) {
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    }
</script>

