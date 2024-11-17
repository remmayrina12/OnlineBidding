@extends('layouts.app')

@section('content')

<style>
    .star-rating {
        direction: rtl; /* Makes the highest value star on the left */
        display: inline-flex;
        gap: 5px;
    }

    .star-rating input[type="radio"] {
        display: none;
    }

    .star-rating label {
        cursor: pointer;
        font-size: 2rem;
        color: #ddd;
        transition: color 0.3s ease;
    }

    .star-rating input[type="radio"]:checked ~ label {
        color: #ffcc00; /* Highlighted stars */
    }

    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #ffcc00; /* Highlight on hover */
    }

    .average-rating {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .average-rating i {
        font-size: 1.5rem;
        color: #ffcc00;
    }
</style>

<div class="container">
    <h1>User Profile</h1>

    <div class="card">
        <div class="card-body">
            <!-- Profile Picture -->
            <div class="mb-4 text-center">
                @if($user->info && $user->info->profile_picture)
                                    <!-- Thumbnail image with a click event to open modal -->
                                    <a href="#" data-toggle="modal" data-target="#profilePictureModal">
                                        <img src="{{ asset('storage/' . $user->info->profile_picture) }}" class="rounded-circle img-fluid mb-3" alt="Profile Picture" style="width: 100px; height: 100px;">
                                    </a>
                                @else
                                    <img src="{{ asset('assets/—Pngtree—vector add user icon_4101348.png') }}" class="rounded-circle img-fluid mb-3" alt="Default Picture" style="width: 100px; height: 100px;">
                                @endif
            </div>

            <!-- Name -->
            <h5 class="card-title">Name: {{ $user->name }}</h5>

            <!-- Address -->
            <p class="card-text"><strong>Address:</strong> {{ $user->info->address ?? 'No address provided.' }}</p>

            <!-- Contact Number -->
            <p class="card-text"><strong>Contact Number:</strong> {{ $user->info->contact_number ?? 'No contact number provided.' }}</p>
        </div>
    </div>

    @if ($user->role === 'auctioneer')
        @if ($user->average_rating)
            <h3>Average Rating:</h3>
            <div class="average-rating">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $user->average_rating)
                        <i class="fa fa-star" style="color: #ffcc00;"></i> <!-- Filled Star -->
                    @else
                        <i class="fa fa-star" style="color: #ddd;"></i> <!-- Empty Star -->
                    @endif
                @endfor
                <span>({{ $user->average_rating }}/5)</span>
            </div>
        @else
            <h3>Average Rating: No ratings yet</h3>
        @endif

        @if (Auth::check() && Auth::id() !== $user->id)
            <form action="{{ route('ratings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}"> <!-- The user being rated -->

                <div class="form-group">
                    <label for="stars">Rate this Auctioneer:</label>
                    <div class="star-rating">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="stars" value="{{ $i }}" />
                            <label for="star{{ $i }}" title="{{ $i }} stars">
                                <i class="fa fa-star"></i>
                            </label>
                        @endfor
                    </div>
                </div>

                <div class="form-group">
                    <label for="feedback">Feedback:</label>
                    <textarea name="feedback" id="feedback" class="form-control" rows="3" placeholder="Leave your feedback..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit Rating</button>

            </form>
        @endif
        <ul class="list-group">
            @foreach ($user->ratingsReceived as $rating)
                <li class="list-group-item">
                    <div class="d-flex align-items-start">
                        <!-- Rater's Profile Picture (Optional) -->
                        @if ($rating->rater->info && $rating->rater->info->profile_picture)
                            <img src="{{ asset('storage/' . $rating->rater->info->profile_picture) }}"
                                class="rounded-circle mr-3"
                                alt="Rater's Picture"
                                style="width: 40px; height: 40px;">
                        @else
                            <img src="{{ asset('assets/default-avatar.png') }}"
                                class="rounded-circle mr-3"
                                alt="Default Avatar"
                                style="width: 40px; height: 40px;">
                        @endif

                        <!-- Rater's Name and Comment -->
                        <div>
                            <strong>{{ $rating->rater->name }}</strong>
                            <div class="star-rating mt-1">
                                <!-- Display the actual stars -->
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $rating->stars)
                                        <i class="fa fa-star" style="color: #ffcc00;"></i> <!-- Filled Star -->
                                    @else
                                        <i class="fa fa-star" style="color: #ddd;"></i> <!-- Empty Star -->
                                    @endif
                                @endfor
                                <span>({{ $rating->stars }} Stars)</span>
                            </div>
                            <p class="mt-2 mb-0">{{ $rating->feedback ?? 'No feedback provided.' }}</p>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
