@extends('layouts.app')

@section('title', 'Rekap Kehadiran')

@section('content')
    <div class="mb-4">
        <h1 class="h4 fw-bold text-dark">Rekap Kehadiran</h1>
        <p class="text-muted small mb-0">Daftar check-in acara Halal Bihalal</p>
    </div>

    @if ($attendances->count() === 0)
        <div class="card card-halal">
            <div class="card-body text-center py-5">
                <div class="text-muted mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="opacity-50" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                </div>
                <p class="text-muted mb-0">Belum ada data kehadiran.</p>
                <p class="small text-muted">Data akan muncul setelah peserta melakukan check-in di halaman absensi.</p>
                <a href="{{ route('attendance.index') }}" class="btn btn-halal btn-sm mt-2 rounded-3">Ke Halaman Absensi</a>
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
                                <th class="text-nowrap">Nama</th>
                                <th class="text-nowrap">Employee ID</th>
                                <th class="text-nowrap d-none d-md-table-cell">Posisi</th>
                                <th class="text-nowrap d-none d-lg-table-cell">Kantor</th>
                                <th class="text-nowrap">Waktu Check-in</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td class="ps-3 text-muted">{{ $loop->iteration + ($attendances->currentPage() - 1) * $attendances->perPage() }}</td>
                                    <td class="fw-600">{{ $attendance->employee_name ?? '–' }}</td>
                                    <td>{{ $attendance->employee_identifier ?? '–' }}</td>
                                    <td class="d-none d-md-table-cell small">{{ $attendance->employee_position ?? '–' }}</td>
                                    <td class="d-none d-lg-table-cell small text-muted">{{ $attendance->employee_office ?? '–' }}</td>
                                    <td class="small">{{ $attendance->checkin_time?->format('d/m/Y H:i') ?? '–' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($attendances->hasPages())
                <div class="card-footer bg-transparent border-top d-flex justify-content-between align-items-center flex-wrap gap-2 py-2">
                    <small class="text-muted">
                        Menampilkan {{ $attendances->firstItem() }}–{{ $attendances->lastItem() }} dari {{ $attendances->total() }} kehadiran
                    </small>
                    <div>{{ $attendances->links('pagination::bootstrap-5') }}</div>
                </div>
            @endif
        </div>
    @endif
@endsection
