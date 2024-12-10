@extends('layouts.app2')
@section('content')
<style>
    body {
        background: linear-gradient(to right, #58d68d, #abebc6, #d5f5e3, #58d68d);
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        justify-content: center;
        color: #333;
    }
    .container{
        width: 100%;
        padding: 20px;
        box-sizing: border-box;
    }
    .card {
        background: url('assets/panibagong logo eyyy.png')no-repeat center;
        flex: 1;
        justify-content: center;
        height: 70vh;
        background: cover;
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: scale(1.02);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }
    .card-body {
        background: filter: blur(5px);
        padding: 20px;
    }
    .form-control {
        border:1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        font-size: 1rem;
        width: 100%;
        transition: border-color 0.2s ease;
    }
    .from-control:focus {
        border-color: #2575fc;
        box-shadow: 0 0 5px rgba(37, 117, 252, 0.5);
        outline: none;
    }
    .btn-primary {
        background: #2575fc;
        border: none;
        color: #fff;
        padding: 10px 20px;
        font-size: 1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.2s ease;
    }
    .btn-primary:hover {
        background: #1e63d5;
        transform: scale(1.05);
    }
    .btn-link {
        color: black;
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.2s ease;
    }
    .btn-link:hover {
        color: #1e63d5;
        text-decoration: underline;
    }
    .invalid-feedback {
        font-size: 0.9rem;
        color: #d9534f;
        margin-top: 5px;
    }
    .form-check-label {
        font-size: 0.9rem;
    }
    .input[type="checkbox"] {
        accent-color: #2575fc;
    }
    @media (max-width: 768px) {
        .card {
            margin: 10px;
        }
        .col-md-6, .col-md-8, .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        .text-md-end {
            text-align: left;
        }
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Role Field (Dropdown) -->
                        <div class="row mb-3">
                            <label for="role" class="col-md-4 col-form-label text-md-end">{{ __('Role') }}</label>

                            <div class="col-md-6">
                                <select id="role" class="form-control @error('role') is-invalid @enderror" name="role" required>
                                    <option value="bidder">{{ __('Bidder') }}</option>
                                    <option value="auctioneer">{{ __('Auctioneer') }}</option>
                                </select>

                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(event, 'password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(event, 'password-confirm')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                                <a class="btn btn-link" href="{{ route('login') }}">
                                    {{ __('Already have an account? Login') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function togglePassword(event, fieldId) {
        const passwordField = document.getElementById(fieldId);
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

        const icon = event.target.querySelector('i');
        if (icon) {
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    }
</script>
