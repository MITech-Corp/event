@extends('layouts.app')

@section('title', 'Attendance Records')

@section('content')
    <h1 class="h3 mb-3">Attendance Records</h1>

    @if ($attendances->count() === 0)
        <div class="alert alert-info">
            No attendance records found yet.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Employee Name</th>
                    <th>Employee ID</th>
                    <th>Position</th>
                    <th>Office</th>
                    <th>Check-in Time</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($attendances as $attendance)
                    <tr>
                        <td>{{ $loop->iteration + ($attendances->currentPage() - 1) * $attendances->perPage() }}</td>
                        <td>{{ $attendance->employee?->name }}</td>
                        <td>{{ $attendance->employee?->employee_id }}</td>
                        <td>{{ $attendance->employee?->position }}</td>
                        <td>{{ $attendance->employee?->office }}</td>
                        <td>{{ $attendance->checkin_time?->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{ $attendances->links() }}
    @endif
@endsection

