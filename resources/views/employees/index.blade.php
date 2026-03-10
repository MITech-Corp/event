@extends('layouts.app')

@section('title', 'Daftar Karyawan')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-4">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <h1 class="h4 mb-0 fw-bold text-dark">Daftar Karyawan</h1>
            @if(isset($fromSharePoint) && $fromSharePoint)
                <span class="badge bg-primary">Sumber: SharePoint</span>
            @endif
        </div>
    </div>

    @if (!$employees->count())
        <div class="card card-halal">
            <div class="card-body text-center py-5">
                <div class="text-halal opacity-75 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                    </svg>
                </div>
                @if(isset($sharePointConfigured) && !$sharePointConfigured)
                    <p class="text-muted mb-0">SharePoint belum dikonfigurasi.</p>
                    <p class="small text-muted">Isi MS_TENANT_ID, MS_CLIENT_ID, MS_CLIENT_SECRET, serta MS_USER_UPN atau MS_DRIVE_ID dan MS_FILE_PATH di file .env. Lihat README untuk panduan.</p>
                @else
                    <p class="text-muted mb-0">Tidak ada data dari file SharePoint.</p>
                    <p class="small text-muted">Periksa MS_FILE_PATH dan pastikan file Excel/CSV ada di SharePoint/OneDrive.</p>
                @endif
            </div>
        </div>
    @else
        <div class="card card-halal">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap ps-3" style="width: 3rem;">#</th>
                                <th class="text-nowrap">Employee ID</th>
                                <th class="text-nowrap">Nama</th>
                                <th class="text-nowrap d-none d-md-table-cell">Telepon</th>
                                <th class="text-nowrap d-none d-lg-table-cell">Email</th>
                                <th class="text-nowrap d-none d-lg-table-cell">Posisi</th>
                                <th class="text-nowrap d-none d-xl-table-cell">Kantor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr>
                                    <td class="ps-3 text-muted">{{ $loop->iteration + ($employees->currentPage() - 1) * $employees->perPage() }}</td>
                                    <td><span class="fw-600">{{ $employee->employee_id }}</span></td>
                                    <td>{{ $employee->name ?? '–' }}</td>
                                    <td class="d-none d-md-table-cell small">
                                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $employee->phone ?? '') }}" class="text-decoration-none text-halal" target="_blank" rel="noopener">{{ $employee->phone ?? '–' }}</a>
                                    </td>
                                    <td class="d-none d-lg-table-cell small text-break" style="max-width: 200px;">
                                        @if($employee->email)
                                            <a href="mailto:{{ $employee->email }}" class="text-decoration-none text-halal">{{ $employee->email }}</a>
                                        @else – @endif
                                    </td>
                                    <td class="d-none d-lg-table-cell small">{{ $employee->position ?? '–' }}</td>
                                    <td class="d-none d-xl-table-cell small text-muted">{{ $employee->office ?? '–' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($employees->hasPages())
                <div class="card-footer bg-transparent border-top d-flex justify-content-between align-items-center flex-wrap gap-2 py-2">
                    <small class="text-muted">Menampilkan {{ $employees->firstItem() }}–{{ $employees->lastItem() }} dari {{ $employees->total() }} karyawan</small>
                    <div>{{ $employees->links('pagination::bootstrap-5') }}</div>
                </div>
            @endif
        </div>
    @endif
@endsection
