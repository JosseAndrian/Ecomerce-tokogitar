@extends($layout)

@section('title', 'Pengaturan Profil - Musik Store')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="fw-bold mb-4"><i class="bi bi-person-gear text-primary me-2"></i>Pengaturan Akun</h2>
            
            <!-- Update Profile Info -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Informasi Profil</h5>
                    <p class="text-muted small mb-4">Perbarui informasi profil dan alamat email akun Anda.</p>
                    
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small">Alamat Email</label>
                            <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm">Simpan Perubahan</button>
                    </form>
                </div>
            </div>

            <!-- Update Password -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Ganti Password</h5>
                    <p class="text-muted small mb-4">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.</p>
                    
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control rounded-3 @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Password Baru</label>
                            <input type="password" name="password" class="form-control rounded-3 @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control rounded-3" required>
                        </div>
                        
                        <button type="submit" class="btn btn-dark px-4 rounded-pill shadow-sm">Perbarui Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
