@extends('layouts.app')

@section('title', 'Employee Event Attendance')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center mb-4">Employee Event Attendance</h1>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning">
                    {{ session('warning') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('attendance.checkin') }}" id="attendance-form">
                        @csrf
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee ID</label>
                            <input type="text"
                                   class="form-control"
                                   id="employee_id"
                                   name="employee_id"
                                   <!-- placeholder="MIT00459" -->
                                   value="{{ old('employee_id') }}"
                                   required>
                        </div>

                        <div id="employee-info" class="mb-3" style="display: none;">
                            <div class="border rounded p-2 bg-light">
                                <p class="mb-1"><strong>Name:</strong> <span id="employee-name"></span></p>
                                <p class="mb-0"><strong>Position:</strong> <span id="employee-position"></span></p>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Check In
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const employeeIdInput = document.getElementById('employee_id');
        const employeeInfo = document.getElementById('employee-info');
        const employeeName = document.getElementById('employee-name');
        const employeePosition = document.getElementById('employee-position');

        async function fetchEmployee() {
            const employeeId = employeeIdInput.value.trim();
            employeeInfo.style.display = 'none';
            employeeName.textContent = '';
            employeePosition.textContent = '';

            if (!employeeId) {
                return;
            }

            try {
                const response = await fetch(`{{ route('attendance.find') }}?employee_id=${encodeURIComponent(employeeId)}`);

                if (!response.ok) {
                    return;
                }

                const data = await response.json();
                employeeName.textContent = data.name || '-';
                employeePosition.textContent = data.position || '-';
                employeeInfo.style.display = 'block';
            } catch (e) {
                // Silent fail for optional feature
            }
        }

        employeeIdInput.addEventListener('blur', fetchEmployee);
        employeeIdInput.addEventListener('change', fetchEmployee);
    </script>
@endpush

