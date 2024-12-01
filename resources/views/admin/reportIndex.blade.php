@extends('layouts.app')

@section('content')
    <h1>Manage Reports</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Reported User</th>
                <th>Reported By</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->id }}</td>
                    <td>{{ $report->reportedUser->name }}</td>
                    <td>{{ $report->reporter->name }}</td>
                    <td>{{ $report->reason }}</td>
                    <td>{{ $report->status }}</td>
                    <td>
                        @if ($report->status == 'reviewed')
                            <button class="btn btn-outline-secondary" disabled>
                                Review
                            </button>
                        @else
                            <a href="{{ route('reports.updateStatus', $report->id)}}"
                                class="btn btn-outline-secondary">
                                Review
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
