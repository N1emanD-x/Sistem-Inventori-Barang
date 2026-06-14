# Sistem Inventori Barang

UTS Pemrograman Web II — IF403 PJJ Informatika Universitas Siber Asia

## Deskripsi
Website sistem manajemen inventori barang berbasis PHP dan MySQL yang memiliki fitur login, register, dashboard, dan manajemen data barang serta kategori.

## Tools
- PHP
- MySQL & phpMyAdmin
- Bootstrap 5
- Laragon (Local Server)
- Visual Studio Code

## Fitur
- Login & Register dengan status Admin/User
- Dashboard dengan statistik data real-time
- CRUD Manajemen Barang
- CRUD Manajemen Kategori
- Logout

## Struktur Database
- Tabel `users` — data akun pengguna
- Tabel `kategori` — data kategori barang
- Tabel `barang` — data barang inventori

## Cara Menjalankan
1. Import database `db_inventori` di phpMyAdmin
2. Letakkan folder project di `C:\laragon\www\`
3. Jalankan Laragon dan aktifkan Apache & MySQL
4. Buka browser dan akses `http://localhost/inventori/login.php`

## Login Default
| Username | Password | Status |
|----------|----------|--------|
| admin    | password | Admin  |
| user1    | password | User   |