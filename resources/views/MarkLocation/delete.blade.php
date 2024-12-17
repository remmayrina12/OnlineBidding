@foreach ($locations as $location)
    <div>
        <h4>{{ $location->name }}</h4>
        <p>Latitude: {{ $location->latitude }}</p>
        <p>Longitude: {{ $location->longitude }}</p>

        <!-- Delete Button -->
        <form action="{{ route('auth.safe_location.destroy', $location->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this location?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
@endforeach
