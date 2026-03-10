<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Menjalankan dengan Docker (PHP 8.3)

Pastikan [Docker](https://docs.docker.com/get-docker/) dan Docker Compose sudah terpasang.

1. Salin `.env.example` ke `.env` jika belum ada.
2. Build dan jalankan: `docker compose up -d --build`
3. Generate key & migration: `docker compose exec app php artisan key:generate` lalu `docker compose exec app php artisan migrate`
4. Akses: http://localhost:8000

Database: MySQL di `localhost:3306`, user `halbil`, password `secret`, database `halbil`.

## Data Karyawan dari SharePoint Online (opsional)

Daftar karyawan bisa diambil langsung dari file Excel/CSV di SharePoint Online tanpa import manual.

1. **Azure AD – App registration**
   - Buat aplikasi di [Azure Portal](https://portal.azure.com) → Azure Active Directory → App registrations → New registration.
   - Catat **Application (client) ID** dan **Directory (tenant) ID**.
   - Certificates & secrets → New client secret → catat **Value** (client secret).

2. **API permissions**
   - App registration → API permissions → Add permission → Microsoft Graph → Application permissions.
   - Tambah: **Sites.Read.All** dan **Files.Read.All** (untuk OneDrive personal tambah **User.Read.All**).
   - Klik **Grant admin consent**.

3. **Lokasi file – pilih salah satu**

   **A) File di OneDrive personal** (link seperti `https://mitechcorp-my.sharepoint.com/.../personal/kezia_fitrari_mitech_co_id/...`)
   - Dari URL: host `...-my.sharepoint.com` dan path `personal/email_mitech_co_id` → pemilik file = `email@mitech.co.id`.
   - Nama file dari parameter `file=...` (mis. `Halal Bihalal Registration Form.xlsx`).
   - Isi `.env` dengan **MS_USER_UPN** (email pemilik) dan **MS_FILE_PATH** (nama file di root, atau path lengkap jika di dalam folder). **Jika path ada spasi, wajib pakai tanda petik:**
   ```env
   MS_USER_UPN=kezia_fitrari@mitech.co.id
   MS_FILE_PATH="/Halal Bihalal Registration Form.xlsx"
   ```
   - Jika file ada di dalam folder OneDrive, gunakan path lengkap, mis. `MS_FILE_PATH="/Documents/Halal Bihalal Registration Form.xlsx"`.

   **B) File di SharePoint team site / document library**
   - Bisa pakai [Graph Explorer](https://developer.microsoft.com/graph/graph-explorer): `GET https://graph.microsoft.com/v1.0/sites/{site-hostname}:/sites/{site-name}:/drives` untuk dapat `id` drive.
   - Path file relatif ke root drive: `/Karyawan.xlsx` atau `"/Shared Documents/Daftar Karyawan.csv"` (pakai petik jika ada spasi).
   - Isi **MS_DRIVE_ID** dan **MS_FILE_PATH** (jangan isi MS_USER_UPN).

4. **Contoh `.env` lengkap**
   ```env
   MS_TENANT_ID=...      # Directory (tenant) ID
   MS_CLIENT_ID=...      # Application (client) ID
   MS_CLIENT_SECRET=...  # Client secret value
   # Salah satu:
   MS_DRIVE_ID=...       # Untuk SharePoint team site
   MS_USER_UPN=...       # Untuk OneDrive personal (email pemilik file)
   MS_FILE_PATH="/Halal Bihalal Registration Form.xlsx"
   ```

Jika `MS_CLIENT_ID` dikosongkan, halaman Daftar Karyawan kembali memakai data dari database (import Excel seperti biasa).

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
