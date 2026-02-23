<!-- Begin Page Content -->
    <div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <!-- Filter -->
    <form method="get" class="form-inline mb-3">
        <select name="jenis" class="form-control mr-2">
            <option value="">-- Semua --</option>
            <option value="rusak">Rusak Berat</option>
            <option value="hilang">Hilang</option>
            <option value="kadaluarsa">Kadaluarsa</option>
        </select>
        <button type="submit" class="btn btn-secondary">Filter</button>
    </form>

    <!-- Table -->
<div class="table-responsive">
    <table id="tablePenghapusan" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Jenis Penghapusan</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($penghapusan_barang as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $p->nama_barang ?></td>
                            <td><?= $p->jenis_penghapusan ?></td>
                            <td><?= $p->tanggal_penghapusan ?></td>
                            <td><?= $p->keterangan ?></td>
                            <td>
                                <a href="<?= base_url('penghapusan/berita_acara/'.$p->id_penghapusan) ?>" 
                                   class="btn btn-sm btn-info">
                                   Berita Acara
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
</div>

</div>

<div class="modal fade" id="modalTambah">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="post" action="<?= base_url('penghapusan/simpan') ?>">

        <div class="modal-header">
          <h5 class="modal-title">Tambah Penghapusan</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">

          <div class="form-group">
            <label>Barang</label>
            <select name="id_barang" class="form-control" required>
              <?php foreach($this->db->get('databarang')->result() as $b): ?>
                <option value="<?= $b->id_barang ?>">
                  <?= $b->nama_barang ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Jenis Penghapusan</label>
            <select name="jenis_penghapusan" class="form-control" required>
              <option value="Rusak Berat">Rusak Berat</option>
              <option value="Hilang">Hilang</option>
              <option value="Kadaluarsa">Kadaluarsa</option>
            </select>
          </div>

          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal_penghapusan" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control"></textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>

      </form>

    </div>
  </div>
</div>
