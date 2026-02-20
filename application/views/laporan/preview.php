<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Preview Laporan</h1>
    <div>
      <button onclick="window.print()" class="btn btn-secondary">
        <i class="fas fa-print"></i> Print
      </button>
      <form method="POST" action="<?= base_url('laporan/export_pdf') ?>" style="display: inline;">
        <input type="hidden" name="jenis_laporan" value="<?= $jenis_laporan ?>">
        <input type="hidden" name="tanggal_awal" value="<?= $tanggal_awal ?>">
        <input type="hidden" name="tanggal_akhir" value="<?= $tanggal_akhir ?>">
        <button type="submit" class="btn btn-danger">
          <i class="fas fa-file-pdf"></i> Export PDF
        </button>
      </form>
      <a href="<?= base_url('laporan') ?>" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
    </div>
  </div>

  <!-- Info Laporan -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Informasi Laporan</h6>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-3">
          <strong>Jenis Laporan:</strong><br>
          <span class="badge badge-primary">
            <?php 
            switch($jenis_laporan) {
              case 'inventaris': echo 'Daftar Inventaris'; break;
              case 'barang_masuk': echo 'Barang Masuk'; break;
              case 'barang_keluar': echo 'Barang Keluar'; break;
              case 'peminjaman': echo 'Peminjaman'; break;
              case 'kondisi': echo 'Kondisi Barang'; break;
              case 'penghapusan': echo 'Penghapusan'; break;
              default: echo 'Tidak Diketahui';
            }
            ?>
          </span>
        </div>
        <div class="col-md-3">
          <strong>Tanggal Cetak:</strong><br>
          <?= date('d/m/Y H:i:s') ?>
        </div>
        <div class="col-md-3">
          <strong>Periode:</strong><br>
          <?php if($tanggal_awal && $tanggal_akhir): ?>
            <?= date('d/m/Y', strtotime($tanggal_awal)) ?> - <?= date('d/m/Y', strtotime($tanggal_akhir)) ?>
          <?php else: ?>
            Semua Data
          <?php endif; ?>
        </div>
        <div class="col-md-3">
          <strong>Total Data:</strong><br>
          <?= count($laporan_data) ?> Record
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel Data -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Data Laporan</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <?php switch($jenis_laporan): 
          case 'inventaris': ?>
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Kode Barang</th>
                  <th>Nama Barang</th>
                  <th>Kategori</th>
                  <th>Lokasi</th>
                  <th>Kondisi</th>
                  <th>Harga</th>
                  <th>Tanggal Perolehan</th>
                </tr>
              </thead>
              <tbody>
                <?php $no=1; foreach($laporan_data as $item): ?>
                <tr>
                  <td><?= $no ?></td>
                  <td><?= $item['kode_barang'] ?></td>
                  <td><?= $item['nama_barang'] ?></td>
                  <td><?= $item['nama_kategori'] ?? '-' ?></td>
                  <td><?= $item['nama_lokasi'] ?? '-' ?></td>
                  <td><?= $item['kondisi'] ?? 'Baik' ?></td>
                  <td>Rp <?= number_format($item['harga_perolehan'] ?? 0, 0, ',', '.') ?></td>
                  <td><?= date('d/m/Y', strtotime($item['tanggal_perolehan'])) ?></td>
                </tr>
                <?php $no++; endforeach; ?>
              </tbody>
            </table>

          <?php break; case 'barang_masuk': ?>
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal Masuk</th>
                  <th>Sumber Barang</th>
                  <th>Jumlah</th>
                  <th>Dokumen</th>
                </tr>
              </thead>
              <tbody>
                <?php $no=1; foreach($laporan_data as $item): ?>
                <tr>
                  <td><?= $no ?></td>
                  <td><?= date('d/m/Y', strtotime($item['tgl_masuk'])) ?></td>
                  <td><?= $item['sumberbarang'] ?></td>
                  <td><?= $item['jumlah'] ?></td>
                  <td><?= $item['dokumen_pendukung'] ?? '-' ?></td>
                </tr>
                <?php $no++; endforeach; ?>
              </tbody>
            </table>

          <?php break; case 'barang_keluar': ?>
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal Keluar</th>
                  <th>Kode Barang</th>
                  <th>Nama Barang</th>
                  <th>Jenis Transaksi</th>
                  <th>Tujuan</th>
                  <th>Penanggung Jawab</th>
                  <th>Jumlah</th>
                </tr>
              </thead>
              <tbody>
                <?php $no=1; foreach($laporan_data as $item): ?>
                <tr>
                  <td><?= $no ?></td>
                  <td><?= date('d/m/Y', strtotime($item['tgl_keluar'])) ?></td>
                  <td><?= $item['kode_barang'] ?? '-' ?></td>
                  <td><?= $item['nama_barang'] ?? '-' ?></td>
                  <td><?= $item['jenis_tras'] ?></td>
                  <td><?= $item['tujuan'] ?></td>
                  <td><?= $item['pj'] ?></td>
                  <td><?= $item['jumlah'] ?></td>
                </tr>
                <?php $no++; endforeach; ?>
              </tbody>
            </table>

          <?php break; case 'peminjaman': ?>
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal Pinjam</th>
                  <th>Kode Barang</th>
                  <th>Nama Barang</th>
                  <th>Peminjam</th>
                  <th>Tujuan</th>
                  <th>Jumlah</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php $no=1; foreach($laporan_data as $item): ?>
                <tr>
                  <td><?= $no ?></td>
                  <td><?= date('d/m/Y', strtotime($item['tgl_keluar'])) ?></td>
                  <td><?= $item['kode_barang'] ?? '-' ?></td>
                  <td><?= $item['nama_barang'] ?? '-' ?></td>
                  <td><?= $item['pj'] ?></td>
                  <td><?= $item['tujuan'] ?></td>
                  <td><?= $item['jumlah'] ?></td>
                  <td><?= $item['status_keterlambatan'] ?></td>
                </tr>
                <?php $no++; endforeach; ?>
              </tbody>
            </table>

          <?php break; case 'kondisi': ?>
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Kode Barang</th>
                  <th>Nama Barang</th>
                  <th>Kategori</th>
                  <th>Kondisi</th>
                  <th>Spesifikasi</th>
                </tr>
              </thead>
              <tbody>
                <?php $no=1; foreach($laporan_data as $item): ?>
                <tr>
                  <td><?= $no ?></td>
                  <td><?= $item['kode_barang'] ?></td>
                  <td><?= $item['nama_barang'] ?></td>
                  <td><?= $item['nama_kategori'] ?? '-' ?></td>
                  <td><?= $item['kondisi'] ?? 'Baik' ?></td>
                  <td><?= $item['spesifikasi'] ?? '-' ?></td>
                </tr>
                <?php $no++; endforeach; ?>
              </tbody>
            </table>

          <?php break; case 'penghapusan': ?>
            <table class="table table-bordered" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal Penghapusan</th>
                  <th>Kode Barang</th>
                  <th>Nama Barang</th>
                  <th>Jenis Penghapusan</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>
                <?php $no=1; foreach($laporan_data as $item): ?>
                <tr>
                  <td><?= $no ?></td>
                  <td><?= date('d/m/Y', strtotime($item['tanggal_penghapusan'])) ?></td>
                  <td><?= $item['kode_barang'] ?? '-' ?></td>
                  <td><?= $item['nama_barang'] ?? '-' ?></td>
                  <td><?= $item['jenis_penghapusan'] ?></td>
                  <td><?= $item['keterangan'] ?? '-' ?></td>
                </tr>
                <?php $no++; endforeach; ?>
              </tbody>
            </table>

          <?php endswitch; ?>
        
        <?php if(empty($laporan_data)): ?>
          <div class="text-center py-4">
            <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
            <p class="text-muted">Tidak ada data untuk ditampilkan</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>

<style>
@media print {
  .d-sm-flex .btn, .d-sm-flex form {
    display: none !important;
  }
  .card {
    box-shadow: none !important;
    border: 1px solid #ddd !important;
  }
}
</style>
