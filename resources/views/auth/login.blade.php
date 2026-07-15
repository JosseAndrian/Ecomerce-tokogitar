@extends('layouts.app')

@section('title', 'Login - Musik Store')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-circle display-4 text-primary mb-3"></i>
                        <h3 class="fw-bold">Login Member</h3>
                        <p class="text-muted">Masuk ke akun Anda untuk mulai belanja.</p>
                    </div>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        @if(request()->has('redirect'))
                            <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                        @endif
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="••••••••" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                <label class="form-check-label text-muted" for="remember">Ingat Saya</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3 rounded-pill">Login Sekarang</button>
                        
                        <div class="text-center text-muted">
                            Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none fw-bold">Daftar di sini</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
