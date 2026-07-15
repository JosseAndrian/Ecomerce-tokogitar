@extends('layouts.app')

@section('title', 'Admin Login - Musik Store')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-4 bg-dark text-light">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-lock display-4 text-warning mb-3"></i>
                        <h3 class="fw-bold">Admin Portal</h3>
                        <p class="text-secondary">Silakan login untuk mengelola sistem.</p>
                    </div>

                    <form action="{{ route('admin.login.post') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Administrator</label>
                            <input type="email" name="email" class="form-control form-control-lg bg-dark text-light border-secondary @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg bg-dark text-light border-secondary @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg w-100 mb-3 fw-bold">Login Sistem</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
