@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">{{ $user->name . " Location's"}}</h1>

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

    <!-- Map Section -->
    <div id="map" style="width: 100%; height: 500px; margin-top: 20px; border: 1px solid #ccc;"></div>
</div>

<!-- Include Mapbox Script -->
<script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
<link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />

<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoicmVtbWF5cmluYTEyIiwiYSI6ImNtNHIzeTljcDAwdm4ycHEyazdoN2RsbmIifQ.YDYi19NcpW2HsKgACA8r3g';

    // Initialize the Map
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [120.4693, 15.8328], // Centered on Basista, Pangasinan
        zoom: 12
    });

    let marker;

    // Display saved locations as non-deletable markers
    const savedLocations = @json($markLocation);

    savedLocations.forEach(location => {
        const savedMarker = new mapboxgl.Marker({ color: 'blue' })
            .setLngLat([location.longitude, location.latitude])
            .setPopup(new mapboxgl.Popup().setText(location.name))
            .addTo(map);
    });

    // Add geolocate and navigation controls
    map.addControl(new mapboxgl.GeolocateControl({
        positionOptions: { enableHighAccuracy: true },
        trackUserLocation: true,
        showUserHeading: true
    }));

    map.addControl(new mapboxgl.NavigationControl());
</script>
@endsection
