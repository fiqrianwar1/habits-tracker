<div align="center">
  <br>
  <h1>Habits Tracker + Gamification 🏆</h1>
  <p>
    <strong>Aplikasi pelacak kebiasaan (Habit Tracker) berbasis gamifikasi untuk meningkatkan produktivitas harian.</strong>
  </p>
</div>

---

## 📖 Deskripsi Singkat

**Habits Tracker** adalah aplikasi berbasis web yang membantu pengguna melacak kebiasaan harian dan tugas-tugas penting dengan pendekatan *gamifikasi*. Setiap tugas yang diselesaikan akan memberikan **Experience Points (XP)** kepada pengguna, yang memungkinkan mereka untuk naik level dan mengumpulkan **Badge** (Lencana) pencapaian. Aplikasi ini dirancang agar pengguna lebih termotivasi dalam mencapai target harian mereka secara konsisten.

---

## 🌟 Daftar Fitur

- **Manajemen Kebiasaan & Tugas:** Tambah, edit, dan tandai kebiasaan atau tugas harian yang sudah selesai.
- **Sistem Gamifikasi (XP & Leveling):** Dapatkan poin XP untuk setiap aktivitas positif dan saksikan level Anda meningkat.
- **Koleksi Badge (Lencana):** Buka berbagai badge pencapaian (misal: *7 Day Streak*, *Task Master*) sebagai bentuk apresiasi atas konsistensi.
- **Target Kategori:** Tetapkan target khusus untuk kategori tertentu (Kesehatan, Pekerjaan, Pribadi, dll).
- **Dashboard & Statistik Interaktif:** Pantau perkembangan kebiasaan Anda melalui grafik dan ringkasan yang menarik.
- **🤖 AI Chatbot Widget:** Asisten AI cerdas bawaan yang siap memberikan motivasi, saran kebiasaan, dan bantuan kapan saja.

---

## 📸 Tangkapan Layar (Screenshots)

*(Tolong ganti/upload gambar screenshot di bawah ini di folder repository kamu biar tampil di sini!)*

### 1. Halaman Dashboard & Gamifikasi
![Dashboard Screenshot](./screenshot-dashboard.png)
*Menampilkan statistik mingguan, progress XP, level pengguna, dan target kategori.*

### 2. AI Chatbot Widget
![Chatbot Screenshot](./screenshot-chatbot.png)
*Widget asisten cerdas yang memberikan motivasi dan saran harian.*

---

## 🛠 Tumpukan Teknologi (Tech Stack)

- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend:** Laravel Livewire 3 & Alpine.js
- **Styling:** Tailwind CSS
- **Database:** SQLite (Bawaan) / MySQL
- **Testing:** PHPUnit & Playwright (E2E)

---

## 🚀 Langkah Instalasi

Ikuti langkah-langkah berikut untuk menjalankan aplikasi ini secara lokal di mesin Anda.

1. **Clone repository ini**
   ```bash
   git clone https://github.com/USERNAME/habits.git
   cd habits
   ```

2. **Install dependensi PHP (Composer)**
   ```bash
   composer install
   ```

3. **Install dependensi Frontend (NPM) & Build Assets**
   ```bash
   npm install
   npm run build
   ```

4. **Siapkan berkas Environment**
   Salin `.env.example` menjadi `.env`.
   ```bash
   cp .env.example .env
   ```

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Konfigurasi Database**
   Secara default, aplikasi menggunakan SQLite. Anda tidak perlu mengubah banyak hal, cukup jalankan migrasi. (Pastikan berkas `database/database.sqlite` ada, atau akan dibuat otomatis oleh Laravel).
   
7. **Jalankan Migrasi & Database Seeder (Opsional untuk data dummy)**
   ```bash
   php artisan migrate --seed
   ```

8. **Jalankan Development Server**
   ```bash
   php artisan serve
   ```
   *Buka `http://localhost:8000` di browser Anda.*

---

## 🧪 Cara Menjalankan Test

### PHPUnit (Unit & Feature Tests)
Untuk menjalankan tes backend bawaan Laravel:
```bash
php artisan test
```

### Playwright (End-to-End Tests)
Aplikasi ini mendukung E2E testing menggunakan Playwright untuk mensimulasikan interaksi pengguna di browser.
1. Pastikan dependensi Playwright sudah terinstal:
   ```bash
   npm init playwright@latest
   ```
2. Jalankan pengujian Playwright:
   ```bash
   npx playwright test
   ```
3. Untuk melihat laporan hasil tes Playwright secara visual:
   ```bash
   npx playwright show-report
   ```

---

## 👨‍💻 Informasi Penulis

- **Nama:** [NAMA_KAMU_DISINI]
- **NIM:** [NIM_KAMU_DISINI]
- **Program Studi:** [PRODI_KAMU_DISINI]
- **Email Akademik:** [EMAIL_KAMU_DISINI]
