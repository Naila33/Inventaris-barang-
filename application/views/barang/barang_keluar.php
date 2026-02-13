<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<!-- Prodi: Alerts & Actions -->
<?= $this->session->flashdata('message'); ?>

<a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newBarangKeluarModal">Tambah Barang Keluar</a>



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
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
  </div>

</div>

<!-- Modal: Tambah -->
<div class="modal fade" id="newBarangKeluarModal" tabindex="-1" role="dialog" aria-labelledby="newBarangKeluarLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newBarangKeluarLabel">Tambah Barang Keluar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formAddBarangKeluar">
        <div class="modal-body">
          <div class="form-group">
            <label for="o_id_barang">Id Barang</label>
            <select class="form-control" id="o_id_barang" name="id_barang" required>
              <option value="">Pilih barang</option>
              <?php if (isset($barang_options) && is_array($barang_options)) : ?>
                <?php foreach ($barang_options as $b) : ?>
                  <option value="<?= $b->id_barang ?>"><?= $b->nama_barang ?> (<?= $b->kode_barang ?>)</option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
            <small class="text-danger pl-1" id="err_o_id_barang"></small>
          </div>
          <div class="form-group">
            <label for="o_tgl_keluar">Tanggal keluar</label>
            <input type="date" class="form-control" id="o_tgl_keluar" name="tgl_keluar" required>
            <small class="text-danger pl-1" id="err_o_tgl_keluar"></small>
          </div>
          <div class="form-group">
            <label for="o_jenis_tras">Jenis Transaksi</label>
            <select class="form-control" id="o_jenis_tras" name="jenis_tras" required>
              <option value="">Pilih jenis transaksi</option>
              <option value="Dipinjam">Dipinjam</option>
              <option value="Dipindahkan">Dipindahkan</option>
              <option value="Dihapus">Dihapus</option>
            </select>
            <small class="text-danger pl-1" id="err_o_jenis_tras"></small>
          </div>
          <div class="form-group">
            <label for="o_tujuan">Tujuan</label>
            <input type="text" class="form-control" id="o_tujuan" name="tujuan" required>
            <small class="text-danger pl-1" id="err_o_tujuan"></small>
          </div>
          <div class="form-group">
            <label for="o_pj">Penanggung jawab</label>
            <input type="text" class="form-control" id="o_pj" name="pj" required>
            <small class="text-danger pl-1" id="err_o_pj"></small>
          </div>
          <div class="form-group">
            <label for="o_jumlah">Jumlah</label>
            <input type="number" class="form-control" id="o_jumlah" name="jumlah" required>
            <small class="text-danger pl-1" id="err_o_jumlah"></small>
          </div>
          <div class="form-group">
            <label for="o_batas_wp">Batas waktu pengembalian</label>
            <input type="date" class="form-control" id="o_batas_wp" name="batas_wp" required>
            <small class="text-danger pl-1" id="err_o_batas_wp"></small>
          </div>
          <div class="form-group">
            <label for="o_tgl_kembali">Tanggal kembali</label>
            <input type="date" class="form-control" id="o_tgl_kembali" name="tgl_kembali" required>
            <small class="text-danger pl-1" id="err_o_tgl_kembali"></small>
          </div>
          <div class="form-group">
            <label for="o_status_keterlambatan">Status keterlambatan</label>
            <select class="form-control" id="o_status_keterlambatan" name="status_keterlambatan" required>
              <option value="">Pilih status</option>
              <option value="Tepat Waktu">Tepat Waktu</option>
              <option value="Terlambat">Terlambat</option>
              <option value="Belum Kembali">Belum Kembali</option>
            </select>
            <small class="text-danger pl-1" id="err_o_status_keterlambatan"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: Edit -->
<div class="modal fade" id="editBarangKeluarModal" tabindex="-1" role="dialog" aria-labelledby="editBarangKeluarLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBarangKeluarLabel">Edit Barang Keluar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formEditBarangKeluar">
        <div class="modal-body">
          <input type="hidden" name="id_barangout" id="e_id_barangout">

          <div class="form-group">
            <label for="e_o_id_barang">Id Barang</label>
            <select class="form-control" id="e_o_id_barang" name="id_barang" required>
              <option value="">Pilih barang</option>
              <?php if (isset($barang_options) && is_array($barang_options)) : ?>
                <?php foreach ($barang_options as $b) : ?>
                  <option value="<?= $b->id_barang ?>"><?= $b->nama_barang ?> (<?= $b->kode_barang ?>)</option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
            <small class="text-danger pl-1" id="err_e_o_id_barang"></small>
          </div>

          <div class="form-group">
            <label for="e_o_tgl_keluar">Tanggal keluar</label>
            <input type="date" class="form-control" id="e_o_tgl_keluar" name="tgl_keluar" required>
            <small class="text-danger pl-1" id="err_e_o_tgl_keluar"></small>
          </div>

          <div class="form-group">
            <label for="e_o_jenis_tras">Jenis Transaksi</label>
            <select class="form-control" id="e_o_jenis_tras" name="jenis_tras" required>
              <option value="">Pilih jenis transaksi</option>
              <option value="Dipinjam">Dipinjam</option>
              <option value="Dipindahkan">Dipindahkan</option>
              <option value="Dihapus">Dihapus</option>
            </select>
            <small class="text-danger pl-1" id="err_e_o_jenis_tras"></small>
          </div>

          <div class="form-group">
            <label for="e_o_tujuan">Tujuan</label>
            <input type="text" class="form-control" id="e_o_tujuan" name="tujuan" required>
            <small class="text-danger pl-1" id="err_e_o_tujuan"></small>
          </div>

          <div class="form-group">
            <label for="e_o_pj">Penanggung jawab</label>
            <input type="text" class="form-control" id="e_o_pj" name="pj" required>
            <small class="text-danger pl-1" id="err_e_o_pj"></small>
          </div>

          <div class="form-group">
            <label for="e_o_jumlah">Jumlah</label>
            <input type="number" class="form-control" id="e_o_jumlah" name="jumlah" required>
            <small class="text-danger pl-1" id="err_e_o_jumlah"></small>
          </div>

          <div class="form-group">
            <label for="e_o_batas_wp">Batas waktu pengembalian</label>
            <input type="date" class="form-control" id="e_o_batas_wp" name="batas_wp" required>
            <small class="text-danger pl-1" id="err_e_o_batas_wp"></small>
          </div>

          <div class="form-group">
            <label for="e_o_tgl_kembali">Tanggal kembali</label>
            <input type="date" class="form-control" id="e_o_tgl_kembali" name="tgl_kembali" required>
            <small class="text-danger pl-1" id="err_e_o_tgl_kembali"></small>
          </div>

          <div class="form-group">
            <label for="e_o_status_keterlambatan">Status keterlambatan</label>
            <select class="form-control" id="e_o_status_keterlambatan" name="status_keterlambatan" required>
              <option value="">Pilih status</option>
              <option value="Tepat Waktu">Tepat Waktu</option>
              <option value="Terlambat">Terlambat</option>
              <option value="Belum Kembali">Belum Kembali</option>
            </select>
            <small class="text-danger pl-1" id="err_e_o_status_keterlambatan"></small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

