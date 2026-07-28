# MWT Laravel Starter Kit

Starter kit ini adalah fondasi standar untuk semua aplikasi internal PT Mada Wikri Tunggal. Dibangun dengan Laravel 12 dan Tailwind CSS v4, repositori ini telah dikonfigurasi dengan standar UI/UX, Keamanan, dan format kode resmi perusahaan.

## Fitur Bawaan
1. **Tailwind CSS v4 & Alpine.js**: Bawaan desain MWT (Dark/Light mode, palet warna resmi).
2. **Blade Components**: Komponen UI standar (`x-button`, `x-input`, `x-card`, `x-alert`).
3. **SweetAlert2**: Notifikasi global bawaan via session flash (`success` & `error`).
4. **Error Pages**: Halaman error (403, 404, 500) yang interaktif.
5. **Keamanan**: `BaseModel` dengan `$guarded = []` untuk memaksa penggunaan Form Requests.
6. **Laravel Pint**: Konfigurasi `pint.json` siap pakai untuk standardisasi gaya penulisan kode.

## Panduan Instalasi (Untuk Developer)

```bash
# 1. Clone repositori ini dengan nama proyek Anda
git clone https://github.com/PT-MWT/starter-kit.git [nama-proyek]

# 2. Masuk ke direktori
cd [nama-proyek]

# 3. Hapus folder .git bawaan starter kit agar Anda bisa membuat repo baru
rm -rf .git  # (atau hapus manual folder .git)

# 4. Inisialisasi git baru
git init
git add .
git commit -m "Initial commit from MWT Starter Kit"

# 5. Install dependensi
composer install && npm install

# 6. Copy .env dan generate key
cp .env.example .env
php artisan key:generate

# 7. Jalankan server (Backend & Frontend sekaligus)
composer run dev
```

## Penggunaan Komponen Dasar

```html
<!-- Tombol -->
<x-button variant="primary" size="md">Simpan Data</x-button>

<!-- Input -->
<x-input type="text" name="username" placeholder="Masukkan Username" :error="$errors->has('username')" />

<!-- Card -->
<x-card>
    <x-slot name="header">
        <h3 class="font-bold">Judul Card</h3>
    </x-slot>
    
    Isi konten di sini...
</x-card>

<!-- Alert Bawaan UI -->
<x-alert type="success" title="Pemberitahuan">
    Data berhasil diperbarui.
</x-alert>
```

> **Catatan**: Jika menggunakan `return back()->with('success', 'Pesan');` di Controller, SweetAlert akan otomatis muncul tanpa memanggil `x-alert`.
