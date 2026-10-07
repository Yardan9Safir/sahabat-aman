Keterangan : 
- c(create) 
- r(read) 
- u(update) 
- d(delete)

Draft:

Completed :
- fixing Controller, dipecah perUsers
- fixing app.layouts & admin.layouts
- fixing routing & naming conventions
- Admin dapat CRUD Identitas siswa, guru, konselor, maupun admin itu sendiri.
- Adding New role = admin
- Pada Riwayat Chat ditambah nama yang menangani
- Satu Guru hanya bisa Menerima Curhat dari kelas yg ditentukan. Contoh: Anna Koiria, S.Psi hanya menerima kelas 7A, 7B, 7C, 7D, 7E, 7F.
    - Anna Koiria, S.Psi -> 7A, 7B, 7C, 7D, 7E, 7F
    - Annisa Uswatun Hasanah, S.Pd —> 7J, 8A, 8B, 8C, 8D, 8E
    - Haidarratur Rochma, S.Pd —> 7G, 8F, 8G, 8H, 8I, 8J
    - Erna Krisnawati, S.Pd —> 7H, 7I, 9A, 9B, 9C, 9D
    - Dian Miftahul Falah, S.Psi —> 9F, 9G, 9H, 9I, 9J, 9K
- Setiap Kelas Hanya Memiliki 2 KONSELOR SEBAYA. KONSELOR A untuk kelas 7A, Konselor B untuk kelas 7A, dst

Status Test Program :
- Siswa dapat membuat, menjawab, membalas chat
- Siswa dapat membuat, mengedit quotes
- Siswa dapat mengelola data diri. (Kecuali kelas, NIS)

- Konselor sebaya dapat membalas chat, dan menandai chat selesai
- Konselor sebaya membuat, mengedit quotes
- Konselor sebaya dapat mengelola data diri. (Kecuali kelas yg ditentukan)

- Guru dapat membalas chat, dan menandai chat selesai
- Guru dapat membuat, mengedit, menjadikan QOTD, dan menampilkan di halaman login untuk bagian quotes
- Guru dapat mengelola data diri. (Kecuali kelas yg ditentukan)

- Admin dapat mengedit data diri.
- Admin dapat menentukan kelas yg akan diampu oleh guru
- Admin dapat mengelola identitas siswa, guru, konselor sebaya, dan diri sendiri.

Failed : 0
Success : 12
