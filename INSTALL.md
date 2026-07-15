# 🎸 E-Commerce Alat Musik - Panduan Instalasi

## Prasyarat
Pastikan sudah terinstall:
- PHP >= 8.2
- Composer
- MySQL (XAMPP/Laragon)
- Node.js >= 18

---

## Langkah Instalasi

### 1. Masuk ke folder project
```bash
cd ecommerce-alat-musik
```

### 2. Install dependensi PHP
```bash
composer install
```

### 3. Copy file environment
```bash
copy .env.example .env
```

### 4. Generate application key
```bash
php artisan key:generate
```

### 5. Buat database MySQL
Buka phpMyAdmin atau MySQL CLI, lalu buat database:
```sql
CREATE DATABASE ecommerce_alat_musik CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Konfigurasi database di .env
Edit file `.env` dan sesuaikan:
```
DB_DATABASE=ecommerce_alat_musik
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Jalankan migration & seeder
```bash
php artisan migrate --seed
```

### 8. Buat symbolic link storage
```bash
php artisan storage:link
```

### 9. Install dependensi Node.js
```bash
npm install
npm run dev
```

### 10. Jalankan server
```bash
php artisan serve
```

Buka browser: http://127.0.0.1:8000

---

## Akun Default

### Admin
- Email: admin@musikstore.com
- Password: admin123

### Customer Demo
- Email: customer@musikstore.com
- Password: customer123

---

## Struktur Fitur
- `/` - Landing Page
- `/shop` - Katalog Produk
- `/login` - Login Customer
- `/register` - Registrasi Customer
- `/admin` - Dashboard Admin
- `/admin/login` - Login Admin
