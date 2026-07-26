# Rencana Modernisasi Arsitektur & Refactor Codebase — Zen Portfolio

> Dokumen ini disusun berdasarkan audit langsung terhadap isi codebase (bukan asumsi),
> menggunakan snapshot project yang terverifikasi akurat: Laravel 12 + Blade + Vite +
> vanilla JS + SCSS + Bootstrap 5. Tidak ada React di codebase ini.

---

## 1. Kondisi Saat Ini (Verified Stack)

| Layer | Teknologi | Catatan |
|---|---|---|
| Backend | Laravel 12 | Eloquent + migration + seeder untuk `projects` |
| View | Blade | Server-rendered, tidak ada SPA/island framework aktif |
| Styling | Bootstrap 5.3.8 + SCSS custom | 5 file SCSS (`app.scss`, `app2.scss`, `Detailproject.scss`, `_about_redesign.scss`, `_variables.scss`) |
| Build | Vite 7 | `laravel-vite-plugin`, banyak entry point terpisah |
| Interaktivitas | Vanilla JS (`app.js`, `projectranslate.js`, `detail-animations.js`) | Lenis (smooth scroll), GSAP (animasi), toggle tema, i18n manual |
| i18n | Custom (`data-i18n` attribute + JS dictionary) | Bukan Laravel localization resmi |
| Terpasang tapi tidak dipakai | Tailwind CSS 4 | Terverifikasi 0 pemakaian syntax Tailwind asli di seluruh `.blade.php` — hanya `d-flex`/`gap-*` milik Bootstrap |

---

## 2. Temuan & Masalah Arsitektur

### 2.1 Sistem i18n manual, tersebar, dan rentan desync

- Translation project (title, category, description) disimpan sebagai *key-value pair* di
  dalam `resources/js/app.js` (`const translations = {...}`, mulai baris 301), dengan key
  dinamis berbasis slug: `project.title.<slug>`, `project.category.<slug>`, dst.
- Key ini digenerate otomatis di Blade lewat `data-i18n="project.title.{{ $project->slug }}"`
  — tapi isi terjemahannya **hidup terpisah** dari data project itu sendiri (yang ada di
  database). Menambah project baru lewat seeder/admin **tidak otomatis** menambah versi
  Inggrisnya — harus diingat manual untuk diedit di `app.js`.
- Ada **mesin i18n kedua** yang duplikatif: `resources/js/projectranslate.js` (44 baris)
  meng-implementasi ulang pola yang sama persis (snapshot teks asli → ganti saat toggle)
  hanya untuk 4 key statis di halaman detail project, alih-alih menyatu dengan sistem di
  `app.js`.
- Total **38 atribut `data-i18n`** tersebar di 4 file Blade (`navbar.blade.php` ×5,
  `footer.blade.php` ×6, `home.blade.php` ×19, `project/show.blade.php` ×8) — semuanya
  bergantung pada dictionary JS yang terpisah dari sumber konten aslinya.

**Dampak:** Setiap penambahan konten baru berisiko diam-diam rusak di versi Inggris,
dan sinkronisasi konten jadi kerja manual yang mudah lupa.

### 2.2 Config sensitif & dead code di Controller

- `ContactController::store()` — alamat email tujuan (`zenifenagusti70@gmail.com`)
  hardcoded langsung di kode, bukan di `.env`/config.
- `Mail::raw()` dikirim **synchronous** (blocking request) — kalau SMTP lambat/down,
  form contact ikut lambat/gagal tanpa retry. Tidak ada rate-limiting/anti-spam.
- `ResearchController::fetchNews()` — fetch berita forex/XAUUSD dari API contoh
  (`https://example.com/api/news`), **tidak direferensikan** di `routes/web.php`
  atau file manapun (terkonfirmasi lewat grep). Dead code, tidak relevan dengan portfolio.

### 2.3 SCSS terduplikasi dan warna hardcoded

- `_variables.scss` sudah punya sistem CSS variable yang cukup baik (light/dark theme
  lewat `:root` dan `[data-theme="dark"]`) — **tapi tidak dipakai konsisten**.
- `app2.scss` (1876 baris) berisi **104 hex color hardcoded**, termasuk **`#0fb9a7`
  sebanyak 19 kali** — padahal warna ini sudah didefinisikan sebagai
  `--accent-emerald` di `_variables.scss`. Ganti warna brand nanti = harus buru manual
  19+ lokasi.
- Pola penamaan `app.scss` + `app2.scss` ("app versi 2" ditumpuk di samping, bukan
  diintegrasikan) adalah tanda technical debt yang sama seperti pola duplikasi
  translation di atas.
- Akibat nyata dari pola ini: class `.profile-card-horizontal` **didefinisikan di dua
  file sekaligus** (`app.scss` dan `app2.scss`) — styling-nya split, hasil akhir
  tergantung urutan load di `@vite()`, bukan dikendalikan lewat satu Sass import tree.
- Sebagai perbandingan, `Detailproject.scss` dan `_about_redesign.scss` relatif bersih
  (0 hex hardcoded, konsisten pakai `var(--...)`) — jadi masalahnya terkonsentrasi di
  `app2.scss`.

### 2.4 Sistem styling ganda (Tailwind terpasang, tidak terpakai)

- `@tailwindcss/vite` dan `tailwindcss` ada di `devDependencies`, dan
  `resources/css/app.css` meng-`@import 'tailwindcss'` serta ikut di-bundle Vite.
- Terverifikasi: **tidak ada satupun** syntax Tailwind asli (arbitrary value seperti
  `w-[10px]`) dipakai di `.blade.php` manapun. Class yang tampak mirip (`d-flex`,
  `gap-3`) adalah utility milik Bootstrap 5, bukan Tailwind.
- Ini murni menambah ukuran bundle & waktu build tanpa manfaat.

### 2.5 Struktur entry point Vite non-standar

- `vite.config.js` memuat **7 entry point terpisah** langsung sebagai input Laravel
  Vite plugin: `app.css`, `app.js`, `app2.scss`, `app.scss`, `Detailproject.scss`,
  `projectranslate.js`, `detail-animations.js`.
- Pola yang lebih standar: satu file SCSS utama yang `@import` semua partial di
  dalamnya (sudah ada precedent-nya: `_variables.scss` dan `_about_redesign.scss`
  sudah pakai underscore prefix, tanda "partial" Sass — tapi tidak semua file
  konsisten diperlakukan sebagai partial).
- Efek: urutan cascade CSS jadi bergantung pada urutan array di `@vite()`, bukan pada
  struktur `@import` yang eksplisit dan mudah dilacak.

---

## 3. Rencana Modernisasi (Bertahap, Bukan Big-Bang Rewrite)

Prinsip: portfolio ini skala kecil-menengah, jadi prioritasnya **konsolidasi dan
kebersihan struktur**, bukan migrasi ke stack yang jauh lebih kompleks tanpa kebutuhan
nyata. Setiap tahap independen dan bisa di-commit terpisah.

### Tahap 1 — Konten & i18n (sudah didelegasikan sebelumnya, sedang berjalan)
- Satukan translation project ke kolom database (`title_en`, `category_en`,
  `description_en`, `flow_description_en`).
- Pindahkan teks statis ke Laravel localization resmi (`lang/en/`, `lang/id/`).
- Hapus duplikasi antara `app.js` dan `projectranslate.js` — satu mekanisme switch
  bahasa saja.
- Hapus `ResearchController`, pindahkan email contact ke `.env`.

### Tahap 2 — Konsolidasi SCSS
- Audit `app2.scss`: ganti semua hex yang sudah punya padanan variable (terutama
  `#0fb9a7` → `var(--accent-emerald)`) menjadi pakai variable.
- Untuk hex berulang yang belum ada variable-nya, tambahkan sebagai variable baru di
  `_variables.scss` — jangan biarkan sebagai literal tersebar.
- Gabungkan `app.scss` + `app2.scss` jadi satu file utama dengan partial yang jelas
  (`@import`), hapus duplikasi selector `.profile-card-horizontal` setelah dicek mana
  definisi yang aktif dipakai.
- Sederhanakan entry point di `vite.config.js` jadi satu SCSS utama + partial,
  bukan banyak file lepas.

### Tahap 3 — Bersih-bersih dependency
- Hapus `@tailwindcss/vite` dan `tailwindcss` dari `package.json`, hapus
  `resources/css/app.css` dan referensinya di `vite.config.js` (sudah confirmed
  tidak dipakai).
- Audit `axios` di `devDependencies` — cek apakah benar-benar dipakai; kalau tidak,
  hapus juga.

### Tahap 4 — Opsional, evaluasi kebutuhan ke depan
Ini **bukan** rekomendasi langsung, tapi opsi yang layak dipertimbangkan tergantung
rencana kamu:

- **Kalau cuma butuh interaktivitas kecil tanpa build framework berat** (misalnya
  state sederhana untuk toggle/animasi tanpa reinvent vanilla JS terus-menerus):
  pertimbangkan **Alpine.js** — ringan, langsung nempel di Blade, tidak perlu restrukturisasi
  besar.
- **Kalau ke depan mau scale jadi lebih interaktif** (dashboard admin, form dinamis
  kompleks): baru pertimbangkan **Laravel + Inertia.js + React/Vue** — tapi ini
  keputusan arsitektur besar terpisah, evaluasi lagi saat kebutuhannya benar-benar ada.
- **Kalau mau kelola konten tanpa buka kode** (tambah project, ubah teks) tanpa perlu
  redeploy: pertimbangkan **Filament** sebagai admin panel di atas Eloquent yang sudah ada.

---

## 4. Ringkasan Prioritas

| Prioritas | Tahap | Risiko | Effort |
|---|---|---|---|
| Tinggi | Tahap 1 (i18n + dead code) | Sedang | Sedang (sedang berjalan) |
| Tinggi | Tahap 2 (SCSS) | Rendah | Kecil–sedang |
| Sedang | Tahap 3 (dependency cleanup) | Rendah | Kecil |
| Opsional | Tahap 4 (Alpine/Inertia/Filament) | Tergantung pilihan | Besar |

Kerjakan Tahap 1–3 dulu sampai selesai dan stabil sebelum mempertimbangkan Tahap 4 —
itu perubahan arsitektur besar yang butuh keputusan terpisah, bukan kelanjutan otomatis
dari refactor ini.
