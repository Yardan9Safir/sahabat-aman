Keterangan : 
- c(create) 
- r(read) 
- u(update) 
- d(delete)

Draft:

Completed :
- Quotes Bisa diisi siswa, konselor sebaya, dan guru
  - Halaman global `/quotes` (bukan di dalam panel admin), semua role punya menu "Quotes & Kata-Kata"
  - Siswa & konselor sebaya: create + read, edit/hapus hanya quote miliknya sendiri
  - Guru: bisa create, read, update, delete semua quote
- Hanya Guru yang bisa mengatur tampilan di halaman login
  - Menu baru `/admin/tampilan-login` (middleware `guru`)
  - Pilih maksimal 3 quotes + atur urutannya, plus switch nyala/mati blok quotes
  - Kolom baru `quotes.tampil_di_login` + `quotes.urutan_login`, tabel `settings` untuk switch
  - Halaman login menampilkan quotes terpilih sebagai carousel; kalau guru belum memilih,
    jatuh ke quotes "kata-kata hari ini"
- Update Page Login + 3 Quotes di Halaman Login sesuai sahabat-aman\docs\list page\login page
- Update Homepage + 3 Quotes
