<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<!-- Prodi: Alerts & Actions -->
<?= $this->session->flashdata('message'); ?>



<!-- Prodi: Table List -->
<div class="table-responsive">
  <table id="datatable-barangkeluar" class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Id Barang</th>
        <th scope="col">Tanggal keluar</th>
        <th scope="col">Jenis Transaksi</th>
        <th scope="col">Tujuan</th>
        <th scope="col">Penanggung jawab</th>
        <th scope="col">Jumlah</th>
        <th scope="col">Batas waktu pengembalian</th>
        <th scope="col">Tanggal kembali</th>
        <th scope="col">Status keterlambatan</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($barangkeluar)) : ?>
        <?php $i = 1; foreach ($barangkeluar as $bk) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td><?= htmlspecialchars($bk['id_barang'] ?? ''); ?></td>
            <td><?= htmlspecialchars($bk['tgl_keluar'] ?? ''); ?></td>
            <td><?= htmlspecialchars($bk['jenis_tras'] ?? ''); ?></td>
            <td><?= htmlspecialchars($bk['tujuan'] ?? ''); ?></td>
            <td><?= htmlspecialchars($bk['pj'] ?? ''); ?></td>
            <td><?= htmlspecialchars($bk['jumlah'] ?? ''); ?></td>
            <td><?= htmlspecialchars($bk['batas_wp'] ?? ''); ?></td>
            <td><?= htmlspecialchars($bk['tgl_kembali'] ?? ''); ?></td>
            <td><?= htmlspecialchars($bk['status_keterlambatan'] ?? ''); ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr>
          <td colspan="10" class="text-center">Belum ada data barang keluar.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
  </div>

