# Aplikasi Rekap Absensi

Saya membuat aplikasi ini untuk memudahkan admin tata usaha dalam menyusun rekapitulasi absensi para guru dan pegawai. Aplikasi ini dibuat menggunakan Vue.js sebagai *User Interfacenya* dan untuk bagian *backend*-nya menggunakan PHP. Data absensi diambil dari mesin *fingerprint* merek solution melalui perantara koneksi *socket* dengan koneksi kabel LAN yang dihubungkan menggunakan kabel UTP.

## Penjelasan

API (*Application Programming Interface*) terdapat dalam folder api. API ini dibuat menggunakan dengan bahasa pemrograman PHP native. Untuk databasenya menggunakan SQL dan struktur *table*-nya terdapat pada file database.sql.
Aplikasi ini juga memiliki fitur untuk export data menjadi file excel untuk lebih memudahkan dalam mencetak laporan.

# Penggunaan

## Project setup
```
npm install
```

### Compiles dan hot-reloads untuk keperluan development
```
npm run serve
```

### Compiles dan minifies untuk keperluan production
```
npm run build
```

### Lints dan perbaikan pada file
```
npm run lint
```

### Kustomisasi konfigurasi
Lihat [Referensi Kustomisasi](https://cli.vuejs.org/config/).
