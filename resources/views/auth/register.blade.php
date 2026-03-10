@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="text-center mb-4">
                <h1 class="h4 fw-bold text-dark">Daftar Akun</h1>
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
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-600">Nama</label>
                            <input type="text" name="name" id="name"
                                   class="form-control rounded-3 @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required autofocus autocomplete="name"
                                   placeholder="Nama lengkap">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-600">Email</label>
                            <input type="email" name="email" id="email"
                                   class="form-control rounded-3 @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required autocomplete="email"
                                   placeholder="nama@email.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-600">Password</label>
                            <input type="password" name="password" id="password"
                                   class="form-control rounded-3 @error('password') is-invalid @enderror"
                                   required autocomplete="new-password" placeholder="Min. 8 karakter">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-600">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control rounded-3" required autocomplete="new-password"
                                   placeholder="Ulangi password">
                        </div>

                        <button type="submit" class="btn btn-halal w-100 rounded-3 py-2">Daftar</button>
                    </form>

                    <p class="mt-3 mb-0 text-center text-muted small">
                        Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-600 text-halal">Masuk</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
