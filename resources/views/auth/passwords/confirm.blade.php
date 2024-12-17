@extends('layouts.app2')

@section('content')
<style>
    body {
        background: linear-gradient(to right, #58d68d, #abebc6, #d5f5e3, #58d68d);
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        color: #333;
    }

    .container {
        width: 100%;
        padding: 20px;
        box-sizing: border-box;
    }

    .card {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        padding: 30px;
        max-width: 500px;
        margin: auto;
        text-align: center;
    }

    .card-header {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 20px;
        color: #2575fc;
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
        margin-top: 20px;
    }

    .btn-primary:hover {
        background: #1e63d5;
        transform: scale(1.05);
    }

    .btn-link {
        color: #2575fc;
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .card {
            padding: 20px;
        }

        .form-control {
            font-size: 0.9rem;
        }

        .btn-primary {
            padding: 10px;
            font-size: 0.9rem;
        }

        .card-header {
            font-size: 1.2rem;
        }
    }
</style>

<div class="container">
    <div class="card">
        <div class="card-header">{{ __('Confirm Password') }}</div>

        <div class="card-body">
            <p>{{ __('Please confirm your password before continuing.') }}</p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-0">
                    <button type="submit" class="btn btn-primary">{{ __('Confirm Password') }}</button>

                    @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
