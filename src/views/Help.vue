<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12 pt-3">
        <h1><center>-=: Aplikasi Absensi :=-</center></h1>
        <h3 class="pt-3">Fitur dan Fungsi</h3>
        <h4 class="pt-2">- Absensi Individu</h4>
        <p class="text-justify">
          Absensi individu - untuk menampilkan data absensi berdasarkan nama yang telah ditentukan pada pilihan <b>Nama</b> berdasarkan rentang tanggal yang telah ditentukan pada pengaturan <b>Dari Tanggal</b> dan <b>Hingga Tanggal</b>.
        </p>
        <h4 class="pt-2">- Rekap Absensi</h4>
        <p class="text-justify">
          Rekap Absensi - untuk menampilkan seluruh data absensi berdasarkan bulan pada menu pilihan <b>Bulan</b> dan tahun pada menu pilihan <b>Tahun</b> yang telah ditentukan.
        </p>
        <h4 class="pt-2">- Sinkron Data</h4>
        <p class="text-justify">
          Sinkron Data - untuk mengambil data yang terdapat pada mesin <i>fingerprint</i> dan memperbarui data yang terdapat pada aplikasi absensi.
        </p>
        <h3 class="pt-3">FAQ (Frequently Asked Question)</h3>
        <h4 class="pt-2">Gagal sinkronisasi data pada menu <b>Sinkron Data</b>?</h4>
        <p class="text-justify">
          Pastikan kabel LAN andan terhubung dengan mesin <i>fingerprint</i> dan cek alamat IP komputer anda apakah sudah berada pada subnet yang sama dengan mesin <i>fingerprint</i>. Alamat mesin adalah 192.168.1.201.
        </p>
        <h4 class="pt-2">Tidak dapat menampilkan data?</h4>
        <p class="text-justify">
          Pastikan anda sudah menentukan nama, mulai tanggal, hingga tanggal atau anda telah memilih bulan dan tahun yang diinginkan. Cek tanggal mulai agar tidak lebih maju dari tanggal akhir.
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  
}
</script>
