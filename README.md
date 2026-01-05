# Desain Database Sistem Antrian

![Design DB](https://github.com/user-attachments/assets/4f4a0107-01f7-4b1d-b008-97038c00702e)

## Penjelasan Diagram

### Tabel: `users`
- Menyimpan data admin yang mengelola sistem antrian.
- **Primary Key**: `id`
- **Unique Key**: `email` (untuk login)
- **Password**: Di-hash menggunakan bcrypt

### Tabel: `queues`
- Menyimpan data antrian sederhana.
- **Primary Key**: `id`
- **Status**: 4 nilai (waiting, active, completed, skipped)
- **queue_number**: Format A001, A002, dst
- Tidak perlu relasi karena sistem ini sederhana tanpa data pasien.

### Relasi
- **users ke queues**: One-to-Many (1:N)
  - Satu admin dapat mengelola banyak antrian.
  - Relasi ini implicit (tidak ada foreign key di database).
  - Admin mengelola antrian melalui fitur Next/Prev/Complete/Skip.

## Cara Kerja
1. Pasien mengakses halaman utama dan klik tombol "Ambil Antrian".
2. Sistem generate nomor antrian otomatis (A001, A002, dst).
3. Admin login dan dapat:
   - Melihat antrian aktif.
   - Memanggil antrian selanjutnya (Next).
   - Kembali ke antrian sebelumnya (Prev).
   - Menyelesaikan antrian (Complete).
   - Melewati antrian (Skip).
4. Tampilan update realtime setiap 3 detik tanpa refresh.

## Catatan
- Nomor antrian direset setiap hari.
- Format nomor: A001, A002, A003, dst.
- Realtime menggunakan polling (interval 3 detik).

## Directory
### Database
- `database/migrations/2025_12_31_073823_queues.php`
- `database/seeders/AdminSeeder.php`
- `.env`

### Backend
- `app/Models/Queue.php`
- `app/Http/Controllers/QueueController.php`
- `app/Http/Controllers/AdminController.php`
- `app/Http/Controllers/AuthController.php`
- `routes/web.php`

### Frontend
- `resources/views/queue/index.blade.php`
- `resources/views/admin/login.blade.php`
- `resources/views/admin/dashboard.blade.php`
