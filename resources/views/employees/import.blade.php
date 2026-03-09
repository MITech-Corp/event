@extends('layouts.app')

@section('title', 'Import Employees')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="h3 mb-3 text-center">Import Employees from Excel</h1>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
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
                    <form method="POST" action="{{ route('employees.import.process') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="file" class="form-label">Excel File</label>
                            <input type="file" class="form-control" id="file" name="file" required>
                            <div class="form-text">
                                Accepted formats: .xlsx, .xls, .csv
                            </div>
                        </div>

                        <div class="mb-3">
                            <p class="mb-1"><strong>Expected columns (header row):</strong></p>
                            <ul class="mb-0">
                                <li>Full Name</li>
                                <li>Employee ID Number</li>
                                <li>Phone Number (WhatsApp)</li>
                                <li>Your Email</li>
                                <li>Role / Position</li>
                                <li>Office Placement</li>
                                <li>I hereby confirm my attendance at this event. (ignored)</li>
                            </ul>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Upload &amp; Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

