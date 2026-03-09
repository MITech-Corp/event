@extends('layouts.app')

@section('title', 'Employees')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Employees</h1>
        <a href="{{ route('employees.import') }}" class="btn btn-primary">Import from Excel</a>
    </div>

    @if ($employees->count() === 0)
        <div class="alert alert-info">
            No employees found. Try importing from Excel.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Office</th>
                    <th>Created At</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $loop->iteration + ($employees->currentPage() - 1) * $employees->perPage() }}</td>
                        <td>{{ $employee->employee_id }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->phone }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->position }}</td>
                        <td>{{ $employee->office }}</td>
                        <td>{{ $employee->created_at?->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{ $employees->links() }}
    @endif
@endsection

