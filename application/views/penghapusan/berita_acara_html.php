<h2 style="text-align:center;">BERITA ACARA PENGHAPUSAN BARANG</h2>

<p>Pada hari ini, telah dilakukan penghapusan barang dengan data berikut:</p>

<table border="1" cellpadding="6" width="100%">
<tr><td>Kode Barang</td><td><?= $penghapusan->kode_barang ?></td></tr>
<tr><td>Nama Barang</td><td><?= $penghapusan->nama_barang ?></td></tr>
<tr><td>Jenis Penghapusan</td><td><?= strtoupper($penghapusan->jenis_penghapusan) ?></td></tr>
<tr><td>Tanggal</td><td><?= $penghapusan->tanggal_penghapusan ?></td></tr>
<tr><td>Keterangan</td><td><?= $penghapusan->keterangan ?></td></tr>
</table>

<br>
<a href="<?= base_url('penghapusan/cetak_pdf/'.$penghapusan->id_penghapusan) ?>" target="_blank">
    Cetak PDF
</a>