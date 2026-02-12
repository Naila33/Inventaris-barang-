<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<!-- Alerts & Actions -->
<?= $this->session->flashdata('message'); ?>
<div class="mb-3">
  <a href="<?= base_url('peminjaman') ?>" class="btn btn-primary">Semua Peminjaman</a>
  <a href="<?= base_url('peminjaman/terlambat') ?>" class="btn btn-warning">Peminjaman Terlambat</a>
</div>

<!-- Table List -->
<div class="table-responsive">
  <table id="datatable-aktif" class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Tanggal Pinjam</th>
        <th scope="col">Kode Barang</th>
        <th scope="col">Nama Barang</th>
        <th scope="col">Peminjam</th>
        <th scope="col">Batas Waktu</th>
        <th scope="col">Status</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($peminjaman)) : ?>
        <?php $i = 1; foreach ($peminjaman as $dt) : ?>
          <?php 
            $hari_ini = new DateTime();
            $batas_waktu = new DateTime($dt->batas_waktu);
            $selisih = $hari_ini->diff($batas_waktu);
            $is_terlambat = $hari_ini > $batas_waktu;
          ?>
          <tr class="<?= $is_terlambat ? 'table-warning' : '' ?>">
            <th scope="row"><?= $i++; ?></th>
            <td><?= date('d F Y', strtotime($dt->tanggal_pinjam)) ?></td>
            <td><?= htmlspecialchars($dt->kode_barang ?? '') ?></td>
            <td><?= htmlspecialchars($dt->nama_barang ?? '') ?></td>
            <td><?= htmlspecialchars($dt->nama_peminjam ?? '') ?></td>
            <td>
              <?= date('d F Y', strtotime($dt->batas_waktu)) ?>
              <?php if ($is_terlambat): ?>
                <br><small class="text-danger">Terlambat <?= $selisih->days ?> hari</small>
              <?php else: ?>
                <br><small class="text-info"><?= $selisih->days ?> hari lagi</small>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($dt->status == 'Dipinjam'): ?>
                <span class="badge badge-primary">Dipinjam</span>
              <?php elseif ($dt->status == 'Terlambat'): ?>
                <span class="badge badge-danger">Terlambat</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="<?= base_url('peminjaman/kembalikan/' . $dt->id_peminjaman) ?>" 
                 class="badge badge-success" 
                 onclick="return confirm('Yakin ingin mengembalikan barang ini?')">Kembalikan</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr>
          <td colspan="8" class="text-center">Tidak ada peminjaman aktif.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</div>
