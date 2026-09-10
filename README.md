# Website Nurul Fikri Balikpapan

Website Sekolah Islam Terpadu (Daycare, KBIT, SDIT, SMPIT) — Laravel + Tailwind CSS + PostgreSQL (prod) / SQLite (dev).

## Menjalankan lokal

```sh
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed   # akun demo di bawah
php artisan storage:link
npm install && npm run dev   # terminal 1
php artisan serve            # terminal 2
```

Buka http://localhost:8000. Panel admin: http://localhost:8000/admin

## Akun demo (password: `password123`)

| Email | Role |
|---|---|
| admin@nfbpn.id | Super Admin |
| tu@nfbpn.id | Admin PPDB/TU |
| humas@nfbpn.id | Editor Konten |
| kepsek@nfbpn.id | Kepala Sekolah (read-only) |
| ortu@nfbpn.id | Orang Tua |

Ganti password demo setelah deploy perdana.

## Deploy production (VPS)

```sh
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
npm install && npm run build
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

Wajib di `.env` production:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-sekolah.sch.id
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nfbpn
DB_USERNAME=nfbpn
DB_PASSWORD=***
SESSION_SECURE_COOKIE=true
SESSION_ENCRYPT=true
```

Lainnya: aktifkan HTTPS + OPcache, jalankan `queue:work` (database driver),
cron `pg_dump` harian + retensi 14 hari, dan `composer audit` berkala.

## Struktur peran

- **Super Admin**: semua akses + kelola pengguna/role.
- **Admin PPDB**: periode, form builder, pendaftar + pipeline status, ekspor Excel, inbox.
- **Editor**: berita (CKEditor 5), galeri, agenda, testimoni. Langsung publish.
- **Kepala Sekolah**: dashboard + lihat data (read-only).
- **Orang Tua**: daftar PPDB, portal status, unggah berkas susulan.

## Tes

```sh
php artisan test
```
