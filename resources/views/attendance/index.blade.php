@extends('layouts.app')

@section('title', 'Absensi Halal Bihalal')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            {{-- Hero --}}
            <div class="text-center mb-4 pb-2">
                <p class="text-uppercase tracking-wide small text-halal fw-600 mb-1">WELCOME TO</p>
                <h1 class="display-6 fw-bold text-dark mb-2">HALAL BIHALAL MITECH 2026
                </h1>
                <p class="text-muted mb-0">Please enter your Employee ID to proceed with attendance check-in.</p>
            </div>

            @php
                $flashType = null;
                $flashMessage = null;
                $flashEmployeeName = null;
                $flashEmployeePosition = null;

                if (session('success')) {
                    $flashType = 'success';
                    $flashMessage = session('success');
                    $flashEmployeeName = session('employee_name');
                    $flashEmployeePosition = session('employee_position');
                } elseif (session('warning')) {
                    $flashType = 'warning';
                    $flashMessage = session('warning');
                } elseif (session('error')) {
                    $flashType = 'error';
                    $flashMessage = session('error');
                }
            @endphp

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Popup notifikasi kehadiran (custom overlay agar pasti muncul di mobile) --}}
            <div id="attendanceNotifyOverlay" class="attendance-notify-overlay" role="alertdialog" aria-modal="true" aria-labelledby="attendanceNotifyTitle" aria-hidden="true" style="display: none;">
                <div class="attendance-notify-backdrop" id="attendanceNotifyBackdrop"></div>
                <div class="attendance-notify-box">
                    <div class="attendance-notify-content">
                        <div class="mb-3" id="attendanceNotifyIcon"></div>
                        <h5 class="fw-bold mb-2" id="attendanceNotifyTitle">Notification</h5>
                        <p class="text-muted mb-0" id="attendanceNotifyMessage"></p>
                        <div id="attendanceNotifyPerson" class="mt-3 small text-start d-none">
                            <p class="mb-1 text-muted">Name</p>
                            <p class="mb-0 fw-600" id="attendanceNotifyPersonName">-</p>
                            <p class="mb-0 mt-2 text-muted">Position</p>
                            <p class="mb-0" id="attendanceNotifyPersonPosition">-</p>
                        </div>
                        <button type="button" class="btn btn-halal rounded-3 mt-4 px-4" id="attendanceNotifyClose">Tutup</button>
                    </div>
                </div>
            </div>

            <div class="card card-halal">
                <div class="card-body">
                    <form method="POST" action="{{ route('attendance.checkin') }}" id="attendance-form">
                        @csrf
                        <div class="mb-3">
                            <label for="employee_id" class="form-label fw-600">Employee ID</label>
                            <input type="text"
                                   class="form-control form-control-lg rounded-3 border-2"
                                   id="employee_id"
                                   name="employee_id"
                                   placeholder=""
                                   value="{{ old('employee_id') }}"
                                   required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-halal btn-lg rounded-3 py-3">
                                Check In
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <p class="text-center text-muted small mt-3 mb-0">
            Building People, Strengthening Culture.
            </p>
        </div>
    </div>

    @if ($flashMessage)
        <script id="attendance-flash-data" type="application/json">
            {!! json_encode([
                'type' => $flashType,
                'message' => $flashMessage,
                'name' => $flashEmployeeName,
                'position' => $flashEmployeePosition,
            ]) !!}
        </script>
    @endif
@endsection

@push('styles')
<style>
.attendance-notify-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    min-height: 100vh;
    min-height: 100dvh;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    box-sizing: border-box;
    -webkit-overflow-scrolling: touch;
    -webkit-tap-highlight-color: transparent;
}
.attendance-notify-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.5);
    -webkit-tap-highlight-color: transparent;
}
.attendance-notify-box {
    position: relative;
    width: 100%;
    max-width: 360px;
    max-height: 90vh;
    overflow: auto;
    background: #fff;
    border-radius: 1rem;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    border-top: 4px solid #0d9488;
}
.attendance-notify-content {
    text-align: center;
    padding: 1.5rem 1.25rem;
}
</style>
@endpush

@push('scripts')
    <script>
        (function() {
            function showAttendanceNotify() {
                var dataEl = document.getElementById('attendance-flash-data');
                if (!dataEl) return;
                var data;
                try { data = JSON.parse(dataEl.textContent); } catch (e) { return; }
                var type = data.type || 'success';
                var message = data.message || '';
                var name = data.name || '';
                var position = data.position || '';

                var overlay = document.getElementById('attendanceNotifyOverlay');
                var iconEl = document.getElementById('attendanceNotifyIcon');
                var titleEl = document.getElementById('attendanceNotifyTitle');
                var msgEl = document.getElementById('attendanceNotifyMessage');
                var personBlock = document.getElementById('attendanceNotifyPerson');
                var personNameEl = document.getElementById('attendanceNotifyPersonName');
                var personPositionEl = document.getElementById('attendanceNotifyPersonPosition');
                var box = overlay && overlay.querySelector('.attendance-notify-box');

                var config = {
                    success: { title: 'Attendance recorded', borderColor: '#0d9488',
                        icon: '<div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:64px;height:64px;background:rgba(13,148,136,0.15);"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#0f766e" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg></div>'
                    },
                    warning: { title: 'Notification', borderColor: '#d97706',
                        icon: '<div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:64px;height:64px;background:#fef3c7;"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#d97706" viewBox="0 0 16 16"><path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg></div>'
                    },
                    error: { title: 'Error', borderColor: '#dc2626',
                        icon: '<div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:64px;height:64px;background:#fee2e2;"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#dc2626" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/></svg></div>'
                    }
                };
                var c = config[type] || config.success;
                if (iconEl) iconEl.innerHTML = c.icon;
                if (titleEl) titleEl.textContent = c.title;
                if (msgEl) msgEl.textContent = message;
                if (box) box.style.borderTopColor = c.borderColor;

                if (type === 'success' && (name || position)) {
                    if (personBlock) {
                        personBlock.classList.remove('d-none');
                        if (personNameEl) personNameEl.textContent = name || '-';
                        if (personPositionEl) personPositionEl.textContent = position || '-';
                    }
                } else if (personBlock) {
                    personBlock.classList.add('d-none');
                }

                function closeOverlay() {
                    overlay.style.display = 'none';
                    overlay.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                }

                overlay.setAttribute('aria-hidden', 'false');
                overlay.style.display = 'flex';
                document.body.style.overflow = 'hidden';

                document.getElementById('attendanceNotifyClose').onclick = closeOverlay;
                document.getElementById('attendanceNotifyBackdrop').onclick = closeOverlay;

                setTimeout(closeOverlay, 5000);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() { setTimeout(showAttendanceNotify, 50); });
            } else {
                setTimeout(showAttendanceNotify, 50);
            }
        })();
    </script>
@endpush
