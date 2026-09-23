Keterangan : 
- c(create) 
- r(read) 
- u(update) 
- d(delete)

Draft: 

Completed : 
- Tema website ganti warna biru (cyan-400/sky-400), kuning (yellow-300/amber-300), Orange/peach (orange-400/rose-300) & warm (slate-50/stone-50)
- Role : siswa(crud), konselor sebaya(rud), guru bk(rud)
- Konselor sebaya dipilih 2 orang setiap kelas, bersifat anonim (contoh : konselor sebaya A & B)
- Siswa dapat membuka obrolan curhat
- Siswa dapat memilih antara konselor sebaya / guru bk.
- Bahasa siswa lain diubah menjadi konselor sebaya.
- Untuk guru/konselor sebaya nanti bisa filter mana chat yang belum di balas, on going, dan sudah diselesaikan.
- Login dalam 1 jam dibatasi 100 akun. jika tidak terdapat aktivitas selama 10 menit maka akan logout otomatis. 
- Tambahkan warning message jika server penuh
- Buat Tabel Quotes(id_quotes, id_pembuat(ambil dari id guru), judul_quotes, category_quotes , isi_quotes, created_at, updated_at). 
Guru dapat membuat dan memilih kata-kata hari ini lalu ditampilkan di homepage website (tampil di halaman login maupun di landing page). 
- Hanya Guru yang dapat CRUD "Quotes" ini ke template grid yang disesuaikan.