@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Mark Safe Location</h1>

    <!-- Display Success/Error Messages -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="location-form" action="{{ route('markLocation.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Location Name:</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Enter location name" required>
        </div>
        <div class="mb-3">
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
        </div>
        <button type="submit" class="btn btn-primary">Save Location</button>
    </form>

    <div id="map" style="width: 100%; height: 500px; margin-top: 20px; border: 1px solid #ccc;"></div>
</div>

<!-- Include Mapbox Script -->
<script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
<link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />

<script>
    // Set your Mapbox access token
    mapboxgl.accessToken = 'pk.eyJ1IjoicmVtbWF5cmluYTEyIiwiYSI6ImNtNHIzeTljcDAwdm4ycHEyazdoN2RsbmIifQ.YDYi19NcpW2HsKgACA8r3g';

    // Initialize the Map
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [120.4693, 15.8328], // Centered on Basista, Pangasinan
        zoom: 12
    });

    let marker;

    const savedLocations = @json($markLocations);

    // Add saved locations to the map
    savedLocations.forEach(location => {
        new mapboxgl.Marker({ color: 'blue' })
            .setLngLat([location.longitude, location.latitude])
            .setPopup(new mapboxgl.Popup().setText(location.name))
            .addTo(map);
    });

    // Handle map click event
    map.on('click', (e) => {
        const { lng, lat } = e.lngLat;

        // Remove the previous marker, if any
        if (marker) marker.remove();

        // Add a new marker
        marker = new mapboxgl.Marker({ color: 'red' })
            .setLngLat([lng, lat])
            .addTo(map);

        // Update hidden input fields with coordinates
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    });

    // Add a geolocate control to the map
    map.addControl(new mapboxgl.GeolocateControl({
        positionOptions: {
            enableHighAccuracy: true
        },
        trackUserLocation: true,
        showUserHeading: true
    }));

    // Add zoom and rotation controls
    map.addControl(new mapboxgl.NavigationControl());
</script>
@endsection
