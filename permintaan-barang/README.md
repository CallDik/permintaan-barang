1. Siapkan server lokal
Pastikan sudah install xampp untuk php dan mysl
kemudian nyalakan APache dan MySQL dari control panelnya

2.Buka folder Project Permintaan_barang

composer install
kemudian download database yang ada di github kemudian dan simpan di dalam folder

selanjutnya cek .env untuk sesuai dengan MySQL dan database server setelah itu jalankan php spark serve

http://localhost:8000

id user : admin
password :password
 id karyawan : budi
 password : password


Setelah login sebagai admin dan akan masuk ke dashboard admin
yang pertama dilakukan oleh admin adalah di dashboard di edit karyawan yaitu membuat user karyawan baru ,saat ada karyawan baru setelah sudah di buat usernya karyawan sudah boleh di login

kemudian admin akan menambah stok barang  kantor ada tiga yang di simpan nama barang, jumlah stok, dahulu dan memberikan batas pengajuan supaya mengurangi kecurangan dalam penggunaan barang, atau membatasi meminta barang yang sama berkali kali dalam waktu singkat, kalau nilai diisi nol ,artinya barang tidak ada batas untuk pengajuan.

#PENGECEKAN PERMINTAAN
Admin akan mengecek permintaan dari hasil pengajuan dari karyawan untuk mevalidasi pengjuan jika permintaan sesuai, jika tidak admin akan menolaknya.

#KARYAWAN
Karyawan akan login sebagai karyawan
id: budi
password: password

Ketika sudah login karyawan akan masuk ke dashboard karyawan sendiri,

kemudian karyawan akan melakukan pengajuan permintaan barang ke admin, sebelum pengajuan sistem akan mengecek dahulu bahwa karyawan ini sudah pernah melakukan pengajuan belum dan menghitung kapan karyawan boleh mengajukan lagi, dan sistem akan otomatis mengecek stok untuk apakah jumlah yang diminta karyawan masih tersedia di stok, kalau jumlah yang diminta lebih besar dari stok yang ada, maka tidak akan bisa melakukan pengajuan.

jika sudah melakukan pengajuan karyawan bisa melihat ke dashboard status permintaan, disitu akan melihat status pengajuannya sudah di proses oleh admin atau belum.