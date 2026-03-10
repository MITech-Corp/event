@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="text-center mb-4">
                <h1 class="h4 fw-bold text-dark">Masuk ke Akun</h1>
                <p class="text-muted small mb-0">Halal Bihalal — Absensi</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-3">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card card-halal">
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-600">Email</label>
                            <input type="email" name="email" id="email"
                                   class="form-control rounded-3 @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required autofocus autocomplete="email"
                                   placeholder="nama@email.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-600">Password</label>
                            <input type="password" name="password" id="password"
                                   class="form-control rounded-3 @error('password') is-invalid @enderror"
                                   required autocomplete="current-password" placeholder="••••••••">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input">
                            <label for="remember" class="form-check-label small">Ingat saya</label>
                        </div>

                        <button type="submit" class="btn btn-halal w-100 rounded-3 py-2">Masuk</button>
                    </form>

                    <p class="mt-3 mb-0 text-center text-muted small">
                        Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none fw-600 text-halal">Daftar</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
