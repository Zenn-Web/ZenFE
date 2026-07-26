# Rencana Migrasi ke Alpine.js — Zen Portfolio

> Disusun berdasarkan audit langsung ke codebase terkini (commit `53e211b1` + staged
> cleanup). Status fondasi: i18n hybrid no-reload ✅, modularisasi SCSS ✅,
> `Detailproject.scss` lama sudah bersih ✅. Project dalam kondisi stabil, aman untuk
> mulai migrasi bertahap.

---

## 1. Inventory `app.js` Saat Ini (337 baris)

| Baris | Fungsi | Kandidat migrasi? |
|---|---|---|
| 7–29 | Setup Lenis (smooth scroll) | ❌ Tetap vanilla — library eksternal, di luar scope Alpine |
| 30 | Update tinggi navbar saat resize | ❌ Tetap vanilla — util kecil, tidak ada state UI |
| 83–104 | Smooth-scroll anchor click + `preventDefault` | ❌ Tetap vanilla — terikat erat ke Lenis |
| 140–158 | Live clock | ✅ Kandidat ringan, opsional |
| 159–208 | `IntersectionObserver` untuk scroll-reveal animation | ⚠️ Opsional, risiko re-timing — lihat catatan Fase 5 |
| 209–225 | Bootstrap navbar collapse show/hide listener | ❌ Tetap pakai Bootstrap JS — sudah teruji, ganti = risiko regresi tanpa benefit besar |
| 234–256 | Theme toggle (dark/light) | ✅ **Prioritas 1** — state UI sederhana, cocok sekali untuk Alpine |
| 286–296 | Textarea auto-resize (form contact) | ✅ Kandidat, digabung ke Fase 4 |
| 307–337 | Lang toggle (`data-i18n-id`/`data-i18n-en`, fetch background) | ✅ **Prioritas 2** — sudah pakai pola data-attribute yang cocok Alpine |

---

## 2. Prinsip Migrasi

- **Alpine hidup berdampingan dengan kode lama**, bukan big-bang rewrite. Tiap fase
  independen dan bisa di-rollback sendiri tanpa mempengaruhi fase lain.
- **Jangan migrasikan Lenis, navbar collapse Bootstrap, atau anchor-scroll** — itu di
  luar use-case Alpine dan mengganti mereka cuma menambah risiko tanpa manfaat nyata.
- **Satu fase = satu branch = satu commit** — supaya kalau ada bug, jelas persis
  fase mana yang jadi penyebabnya.

---

## 3. Fase Implementasi

### Fase 0 — Setup

```bash
git checkout -b migrate/alpine-setup
npm install alpinejs@3.14.9   # kunci versi EXACT, jangan pakai ^ — lihat §5
```

**[NEW]** `resources/js/alpine-init.js`:
```js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
```

**[MODIFY]** `vite.config.js` — tambahkan ke `input` array.
**[MODIFY]** `resources/views/layout/welcome.blade.php` — tambahkan ke `@vite([...])`.

Verifikasi: `npm run build`, buka halaman, pastikan tidak ada JS error di console
(Alpine belum dipakai di elemen manapun, jadi tidak ada perubahan visual sama sekali
di fase ini — ini murni "instalasi").

```bash
git add -A && git commit -m "Alpine: setup awal, belum dipakai di elemen manapun"
```

---

### Fase 1 — Theme Toggle (Prioritas 1, risiko rendah)

```bash
git checkout -b migrate/alpine-theme-toggle
```

Ganti binding manual (`document.getElementById('theme-toggle')` + `addEventListener`)
jadi:
```html
<button x-data="{ theme: localStorage.getItem('theme') ?? 'light' }"
        x-init="$watch('theme', v => { document.documentElement.setAttribute('data-theme', v); localStorage.setItem('theme', v) }); document.documentElement.setAttribute('data-theme', theme)"
        @click="theme = theme === 'light' ? 'dark' : 'light'"
        id="theme-toggle">
    ...
</button>
```

Hapus logic theme toggle lama (baris 234–256) dari `app.js` **setelah** dikonfirmasi
versi Alpine jalan sama persis (termasuk deteksi `theme-force-style` yang ada di kode
lama — cek dulu apa fungsinya sebelum dihapus, jangan asumsi tidak penting).

**Verifikasi:** toggle dark/light berfungsi, refresh halaman tema tetap tersimpan,
tidak ada flash of unstyled theme (FOUC) saat load pertama.

```bash
git commit -m "Alpine: migrasi theme toggle dari vanilla JS"
```

---

### Fase 2 — Lang Toggle (Prioritas 2)

```bash
git checkout -b migrate/alpine-lang-toggle
```

Karena elemen sudah punya `data-i18n-id`/`data-i18n-en` (hasil kerja sebelumnya), Alpine
tinggal baca attribute yang sudah ada — **tidak perlu ubah satupun Blade view**:

```js
// alpine-init.js, tambahkan store
Alpine.store('lang', {
  current: document.documentElement.getAttribute('lang') || 'id',
  toggle() {
    const newLang = this.current === 'id' ? 'en' : 'id';
    this.current = newLang;
    document.documentElement.setAttribute('lang', newLang);
    document.querySelectorAll('[data-i18n-id]').forEach(el => {
      el.innerHTML = el.getAttribute(newLang === 'en' ? 'data-i18n-en' : 'data-i18n-id');
    });
    const token = document.querySelector('meta[name="csrf-token"]').content;
    fetch(`/lang/${newLang}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': token } }).catch(() => {});
  }
});
```
Tombol toggle: `@click="$store.lang.toggle()"`.

Hapus logic lang toggle lama (baris 307–337) dari `app.js` setelah verifikasi.

**Verifikasi — WAJIB ulangi test scroll-position** (ini fix yang paling rawan regresi
kalau migrasi tidak hati-hati): buka halaman, scroll ke section Projects/Contact,
klik toggle bahasa, **pastikan posisi scroll tidak berubah sama sekali**.

```bash
git commit -m "Alpine: migrasi lang toggle, pertahankan perilaku no-reload"
```

---

### Fase 3 — Contact Form UX (opsional, effort sedang)

```bash
git checkout -b migrate/alpine-contact-form
```

Textarea auto-resize (baris 286) + tambahan state loading/success yang belum ada
sebelumnya:
```html
<form x-data="{ loading: false }" @submit="loading = true" method="POST" action="{{ route('contact.store') }}">
  ...
  <button type="submit" :disabled="loading" x-text="loading ? 'Mengirim...' : 'Kirim Pesan'"></button>
</form>
```
Form tetap submit normal ke server (tidak diubah jadi AJAX) — cukup tambah state
visual, jangan ubah mekanisme submit yang sudah berjalan.

---

### Fase 4 — Cleanup akhir

Setelah Fase 1–3 stabil di production minimal beberapa hari tanpa bug, `app.js`
akhirnya cuma tersisa: setup Lenis, update tinggi navbar, anchor-scroll, clock,
`IntersectionObserver`, dan navbar collapse listener — semuanya yang memang
sengaja TIDAK dimigrasikan (lihat §1).

```bash
git commit -m "Alpine: cleanup akhir app.js, hapus semua kode yang sudah tergantikan"
```

---

## 4. Bagian yang SENGAJA Tidak Dimigrasikan

| Bagian | Alasan |
|---|---|
| Lenis smooth scroll | Library eksternal level-rendah, di luar cakupan Alpine |
| `IntersectionObserver` scroll-reveal | Migrasi ke `@alpinejs/intersect` mungkin, tapi berisiko re-timing animasi yang sudah halus — tidak sepadan risikonya untuk portfolio kecil |
| Bootstrap navbar collapse | Sudah teruji stabil, mengganti ke Alpine `x-show`/`x-transition` cuma menambah risiko regresi mobile-nav tanpa manfaat berarti |

---

## 5. Mitigasi Risiko & Rencana Rollback (kalau terjadi bug major)

### 5.1 Pencegahan sebelum mulai

```bash
# Backup build output SEBELUM mulai migrasi apapun
cp -r public/build public/build.pre-alpine-backup

# Kunci versi Alpine EXACT di package.json (bukan ^3.14.9), supaya
# rebuild di kemudian hari tidak diam-diam kena breaking change minor/patch
```
Cek `package.json` setelah install — pastikan tertulis `"alpinejs": "3.14.9"` tanpa
prefix `^` atau `~`.

### 5.2 Disiplin branch & commit

- **Satu fase = satu branch terpisah dari `main`.** Jangan pernah kerja migrasi
  langsung di `main`.
- **Merge ke `main` cuma setelah fase itu diverifikasi manual di browser**, bukan
  cuma "build sukses tanpa error".
- **Time-box tiap fase.** Kalau satu fase makan waktu debug jauh lebih lama dari
  estimasi atau muncul bug yang tidak jelas akar masalahnya, itu sinyal untuk
  **revert fase itu dan re-evaluasi pendekatan** — jangan dipaksakan terus.

### 5.3 Kalau bug major ketahuan SEBELUM merge ke main

```bash
# Paling aman: buang saja branch fase yang bermasalah
git checkout main
git branch -D migrate/alpine-theme-toggle   # ganti sesuai nama branch fase yang gagal
```

### 5.4 Kalau bug major ketahuan SETELAH merge ke main (production kena)

```bash
# Cari commit merge yang jadi biang masalah
git log --oneline --merges -10

# Revert merge commit itu TANPA menghapus history (aman, bisa di-redo nanti)
git revert -m 1 <hash-commit-merge>

# Build ulang dari state yang sudah di-revert
npm run build
```

Kalau butuh super cepat tanpa nunggu revert penuh (situasi darurat, production down):
```bash
# Restore file spesifik ke state sebelum migrasi, tanpa revert history penuh
git checkout <hash-commit-sebelum-alpine-dimulai> -- resources/js/ resources/views/
npm run build
```
Setelah situasi darurat teratasi, tetap lakukan `git revert` yang benar (langkah di
atas) supaya history tetap bersih — checkout manual ini cuma untuk stop-the-bleeding
sementara.

### 5.5 Kalau perlu bandingkan output build lama vs baru untuk debug

```bash
diff -rq public/build.pre-alpine-backup public/build
```
Ini bantu isolasi apakah masalahnya di CSS, JS, atau keduanya, tanpa harus baca
seluruh kode.

### 5.6 Checklist verifikasi manual tiap fase (jangan skip)

- [ ] `npm run build` sukses tanpa warning/error baru
- [ ] Buka Devtools Console — nol error JS baru yang muncul
- [ ] Fitur yang dimigrasi di fase ini berfungsi sama seperti sebelumnya
- [ ] Fitur-fitur LAIN yang tidak disentuh fase ini masih berfungsi normal
  (regresi tidak langsung sering muncul di sini)
- [ ] Test di mobile viewport (khususnya kalau menyentuh navbar/theme toggle)
- [ ] Kalau menyentuh lang toggle: scroll position tidak berubah saat toggle

---

## 6. Urutan Kerja Disarankan

1. Fase 0 (setup) → merge
2. Fase 1 (theme toggle) → verifikasi manual → biarkan jalan beberapa hari → merge
3. Fase 2 (lang toggle) → verifikasi manual ketat (scroll position) → merge
4. Fase 3 (contact form) → opsional, bisa ditunda
5. Fase 4 (cleanup) → hanya setelah Fase 1–3 stabil tanpa laporan bug
