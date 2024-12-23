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

    .container {
        width: 100%;
        padding: 20px;
        box-sizing: border-box;
    }

    .card {
        background: url('assets/panibagong logo eyyy.png') no-repeat center;
        background-size: cover;
        display: flex;
        justify-content: center;
        height: auto;
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        margin: 20px 0;
    }

    .card:hover {
        transform: scale(1.02);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    .card-body {
        background: rgba(255, 255, 255, 0.8);
        padding: 30px;
        border-radius: 15px;
        width: 100%;
        box-sizing: border-box;
    }

    .form-control {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 12px;
        font-size: 1rem;
        width: 100%;
        transition: border-color 0.2s ease;
    }

    .form-control:focus {
        border-color: #2575fc;
        box-shadow: 0 0 5px rgba(37, 117, 252, 0.5);
        outline: none;
    }

    .btn-primary {
        background: #2575fc;
        border: none;
        color: #fff;
        padding: 12px 20px;
        font-size: 1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.2s ease;
        width: 100%;
        text-align: center;
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .card-body {
            padding: 20px;
        }

        .form-control {
            font-size: 0.9rem;
        }

        .btn-primary {
            padding: 12px;
            font-size: 1rem;
            width: 100%;
        }

        .btn-link {
            font-size: 1rem;
        }

        .card-header {
            text-align: center;
            font-size: 1.5rem; /* Increase size for mobile */
            margin-bottom: 20px;
        }

        .input-group {
            width: 100%;
        }

        .input-group-prepend {
            width: auto;
        }

        .input-group-btn {
            border-left: none;
        }

        .input-group .btn-outline-secondary {
            border-radius: 8px;
            padding: 10px;
            cursor: pointer;
        }
    }

    /* Larger screen styles */
    @media (min-width: 769px) {
        .col-md-6 {
            max-width: 45%;
        }
        .col-md-8 {
            max-width: 60%;
        }
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">{{ __('Login') }}</div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-8">
                                <div class="input-group">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
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
                            <div class="col-md-8 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId) {
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

@endsection
