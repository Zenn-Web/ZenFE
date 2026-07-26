# Alpine.js Migration — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Migrasi Alpine.js secara bertahap ke portfolio Zen/Zenifen — mengaktifkan Alpine sebagai layer state UI tanpa mengganti Lenis, Bootstrap collapse, atau GSAP yang sudah berfungsi stabil.

**Architecture:** Alpine diload sebagai entry point terpisah (`alpine-init.js`) via Vite, di-bundle dan dipanggil di master layout `welcome.blade.php`. Tiap fase adalah satu branch git independen. Fase 1 & 2 mengganti kode vanilla JS dari `app.js` — kode lama dihapus *setelah* Alpine version diverifikasi berfungsi sama persis. Fase 3 opsional/deferred. Fase 4 cleanup akhir `app.js`.

**Tech Stack:** Laravel 11, Blade, Vite 7, Alpine.js 3.15.12 (exact pin — tanpa `^`), Bootstrap 5.3, GSAP 3.15, Lenis 1.3.

**Spec design:** `docs/superpowers/specs/2026-07-26-alpine-migration-design.md`

## Global Constraints

- Alpine versi **exact `3.15.12`** — pin di `package.json` tanpa `^` atau `~`.
- **Satu branch git per fase** — jangan gabung fase.
- **Jangan migrasikan:** Lenis smooth scroll, Bootstrap navbar collapse (`data-bs-toggle`), IntersectionObserver scroll-reveal, anchor-scroll.
- **Gate review manual wajib antara tiap fase** — lanjut ke fase berikutnya HANYA setelah user konfirmasi checklist manual oke.
- Validasi pakai checklist §6 spec design (bukan unit test suite). Pengecualian: task backend logic boleh pakai TDD.
- Tiap task punya kriteria "selesai" yang dapat diverifikasi secara konkret.
- **Backup `public/build` HARUS dilakukan sebelum Fase 0 dimulai** — ini task eksplisit, bukan catatan.

---

## Pre-Flight: Backup & Pin Versi (WAJIB, sebelum Fase 0)

**Files:**
- Modify: `package.json` (pin versi Alpine)

- [ ] **Step 1: Backup build output**

  ```bash
  # Di Windows PowerShell
  Copy-Item -Recurse public\build public\build.pre-alpine-backup
  ```
  Verifikasi: folder `public/build.pre-alpine-backup` ada dan berisi file CSS/JS.

- [ ] **Step 2: Pin versi Alpine exact di `package.json`**

  Ubah baris 16 dari:
  ```json
  "alpinejs": "^3.15.12",
  ```
  Menjadi:
  ```json
  "alpinejs": "3.15.12",
  ```
  Verifikasi: tidak ada prefix `^` atau `~` di baris alpinejs.

- [ ] **Step 3: Jalankan npm install untuk sync package-lock**

  ```bash
  npm install
  ```
  Expected: package-lock.json terupdate, tidak ada error.

---

## Fase 0 — Setup Alpine (branch: `migrate/alpine-setup`)

**Files:**
- Create: `resources/js/alpine-init.js`
- Modify: `vite.config.js` — tambah entry point
- Modify: `resources/views/layout/welcome.blade.php` — tambah ke `@vite([...])`

**Interfaces:**
- Produces: `window.Alpine` tersedia secara global di semua halaman yang extend `welcome.blade.php`. Alpine berjalan tapi belum ada elemen yang menggunakan directive (`x-data`, dll) — tidak ada perubahan visual sama sekali.

- [ ] **Step 1: Buat branch**

  ```bash
  git checkout -b migrate/alpine-setup
  ```

- [ ] **Step 2: Buat `resources/js/alpine-init.js`**

  Buat file baru dengan isi:
  ```js
  import Alpine from 'alpinejs';
  window.Alpine = Alpine;
  Alpine.start();
  ```

- [ ] **Step 3: Tambahkan entry point di `vite.config.js`**

  File saat ini (baris 6–14):
  ```js
  input: [
      'resources/css/app.css',
      'resources/js/app.js',
      'resources/sass/app.scss',
      'resources/sass/detail-project.scss',
      'resources/js/detail-animations.js'
  ],
  ```
  Ubah jadi:
  ```js
  input: [
      'resources/css/app.css',
      'resources/js/app.js',
      'resources/js/alpine-init.js',
      'resources/sass/app.scss',
      'resources/sass/detail-project.scss',
      'resources/js/detail-animations.js'
  ],
  ```

- [ ] **Step 4: Tambahkan `alpine-init.js` ke `@vite([...])` di `welcome.blade.php`**

  Ubah baris 23 dari:
  ```blade
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
  ```
  Jadi:
  ```blade
  @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/js/alpine-init.js'])
  ```
  > **PENTING:** Jangan tambahkan `alpine-init.js` ke `@vite([...])` di `project/show.blade.php` — file itu hanya untuk asset page-specific (`detail-project.scss`). Alpine sudah ter-cover lewat master layout.

- [ ] **Step 5: Build dan verifikasi**

  ```bash
  npm run build
  ```
  Expected output: `✓ built in X.XXs` — bundle baru `alpine-init-XXXXXXXX.js` muncul di `public/build/assets/`.

- [ ] **Step 6: Verifikasi manual di browser**

  Buka `http://127.0.0.1:8000`, buka DevTools Console.
  Jalankan: `window.Alpine` — harus return Alpine object (bukan `undefined`).
  **Tidak ada perubahan visual apapun** di halaman — ini murni instalasi.
  Tidak ada JS error baru di console.

- [ ] **Step 7: Checklist selesai Fase 0**

  - [ ] `npm run build` sukses tanpa warning/error baru
  - [ ] Console nol error JS baru
  - [ ] `window.Alpine` tersedia di browser console
  - [ ] Semua fitur lama masih berfungsi (theme toggle, lang toggle, smooth scroll, navbar)
  - [ ] Test di mobile viewport — navbar collapse masih berfungsi

- [ ] **Step 8: Commit dan tunggu gate review user**

  ```bash
  git add resources/js/alpine-init.js vite.config.js resources/views/layout/welcome.blade.php package.json package-lock.json
  git commit -m "Alpine: setup awal, belum dipakai di elemen manapun"
  ```
  **STOP — tunggu konfirmasi user sebelum lanjut ke Fase 1.**

---

## Fase 1 — Theme Toggle (branch: `migrate/alpine-theme-toggle`)

**Files:**
- Modify: `resources/views/layout/navbar.blade.php` — ganti `#theme-toggle` button
- Modify: `resources/js/app.js` — hapus baris 233–275 (theme toggle logic)

**Interfaces:**
- Consumes: `window.Alpine` dari Fase 0
- Produces: `#theme-toggle` button dikontrol Alpine, menyimpan ke `localStorage('theme')`, set `data-theme` di `<html>`, dan menjalankan transisi `html.theme-switching` yang sama seperti implementasi lama.

> **Penting sebelum mulai:** Baca dulu baris 237–253 `app.js` (IIFE `theme-force-style`). Ini inject `<style id="theme-force-style">` yang mengontrol transisi smooth saat theme switching. Logic ini harus **dipertahankan** di Alpine `x-init`, bukan dihapus begitu saja.

- [ ] **Step 1: Buat branch**

  ```bash
  git checkout main
  git checkout -b migrate/alpine-theme-toggle
  ```

- [ ] **Step 2: Temukan elemen `#theme-toggle` di navbar**

  Buka `resources/views/layout/navbar.blade.php`, cari elemen dengan `id="theme-toggle"`.
  Catat markup lengkap beserta child element-nya (icon, label, dll) — ini akan dipertahankan, hanya container button yang ditambahi directive Alpine.

- [ ] **Step 3: Ganti button theme-toggle dengan versi Alpine**

  Ganti button `#theme-toggle` yang ada dengan versi berikut. Pertahankan semua child element (icon, label) dan class CSS yang sudah ada — hanya tambahi attribute Alpine:

  ```html
  <button
      id="theme-toggle"
      x-data="{
          theme: localStorage.getItem('theme') || 'light',
          toggle() {
              if (!document.getElementById('theme-force-style')) {
                  const style = document.createElement('style');
                  style.id = 'theme-force-style';
                  style.textContent = 'html.theme-switching, html.theme-switching *, html.theme-switching *::before, html.theme-switching *::after { transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, opacity 0.3s ease, box-shadow 0.3s ease !important; transition-delay: 0s !important; animation: none !important; animation-delay: 0s !important; }';
                  document.head.appendChild(style);
              }
              document.documentElement.classList.add('theme-switching');
              document.documentElement.offsetHeight;
              this.theme = this.theme === 'light' ? 'dark' : 'light';
              document.documentElement.setAttribute('data-theme', this.theme);
              localStorage.setItem('theme', this.theme);
              setTimeout(() => document.documentElement.classList.remove('theme-switching'), 300);
          }
      }"
      x-init="document.documentElement.setAttribute('data-theme', theme)"
      @click="toggle()"
      {{-- pertahankan class yang sudah ada, contoh: --}}
      class="..."
  >
      {{-- pertahankan semua child element (icon, dsb) yang sudah ada --}}
  </button>
  ```
  > **Catatan:** Isi `class="..."` dan child element diisi dari markup asli yang ditemukan di Step 2 — jangan dihapus.

- [ ] **Step 4: Hapus theme toggle logic lama dari `app.js`**

  Hapus baris 233–275 dari `app.js`:
  - Baris 233: `// 6. THEME TOGGLE (Dark Mode) - Optimized`
  - Sampai baris 275: penutup `}` dari `if (themeToggle) { ... }`

  Baris 276 (`// 7. AUTO-EXPAND TEXTAREA`) dan seterusnya **tidak disentuh**.

  > **Verifikasi sebelum hapus:** Pastikan tidak ada referensi `themeToggle` atau `theme-force-style` di bagian lain `app.js` selain blok 233–275. Gunakan Ctrl+F di editor.

- [ ] **Step 5: Build**

  ```bash
  npm run build
  ```
  Expected: `✓ built in X.XXs` tanpa error.

- [ ] **Step 6: Verifikasi manual di browser**

  Buka `http://127.0.0.1:8000`.
  - Klik theme-toggle → tema berganti (dark ↔ light) dengan transisi smooth.
  - Refresh halaman → tema yang terakhir dipilih tetap tersimpan (tidak flash ke light).
  - Buka DevTools Console → nol JS error.
  - Test di mobile viewport → tombol toggle tetap berfungsi.

- [ ] **Step 7: Checklist selesai Fase 1**

  - [ ] `npm run build` sukses tanpa warning/error baru
  - [ ] Console nol error JS baru
  - [ ] Dark/light toggle berfungsi dengan transisi smooth (300ms)
  - [ ] Tema tersimpan setelah refresh (localStorage)
  - [ ] Tidak ada FOUC (flash of unstyled content) saat load pertama
  - [ ] Semua fitur lain (lang toggle, smooth scroll, navbar collapse) masih berfungsi
  - [ ] Test di mobile viewport

- [ ] **Step 8: Commit dan tunggu gate review user**

  ```bash
  git add resources/views/layout/navbar.blade.php resources/js/app.js
  git commit -m "Alpine: migrasi theme toggle dari vanilla JS"
  ```
  **STOP — tunggu konfirmasi user + biarkan jalan beberapa hari sebelum lanjut ke Fase 2.**

---

## Fase 2 — Lang Toggle (branch: `migrate/alpine-lang-toggle`)

**Files:**
- Modify: `resources/js/alpine-init.js` — tambah Alpine store untuk i18n
- Modify: `resources/views/layout/navbar.blade.php` — ganti binding `#lang-toggle`
- Modify: `resources/js/app.js` — hapus baris 294–336 (lang toggle + applyLanguage)

**Interfaces:**
- Consumes: `window.Alpine` dan `Alpine.start()` dari Fase 0; `data-i18n-id` dan `data-i18n-en` attributes yang sudah ada di Blade views (hasil kerja i18n sebelumnya — tidak diubah)
- Produces: `Alpine.store('lang')` dengan method `toggle()` tersedia global. Button `#lang-toggle` memanggil `$store.lang.toggle()`. Perilaku scroll position tidak berubah saat toggle (fitur kritis).

> **Catatan kritis Fase 2:** Kode lama (`applyLanguage`) memanggil `lenis.resize()` setelah ganti bahasa (baris 313–315) untuk mencegah Lenis kehilangan sync scroll. Alpine store HARUS mereplikasi perilaku ini — lihat Step 3.

- [ ] **Step 1: Buat branch**

  ```bash
  git checkout main
  git checkout -b migrate/alpine-lang-toggle
  ```

- [ ] **Step 2: Tambahkan Alpine store ke `alpine-init.js`**

  Ubah `resources/js/alpine-init.js` dari:
  ```js
  import Alpine from 'alpinejs';
  window.Alpine = Alpine;
  Alpine.start();
  ```
  Menjadi:
  ```js
  import Alpine from 'alpinejs';
  window.Alpine = Alpine;

  Alpine.store('lang', {
      current: document.documentElement.getAttribute('lang') || 'id',
      toggle() {
          const newLang = this.current === 'id' ? 'en' : 'id';
          this.current = newLang;

          // Ganti teks semua elemen bertranslasi (tidak sentuh DOM structure)
          document.querySelectorAll('[data-i18n-id]').forEach(el => {
              const attr = newLang === 'en' ? 'data-i18n-en' : 'data-i18n-id';
              const val = el.getAttribute(attr);
              if (val !== null) {
                  el.innerHTML = val;
              }
          });

          // Update lang attribute di <html>
          document.documentElement.setAttribute('lang', newLang);

          // Update label tombol toggle (ID ↔ EN)
          const langLabel = document.querySelector('#lang-toggle .lang-label');
          if (langLabel) {
              langLabel.textContent = newLang === 'en' ? 'ID' : 'EN';
          }

          // Paksa Lenis resize untuk re-sync scroll position setelah konten berubah
          // (mencegah scroll position bergeser — fitur kritis dari implementasi lama)
          if (window.lenis && typeof window.lenis.resize === 'function') {
              window.lenis.resize();
          }

          // Sync ke server (fire-and-forget, tidak menunggu response)
          const token = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
          fetch(`/lang/${newLang}`, {
              method: 'POST',
              headers: {
                  'X-CSRF-TOKEN': token,
                  'Accept': 'application/json'
              }
          }).catch(() => {});
      }
  });

  Alpine.start();
  ```

  > **Catatan `window.lenis`:** Lenis saat ini di-inisialisasi di dalam `DOMContentLoaded` di `app.js` sebagai variabel lokal. Supaya Alpine store bisa akses Lenis, expose dulu: di `app.js` baris 7 setelah `const lenis = new Lenis({...})`, tambahkan `window.lenis = lenis;`. Ini dilakukan di Step 3.

- [ ] **Step 3: Expose Lenis ke `window` di `app.js`**

  Di `app.js`, setelah baris inisialisasi Lenis (sekitar baris 17, setelah penutup `});` dari `new Lenis({...})`), tambahkan:
  ```js
  window.lenis = lenis;
  ```
  Ini satu-satunya modifikasi `app.js` di step ini — jangan sentuh bagian lain.

- [ ] **Step 4: Ganti binding `#lang-toggle` di navbar**

  Buka `resources/views/layout/navbar.blade.php`, temukan button dengan `id="lang-toggle"`.
  Tambahkan `@click="$store.lang.toggle()"` ke button tersebut.
  Hapus atribut event listener inline yang mungkin sudah ada (tapi jangan hapus class, id, atau child element).

  Contoh hasil akhir (child element tetap dipertahankan dari markup asli):
  ```html
  <button id="lang-toggle" @click="$store.lang.toggle()" class="...">
      {{-- child element asli tetap ada --}}
      <span class="lang-label">{{ app()->getLocale() === 'en' ? 'ID' : 'EN' }}</span>
  </button>
  ```

- [ ] **Step 5: Hapus lang toggle logic lama dari `app.js`**

  Hapus baris 294–336 dari `app.js`:
  - Baris 294: `// 8. HYBRID NO-RELOAD I18N TOGGLE ENGINE`
  - Sampai baris 336: penutup `}` dari `if (langToggle) { ... }`
  - Baris 337 (`});`) dan 338 (akhir file) **tidak disentuh**.

  > **Verifikasi sebelum hapus:** Pastikan tidak ada referensi `applyLanguage`, `currentLang`, atau `langToggle` di bagian lain `app.js` selain blok 294–336.

- [ ] **Step 6: Build**

  ```bash
  npm run build
  ```
  Expected: `✓ built in X.XXs` tanpa error.

- [ ] **Step 7: Verifikasi manual di browser — KHUSUSNYA scroll position**

  Buka `http://127.0.0.1:8000`.
  - Scroll ke bawah (section Projects atau Contact).
  - Klik lang-toggle (ID ↔ EN).
  - **Verifikasi UTAMA: posisi scroll TIDAK bergeser sama sekali saat toggle.**
  - Teks berubah bahasa dengan benar di semua elemen `data-i18n-*`.
  - Label tombol toggle berubah (ID ↔ EN).
  - Refresh halaman → bahasa tetap sesuai yang terakhir dipilih (via server session).
  - DevTools Console → nol JS error.

- [ ] **Step 8: Checklist selesai Fase 2**

  - [ ] `npm run build` sukses tanpa warning/error baru
  - [ ] Console nol error JS baru
  - [ ] Lang toggle berfungsi, teks berubah di semua elemen bertranslasi
  - [ ] **Scroll position tidak bergeser saat toggle** (test wajib, ulangi 3x)
  - [ ] Label tombol berubah (ID ↔ EN)
  - [ ] Theme toggle (Fase 1) masih berfungsi
  - [ ] Smooth scroll Lenis masih berfungsi
  - [ ] Test di mobile viewport

- [ ] **Step 9: Commit dan tunggu gate review user**

  ```bash
  git add resources/js/alpine-init.js resources/views/layout/navbar.blade.php resources/js/app.js
  git commit -m "Alpine: migrasi lang toggle, pertahankan perilaku no-reload"
  ```
  **STOP — tunggu konfirmasi user + verifikasi stabil sebelum lanjut ke Fase 3/4.**

---

## Fase 3 — Contact Form UX [OPSIONAL / DEFERRED]

> **Status: OPSIONAL** — Bukan blocker untuk Fase 4. Lanjutkan ke Fase 4 tanpa menunggu Fase 3 selesai. Kerjakan Fase 3 hanya jika user memutuskan untuk memasukkannya.

**Files:**
- Modify: `resources/views/pages/contact.blade.php` (atau Blade view yang berisi form kontak)
- Modify: `resources/js/app.js` — hapus baris 277–292 (textarea auto-resize)

**Goal:** Tambah state loading/sukses visual pada form kontak menggunakan Alpine. Textarea auto-resize dimigrasikan dari JS ke Alpine `x-on:input`. Form tetap submit normal ke server (bukan AJAX).

- [ ] **Step 1: Buat branch**

  ```bash
  git checkout main
  git checkout -b migrate/alpine-contact-form
  ```

- [ ] **Step 2: Temukan form kontak di Blade**

  Buka view yang berisi `<form>` kontak (cek `resources/views/pages/contact.blade.php` atau `resources/views/pages/home.blade.php` jika halaman kontak adalah section).

- [ ] **Step 3: Tambahkan Alpine state ke form**

  Tambahkan `x-data` ke elemen `<form>`:
  ```html
  <form
      x-data="{ loading: false }"
      @submit="loading = true"
      method="POST"
      action="{{ route('contact.store') }}"
  >
      @csrf
      {{-- semua field form yang sudah ada tetap tidak diubah --}}
      <button type="submit" :disabled="loading" x-text="loading ? 'Mengirim...' : 'Kirim Pesan'">
          Kirim Pesan
      </button>
  </form>
  ```
  > **PENTING:** `method="POST"` tetap, tidak diubah ke AJAX. Hanya tambah state visual loading.

- [ ] **Step 4: Migrasikan textarea auto-resize ke Alpine**

  Pada setiap `<textarea>` di form, ganti dengan versi Alpine:
  ```html
  <textarea
      x-data
      x-init="$el.style.height = $el.scrollHeight > 0 ? $el.scrollHeight + 'px' : 'auto'"
      @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
      {{-- pertahankan name, class, placeholder, dan attribute lain yang sudah ada --}}
  ></textarea>
  ```

- [ ] **Step 5: Hapus textarea auto-resize dari `app.js`**

  Hapus baris 277–292:
  - Baris 277: `// 7. AUTO-EXPAND TEXTAREA (Pesan)`
  - Sampai baris 292: penutup `});` dari `textarea.addEventListener('input', ...)`

- [ ] **Step 6: Build dan verifikasi manual**

  ```bash
  npm run build
  ```
  - Form kontak masih submit dengan benar ke server.
  - Button berubah jadi "Mengirim..." dan disabled saat submit.
  - Textarea auto-resize berfungsi saat mengetik.
  - Console nol error.

- [ ] **Step 7: Commit**

  ```bash
  git add resources/views/ resources/js/app.js
  git commit -m "Alpine: contact form UX — loading state + textarea auto-resize"
  ```

---

## Fase 4 — Cleanup Akhir `app.js` (branch: `migrate/alpine-cleanup`)

> **Prasyarat:** Fase 1 dan Fase 2 sudah merge ke `main` dan stabil minimal beberapa hari tanpa bug report. Fase 3 boleh belum selesai.

**Files:**
- Modify: `resources/js/app.js` — verifikasi tidak ada dead code sisa migrasi

**Goal:** Pastikan `app.js` hanya berisi kode yang memang sengaja tidak dimigrasikan: Lenis setup, navbar height updater, anchor-scroll, live clock, IntersectionObserver, dan Bootstrap navbar collapse listener.

- [ ] **Step 1: Buat branch**

  ```bash
  git checkout main
  git checkout -b migrate/alpine-cleanup
  ```

- [ ] **Step 2: Audit `app.js` untuk dead code**

  Baca seluruh `app.js`. Verifikasi bahwa tidak ada lagi:
  - Referensi `themeToggle`, `theme-force-style`, atau `theme-switching` (sudah di Fase 1)
  - Referensi `langToggle`, `applyLanguage`, `currentLang`, `data-i18n` (sudah di Fase 2)
  - Referensi textarea auto-resize (jika Fase 3 dikerjakan)

  Jika ada sisa kode yang terlewat dari fase sebelumnya, hapus sekarang.

- [ ] **Step 3: Tambahkan komentar header di `app.js`**

  Di baris 1 `app.js`, tambahkan komentar blok:
  ```js
  /**
   * app.js — Vanilla JS core (non-Alpine)
   * Sengaja tidak dimigrasikan ke Alpine:
   * - Lenis smooth scroll (library eksternal)
   * - Navbar height updater (util kecil, tanpa state UI)
   * - Anchor-scroll (terikat erat ke Lenis)
   * - Live clock (opsional, bisa dimigrasikan di masa depan)
   * - IntersectionObserver scroll-reveal (risiko re-timing animasi)
   * - Bootstrap navbar collapse listener (sudah stabil, ganti = risiko regresi)
   */
  ```

- [ ] **Step 4: Build final**

  ```bash
  npm run build
  ```
  Expected: `✓ built in X.XXs` tanpa error.

- [ ] **Step 5: Verifikasi regresi komprehensif**

  - [ ] `npm run build` sukses
  - [ ] Console nol error JS baru
  - [ ] Theme toggle berfungsi (dark/light, persistent)
  - [ ] Lang toggle berfungsi (scroll position tidak bergeser)
  - [ ] Smooth scroll Lenis berfungsi
  - [ ] Navbar collapse di mobile berfungsi
  - [ ] Anchor-scroll dari navbar links berfungsi
  - [ ] Live clock di footer berfungsi (jika ada di UI)
  - [ ] Scroll-reveal animations berfungsi (section muncul saat scroll)
  - [ ] Halaman detail project (`/project/company-website-eyegil`) styling `dp-*` masih ada
  - [ ] Test di mobile viewport (semua fitur di atas)

- [ ] **Step 6: Commit final**

  ```bash
  git add resources/js/app.js
  git commit -m "Alpine: cleanup akhir app.js, dokumentasi bagian yang sengaja tidak dimigrasikan"
  ```

---

## Rollback Reference (jika terjadi bug major)

### Sebelum merge ke main (hapus branch):
```bash
git checkout main
git branch -D migrate/alpine-theme-toggle   # ganti dengan nama branch yang bermasalah
```

### Setelah merge ke main (revert merge commit):
```bash
git log --oneline --merges -10              # temukan hash merge commit yang bermasalah
git revert -m 1 <hash-merge-commit>
npm run build
```

### Emergency stop-the-bleeding (production down):
```bash
# Restore files ke state sebelum Alpine dimulai (pakai hash commit pre-alpine)
git checkout 53e211b1 -- resources/js/ resources/views/
npm run build
# LALU lakukan git revert yang benar setelah situasi teratasi
```

### Bandingkan output build lama vs baru:
```bash
# Windows PowerShell
Compare-Object (Get-ChildItem public\build.pre-alpine-backup -Recurse) (Get-ChildItem public\build -Recurse)
```
