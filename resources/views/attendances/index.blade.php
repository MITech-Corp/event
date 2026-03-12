@extends('layouts.app')

@section('title', 'Rekap Kehadiran')

@section('content')
    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-3" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-4">
        <div>
            <h1 class="h4 fw-bold text-dark mb-0">Rekap Kehadiran</h1>
            <p class="text-muted small mb-0">Daftar check-in acara Halal Bihalal</p>
        </div>
        <div class="nav nav-pills gap-1" role="tablist">
            <a class="nav-link rounded-3 {{ ($filter ?? 'sudah_hadir') === 'sudah_hadir' ? 'active bg-halal' : '' }}" href="{{ route('attendances.index', ['filter' => 'sudah_hadir']) }}">
                Sudah hadir ({{ $attendances->total() }})
            </a>
            <a class="nav-link rounded-3 {{ ($filter ?? '') === 'belum_hadir' ? 'active bg-halal' : '' }}" href="{{ route('attendances.index', ['filter' => 'belum_hadir']) }}">
                Belum hadir
            </a>
        </div>
        @if ($attendances->total() > 0 && ($filter ?? 'sudah_hadir') === 'sudah_hadir')
            <button type="button" class="btn btn-outline-danger btn-sm rounded-3" data-bs-toggle="modal" data-bs-target="#resetAttendancesModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1 align-text-bottom" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                </svg>
                Reset Rekap
            </button>
        @endif
    </div>

    {{-- Modal konfirmasi reset --}}
    @if ($attendances->total() > 0)
        <div class="modal fade" id="resetAttendancesModal" tabindex="-1" aria-labelledby="resetAttendancesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-600" id="resetAttendancesModalLabel">Reset Rekap Kehadiran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Semua data kehadiran ({{ number_format($attendances->total()) }} record) akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.</p>
                        <p class="text-danger small mb-0 mt-2">Yakin ingin melanjutkan?</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('attendances.reset') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger rounded-3">Ya, Reset Semua</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (($filter ?? 'sudah_hadir') === 'belum_hadir')
        {{-- Tampilan filter: Belum hadir --}}
        @if ($absentEmployees === null)
            <div class="card card-halal">
                <div class="card-body text-center py-5">
                    <p class="text-muted mb-0">Data karyawan tidak tersedia. Periksa konfigurasi SharePoint.</p>
                </div>
            </div>
        @elseif ($absentEmployees->count() === 0)
            <div class="card card-halal">
                <div class="card-body text-center py-5">
                    <p class="text-success mb-0">Semua karyawan sudah hadir.</p>
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
                                    <th class="text-nowrap d-none d-md-table-cell">Posisi</th>
                                    <th class="text-nowrap d-none d-lg-table-cell">Kantor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($absentEmployees as $emp)
                                    <tr>
                                        <td class="ps-3 text-muted">{{ $loop->iteration + ($absentEmployees->currentPage() - 1) * $absentEmployees->perPage() }}</td>
                                        <td><span class="fw-600">{{ $emp->employee_id ?? '–' }}</span></td>
                                        <td>{{ $emp->name ?? '–' }}</td>
                                        <td class="d-none d-md-table-cell small">{{ $emp->position ?? '–' }}</td>
                                        <td class="d-none d-lg-table-cell small text-muted">{{ $emp->office ?? '–' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($absentEmployees->hasPages())
                    <div class="card-footer bg-transparent border-top d-flex justify-content-between align-items-center flex-wrap gap-2 py-2">
                        <small class="text-muted">
                            Menampilkan {{ $absentEmployees->firstItem() }}–{{ $absentEmployees->lastItem() }} dari {{ $absentEmployees->total() }} belum hadir
                        </small>
                        <div>{{ $absentEmployees->links('pagination::bootstrap-5') }}</div>
                    </div>
                @endif
            </div>
        @endif
    @elseif ($attendances->count() === 0)
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
