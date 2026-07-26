# Prompt: Kickoff Superpowers — Migrasi Alpine.js

Saya sudah punya desain migrasi yang lengkap untuk task ini, terlampir sebagai
`rencana-migrasi-alpinejs.md`. Ini sudah melalui proses eksplorasi konteks, evaluasi
pendekatan, dan draft desain di luar sesi ini — **treat file ini sebagai desain yang
sudah di-approve untuk fase brainstorming-mu**, bukan draft yang perlu dirumuskan ulang.

## Yang saya minta:

1. Simpan isi `rencana-migrasi-alpinejs.md` ke
   `docs/superpowers/specs/2026-07-26-alpine-migration-design.md` (sesuai konvensi
   lokasimu).
2. Lakukan spec self-review singkat (cek placeholder, kontradiksi, ambiguitas, scope)
   terhadap isi file itu — kalau ada temuan, laporkan ke saya dulu sebelum lanjut,
   **jangan diam-diam mengubah isi desainnya sendiri**.
3. **Jangan re-derive desain dari awal atau nanya ulang clarifying question** yang
   jawabannya sudah ada di file — prioritas dan pendekatan bertahap di dalamnya
   sudah final dari sisi saya.
4. Setelah self-review beres, lanjut langsung ke skill `writing-plans` untuk
   memecahnya jadi implementation plan.

## Constraint penting untuk plan yang dihasilkan:

- **Satu branch git per fase** (Fase 0 sampai Fase 4), persis seperti struktur di
  §3 file desain — jangan gabung beberapa fase jadi satu task/branch.
- **Validasi tiap task pakai checklist manual di §6 file desain** (build sukses,
  console nol error, fitur lama masih jalan, test khusus scroll-position untuk Fase 2)
  — **BUKAN** red/green unit test suite. ini kerjaan mayoritas Blade markup +
  Alpine directive + CSS, bukan logic backend yang punya failing-test natural.
  Kalau maksa pola TDD strict di sini, tolong stop dan diskusikan dulu ke saya
  sebelum lanjut, jangan dipaksakan bikin test palsu cuma buat lolos gate TDD.
  Pengecualian: kalau ada task yang genuinely backend logic (misalnya ubah
  `LanguageController`), TDD normal silakan diterapkan di situ.
- **Gate review wajib di antara tiap fase** — jangan lanjut ke fase berikutnya
  sebelum fase sekarang saya konfirmasi sudah oke lewat checklist manual, walau
  agent-nya "merasa" sudah selesai.
- **Langkah mitigasi risiko di §5 file desain** (backup `public/build`, pin versi
  Alpine exact, prosedur rollback) masukkan sebagai task eksplisit di plan, bukan
  cuma catatan yang gampang kelewat.

## Catatan konteks tambahan:

Project ini sudah melalui beberapa putaran refactor sebelumnya (i18n, modularisasi
SCSS) yang sempat ada task-task kecil kelewat tanpa ketahuan sampai di-audit ulang
manual. Tolong ekstra hati-hati soal ini di plan kali ini — pastikan tiap task di
plan yang dihasilkan punya kriteria "selesai" yang bisa diverifikasi konkret
(bukan cuma "sudah dikerjakan"), sesuai checklist yang sudah ada di file desain.
