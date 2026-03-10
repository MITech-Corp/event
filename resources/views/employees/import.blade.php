@extends('layouts.app')

@section('title', 'Import Karyawan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center mb-4">
                <h1 class="h4 fw-bold text-dark">Import Karyawan</h1>
                <p class="text-muted small mb-0">Unggah file Excel dari data pendaftaran Halal Bihalal</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-3">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card card-halal">
                <div class="card-body">
                    <form method="POST" action="{{ route('employees.import.process') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="file" class="form-label fw-600">File Excel</label>
                            <input type="file" class="form-control rounded-3" id="file" name="file" required accept=".xlsx,.xls,.csv">
                            <div class="form-text small">Format: .xlsx, .xls, atau .csv</div>
                        </div>

                        <div class="mb-4 p-3 rounded-3 small" style="background: rgba(13, 148, 136, 0.06);">
                            <p class="mb-2 fw-600 text-halal">Kolom yang diharapkan (baris pertama = header):</p>
                            <ul class="mb-0 text-muted">
                                <li>Full Name / Nama Lengkap</li>
                                <li>Employee ID No / Employee ID Number</li>
                                <li>Phone Number / Telepon</li>
                                <li>Your Email / Email</li>
                                <li>Role / Position</li>
                                <li>Office Placement</li>
                            </ul>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-halal rounded-3 py-2">
                                Unggah &amp; Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
