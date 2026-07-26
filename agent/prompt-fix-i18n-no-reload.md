# Prompt: Perbaiki Frontend i18n — Hybrid No-Reload (Revisi Task 1)

## Konteks masalah

Implementasi Task 1 (i18n) saat ini pakai pendekatan server-side redirect:
`LanguageController::switch()` set session locale lalu `redirect()->back()`. Ini
menyebabkan **full page reload** setiap toggle bahasa diklik, yang bentrok dengan
Lenis (smooth-scroll library yang dipakai di `app.js`) — Lenis kehilangan state scroll-nya
setiap reload, sehingga halaman terlihat "reset ke atas" walau user tadinya sedang scroll
di section lain. Ini bug UX yang nyata, bukan soal selera.

**State saat ini yang perlu diketahui:**
- Backend infra sudah ada dan BENAR, jangan diubah strukturnya: middleware
  `app/Http/Middleware/SetLocale.php`, route `/lang/{locale}` di `routes/web.php`,
  `lang/en/portfolio.php` + `lang/id/portfolio.php`, kolom `title_en`/`category_en`/
  `description_en`/`flow_description_en` di tabel `projects`.
- Yang JADI MASALAH: seluruh Blade view (`resources/views/layout/navbar.blade.php`,
  `resources/views/layout/footer.blade.php`, `resources/views/pages/home.blade.php`,
  `resources/views/pages/project/show.blade.php`) **masih 100% pakai atribut
  `data-i18n="key"` lama**, belum ada satupun yang pakai helper `__()`.
- `resources/js/app.js` (baris ~295) **masih punya sistem i18n client-side lama**:
  `const translations = {...}`, `originalTexts`, `applyLanguage()`, `langToggle` — ini
  yang sekarang benar-benar jalan, bukan infra server-side yang sudah dibangun.
- `resources/js/projectranslate.js` sudah dikosongkan (tinggal 1 baris komentar) tapi
  filenya dan entry-nya di `vite.config.js` masih ada.

## Solusi: Hybrid — server tetap satu sumber kebenaran, TANPA reload

Prinsip: server me-render **kedua versi bahasa sekaligus** ke DOM lewat data attribute
saat halaman pertama kali dimuat. Toggle bahasa di klien cuma swap attribute mana yang
ditampilkan (instan, tanpa request apapun yang diblok), sementara update session ke
server dikirim di background tanpa redirect.

---

## Task A — Ubah `LanguageController` & route jadi non-redirect

### [MODIFY] `app/Http/Controllers/LanguageController.php`
Ganti method `switch()`:
- Terima locale dari parameter, validasi hanya `id`/`en` yang diterima.
- Set `session(['locale' => $locale])`.
- **Jangan** `redirect()->back()`. Return `response()->noContent()` (HTTP 204).

### [MODIFY] `routes/web.php`
Ubah route `/lang/{locale}` dari `Route::get` jadi `Route::post` (karena ini sekarang
dipanggil lewat `fetch()` di background, bukan navigasi link biasa):
```php
Route::post('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
```
Tambahkan `@csrf` handling — pastikan request `fetch()` di JS nanti menyertakan CSRF
token dari meta tag (`<meta name="csrf-token" content="{{ csrf_token() }}">`), tambahkan
meta tag ini di `<head>` layout utama kalau belum ada.

---

## Task B — Render dua versi bahasa sekaligus di setiap elemen `data-i18n`

Untuk KEEMPAT file Blade (`navbar.blade.php`, `footer.blade.php`, `home.blade.php`,
`project/show.blade.php`), ganti setiap:
```blade
<p data-i18n="hero.bio">{{ __('portfolio.hero_bio') }}</p>
```
menjadi:
```blade
<p class="hero-classic-bio ..."
   data-i18n-id="{{ __('portfolio.hero_bio', [], 'id') }}"
   data-i18n-en="{{ __('portfolio.hero_bio', [], 'en') }}">{{ __('portfolio.hero_bio') }}</p>
```
Pertahankan class HTML asli yang sudah ada (jangan hapus), cuma tambah dua data
attribute baru dan hapus attribute `data-i18n="..."` lama.

**Untuk loop project (title/category/description per project dari database)**, pakai
kolom EN yang sudah ada, bukan `__()`:
```blade
<h3 data-i18n-id="{{ $project->title }}" data-i18n-en="{{ $project->title_en }}">
    {{ $project->title }}
</h3>
```
Lakukan pola yang sama untuk `category`, `description`, `flow_description`.

Setelah semua elemen dikonversi, hapus SEMUA sisa attribute `data-i18n="..."` (bukan
`data-i18n-id`/`data-i18n-en`) — pastikan 0 hasil untuk `grep -r 'data-i18n="' resources/views/`.

---

## Task C — Rewrite mekanisme toggle di `app.js`

### [MODIFY] `resources/js/app.js`
1. **Hapus total**: `const translations = {...}`, `originalTexts`, `applyLanguage()`
   versi lama yang baca dari dictionary JS, dan semua logic yang query
   `[data-i18n]` (attribute ini sudah tidak dipakai lagi).
2. **Tambahkan** `applyLanguage(lang)` versi baru yang baca dari attribute yang sudah
   di-render server:
   ```js
   function applyLanguage(lang) {
     document.querySelectorAll('[data-i18n-id]').forEach(el => {
       const attr = lang === 'en' ? 'data-i18n-en' : 'data-i18n-id';
       el.innerHTML = el.getAttribute(attr);
     });
     document.documentElement.setAttribute('lang', lang);
     localStorage.setItem('preferred_lang', lang);
   }
   ```
3. **Ubah event listener toggle** — swap tampilan instan dulu, baru kirim update
   session ke server tanpa menunggu/blocking:
   ```js
   langToggle.addEventListener('click', () => {
     const newLang = currentLang === 'id' ? 'en' : 'id';
     applyLanguage(newLang);
     currentLang = newLang;

     const token = document.querySelector('meta[name="csrf-token"]').content;
     fetch(`/lang/${newLang}`, {
       method: 'POST',
       headers: { 'X-CSRF-TOKEN': token }
     }).catch(() => {}); // gagal diam-diam, UX tidak boleh terganggu
   });
   ```
4. **Setelah `applyLanguage()` dipanggil**, kalau ada instance Lenis yang bisa
   diakses di scope ini, panggil `lenis.resize()` (atau method resize yang setara) —
   supaya kalau tinggi konten berubah akibat panjang teks ID vs EN beda, batas
   scroll ikut ter-update. Cek dulu bagaimana instance Lenis disimpan di file ini
   sebelum menambahkan pemanggilan ini.

---

## Task D — Bersihkan file mati

### [DELETE] `resources/js/projectranslate.js`
Sudah kosong (1 baris komentar), tidak dipakai lagi dengan pendekatan baru ini.

### [MODIFY] `vite.config.js`
Hapus `'resources/js/projectranslate.js'` dari array input.

### [MODIFY] file Blade manapun yang masih memuatnya lewat `@vite([...])`
Hapus referensi `resources/js/projectranslate.js` dari pemanggilan `@vite()`.

---

## Verification Plan

```bash
npm run build
php artisan serve
```

- [ ] Buka homepage, scroll ke section Projects atau Contact.
- [ ] Klik toggle bahasa — **pastikan posisi scroll TIDAK berubah sama sekali**
      (ini kriteria sukses utama task ini).
- [ ] Cek teks berganti instan ID ⇄ EN tanpa flicker/reload.
- [ ] Reload halaman penuh (F5) setelah toggle ke EN — pastikan halaman tetap
      terbuka dalam bahasa EN (artinya session tersimpan dengan benar walau
      request-nya fire-and-forget).
- [ ] `grep -r 'data-i18n="' resources/views/` menghasilkan nol match.
- [ ] Buka halaman detail project, pastikan title/description ikut berganti bahasa
      dan datanya sesuai kolom `title_en`/`description_en` di database.
- [ ] Devtools Network tab: konfirmasi request ke `/lang/{locale}` terkirim sebagai
      POST tanpa menyebabkan navigasi/reload halaman.

### Commit Convention
```
git commit -m "Fix: i18n toggle jadi no-reload (hybrid server+client), hapus projectranslate.js"
```
