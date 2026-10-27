# BTNG Backend - Showroom Mobil & Test Drive RESTful API

Tugas Akhir Pelatihan Backend BTNG (Bina Talenta Nusantara Gemilang) menggunakan **Laravel 12**, **MySQL**, dan dokumentasi API interaktif dengan **Scramble**.

---

## 📌 Tautan Penting Pengumpulan

- **Live API Endpoint:** [`https://app-a6e6b05f-2f61-4350-b7d3-13329f47560c.cleverapps.io/api/mobil`](https://app-a6e6b05f-2f61-4350-b7d3-13329f47560c.cleverapps.io/api/mobil)
- **Live Scramble Interactive Docs:** [`https://app-a6e6b05f-2f61-4350-b7d3-13329f47560c.cleverapps.io/docs/api#/`](https://app-a6e6b05f-2f61-4350-b7d3-13329f47560c.cleverapps.io/docs/api#/)
- **Repository GitHub:** [`https://github.com/CUKLIZ/BTNG-Backend-Wiratama-Rava-Rahardian`](https://github.com/CUKLIZ/BTNG-Backend-Wiratama-Rava-Rahardian)

---

## 🚀 Fitur Utama & Arsitektur

### 1. Entitas Mobil (CRUD Lengkap - Nilai Utama):
- `GET /api/mobil` : Mengambil daftar seluruh unit mobil (dilengkapi fitur pencarian `?search=`, penyaringan kustom `?brand=`, `?status=`, dan paginasi `?page=&per_page=`).
- `GET /api/mobil/{id}` : Mengambil detail spesifikasi 1 mobil berdasarkan ID.
- `POST /api/mobil` : Menambahkan unit mobil baru dengan validasi data ketat.
- `PUT/PATCH /api/mobil/{id}` : Memperbarui data unit mobil yang tersimpan.
- `DELETE /api/mobil/{id}` : Menghapus unit mobil dari inventori.

### 2. Simulasi Kredit / Cicilan Mobil (Fitur Bisnis - Nilai Tambah ⭐):
- `POST /api/mobil/{id}/simulasi-kredit` : Menghitung simulasi cicilan bulanan mobil berdasarkan Uang Muka DP (%) dan tenor tahun (1 s/d 5 tahun).
- Otomatis menghitung: Nominal DP, Pokok Hutang, Suku Bunga Flat Tahunan (6% s/d 10%), Total Bunga, Total Pembayaran, dan Angsuran per Bulan.

### 3. Entitas Booking Test Drive (Relasi Database - Nilai Tambah ⭐):
- Menerapkan **Eloquent Relationship**: `Mobil hasMany TestDrive` & `TestDrive belongsTo Mobil`.
- Guest-friendly (calon pembeli tidak diwajibkan mendaftar akun untuk mengajukan uji coba mengemudi).
- Dilengkapi **aturan bisnis**: mobil berstatus `sold` (terjual) otomatis ditolak jika diajukan booking test drive.
- Normalisasi tanggal: mendukung format tanggal `YYYY-MM-DD` maupun ISO datetime string.
- `GET /api/test-drives` : Daftar seluruh permohonan test drive beserta rincian mobil terkait.
- `GET /api/test-drives/{id}` : Detail satu permohonan booking.
- `POST /api/test-drives` : Pengajuan permohonan booking test drive baru.
- `PATCH /api/test-drives/{id}/status` : Memperbarui status booking (`menunggu`, `disetujui`, `selesai`, `dibatalkan`).
- `DELETE /api/test-drives/{id}` : Membatalkan/menghapus permohonan.

### 4. Dashboard Ringkasan & Analitik Showroom (Agregasi Data - Nilai Tambah ⭐):
- `GET /api/dashboard/summary` : Menampilkan ringkasan total unit mobil, unit tersedia, unit terjual, total valuasi aset showroom (Rp), dan statistik status booking test drive.

### 5. Standarisasi Respons & Dokumentasi:
- Respons terbungkus rapi menggunakan **API Resource** (`JsonResource`) dengan amplop standar `message` dan `data`.
- HTTP Status Code semantik (`200 OK`, `201 Created`, `404 Not Found`, `422 Unprocessable Content`).
- Dokumentasi interaktif otomatis menggunakan **Scramble** di `/docs/api`.

---

## 🛠️ Panduan Instalasi & Menjalankan Lokal

### 1. Prasyarat Sistem
- PHP >= 8.2
- Composer >= 2.x
- MySQL Database

### 2. Langkah Setup

1. **Clone Repository:**
   ```bash
   git clone https://github.com/CUKLIZ/BTNG-Backend-Wiratama-Rava-Rahardian.git
   cd BTNG-Backend-Wiratama-Rava-Rahardian
   ```

2. **Install Dependensi Composer:**
   ```bash
   composer install
   ```

3. **Setup Environment (.env):**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database:**
   Sesuaikan parameter database di file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=TA
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Jalankan Migrasi & Database Seeder:**
   ```bash
   php artisan migrate --seed
   ```
   *(Atau `php artisan migrate:fresh --seed` jika ingin me-reset ulang seluruh data awal).*

6. **Jalankan Server Lokal:**
   ```bash
   php artisan serve
   ```
   Aplikasi akan berjalan di: `http://localhost:8000`

---

## 📖 Dokumentasi API Interaktif

Buka browser dan kunjungi tautan berikut untuk membaca skema parameter dan mencoba langsung (Try it out):  
👉 **`https://app-a6e6b05f-2f61-4350-b7d3-13329f47560c.cleverapps.io/docs/api#/`**

---

## 📡 Ringkasan Endpoint & Contoh Request

### 1. Simulasi Kredit Mobil
- **Method & URL:** `POST /api/mobil/{id}/simulasi-kredit`
- **Request Body:**
```json
{
  "uang_muka_persen": 20,
  "tenor_tahun": 3
}
```
- **Response (200 OK):**
```json
{
  "message": "Simulasi kredit mobil berhasil dihitung",
  "data": {
    "mobil": {
      "id": 3,
      "nama": "Pajero Sport Dakar",
      "brand": "Mitsubishi",
      "harga": 750000000
    },
    "simulasi": {
      "uang_muka_persen": 20,
      "uang_muka_nominal": 150000000,
      "pokok_hutang": 600000000,
      "tenor_tahun": 3,
      "tenor_bulan": 36,
      "bunga_tahunan_persen": 8,
      "total_bunga": 144000000,
      "total_pembayaran": 894000000,
      "angsuran_per_bulan": 20666667
    }
  }
}
```

---

### 2. Dashboard Showroom
- **Method & URL:** `GET /api/dashboard/summary`
- **Response (200 OK):**
```json
{
  "message": "Ringkasan data dashboard showroom berhasil diambil",
  "data": {
    "inventori_mobil": {
      "total_unit": 20,
      "tersedia": 16,
      "terjual": 4,
      "total_nilai_aset": 8500000000
    },
    "test_drive": {
      "total_booking": 3,
      "menunggu": 2,
      "disetujui": 1
    }
  }
}
```

---

### 3. Booking Test Drive
- **Method & URL:** `POST /api/test-drives`
- **Headers:** `Content-Type: application/json`, `Accept: application/json`
- **Request Body:**
```json
{
  "mobil_id": 1,
  "nama_customer": "Budi Santoso",
  "email": "budi@example.com",
  "no_telp": "081234567890",
  "tanggal": "2026-10-30",
  "catatan": "Ingin mencoba akselerasi dan kenyamanan suspensi."
}
```

- **Update Status:** `PATCH /api/test-drives/{id}/status`
```json
{
  "status": "disetujui"
}
```

---

### 4. Mobil (Search, Filter, Pagination)
- **Pencarian kata kunci:** `GET /api/mobil?search=Avanza`
- **Penyaringan merk & status:** `GET /api/mobil?brand=Toyota&status=tersedia`
- **Paginasi data:** `GET /api/mobil?page=1&per_page=5`
