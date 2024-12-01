@extends('layouts.app')

@section('content')
@if(session('success'))
<script>
    Swal.fire({
        title: 'Success!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonText: 'OK'
    });
</script>
@endif
@if(session('error'))
<script>
    Swal.fire({
        title: 'Error!',
        text: "{{ session('error') }}",
        icon: 'error',
        confirmButtonText: 'OK'
    });
</script>
@endif

<!-- Search Form -->
<form action="{{ route('admin.auctioneerIndex') }}" method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" name="query" class="form-control" placeholder="Search for Auctioneer users..." value="{{ request('query') }}">
        <button class="btn btn-primary" type="submit">Search</button>
    </div>
</form>
<div class="py-12">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-4">{{ __("List of Auctioneers") }}</h3>

                @if ($users->isEmpty())
                    <p>{{ __("No auctioneers created yet.") }}</p>
                @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Address</th>
                            <th>Valid ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->info->contact_number ?? 'N/A' }}</td>
                                <td>{{ $user->info->address ?? 'N/A' }}</td>
                                <td>{{ $user->info->valid_id ?? 'N/A' }}</td>
                                <td>
                                    <!-- Suspend User -->
                                    <form action="{{ route('users.suspend', $user->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <label for="suspension_days">Suspend for:</label>
                                        <input type="number" name="days" id="suspension_days" placeholder="Days" required>
                                        <button type="submit" class="btn btn-warning"
                                            onclick="return confirm('Are you sure you want to suspend this user?')">
                                            Suspend
                                        </button>
                                    </form>

                                    <form action="{{ route('users.unsuspend', $user->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success"
                                            onclick="return confirm('Are you sure you want to ban this user?')">
                                            Unsuspend
                                        </button>
                                    </form>

                                    <!-- Ban User -->
                                    <form action="{{ route('users.ban', $user->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to ban this user?')">
                                            Ban
                                        </button>
                                    </form>

                                    <form action="{{ route('users.unban', $user->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success"
                                            onclick="return confirm('Are you sure you want to ban this user?')">
                                            Unban
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
