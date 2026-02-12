<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<!-- Prodi: Alerts & Actions -->
<?= $this->session->flashdata('message'); ?>
<a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newBarangMasukModal">Tambah Barang Masuk</a>



<!-- Prodi: Table List -->
<div class="table-responsive">
  <table id="datatable-barangmasuk" class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Tanggal masuk</th>
        <th scope="col">Sumber barang</th>
        <th scope="col">Jumlah</th>
        <th scope="col">Dokumen pendukung</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
  </div>



<!-- Modal: Tambah Barang Masuk -->
<div class="modal fade" id="newBarangMasukModal" tabindex="-1" role="dialog" aria-labelledby="newBarangMasukLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newBarangMasukLabel">Tambah Barang Masuk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('barang/barangin'); ?>" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <label for="tgl_masuk">Tanggal masuk</label>
            <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" value="<?= set_value('tgl_masuk') ?>" required>
            <?= form_error('tgl_masuk', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="sumberbarang">Sumber barang</label>
            <select class="form-control" id="sumberbarang" name="sumberbarang" required>
              <option value="">Pilih sumber barang</option>
              <option value="Pembelian" <?= set_select('sumberbarang', 'Pembelian') ?>>Pembelian</option>
              <option value="Hibah" <?= set_select('sumberbarang', 'Hibah') ?>>Hibah</option>
              <option value="Pinjaman" <?= set_select('sumberbarang', 'Pinjaman') ?>>Pinjaman</option>
              <option value="Mutasi" <?= set_select('sumberbarang', 'Mutasi') ?>>Mutasi</option>
            </select>
            <?= form_error('sumberbarang', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="jumlah">Jumlah</label>
            <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Masukkan jumlah" value="<?= set_value('jumlah') ?>" required>
            <?= form_error('jumlah', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="dokumen_pendukung">Dokumen pendukung</label>
            <input type="file" class="form-control" id="dokumen_pendukung" name="dokumen_pendukung" accept="application/pdf" required>
            <?= form_error('dokumen_pendukung', '<small class="text-danger pl-1">', '</small>'); ?>
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

<!-- Modal: Edit Barang Masuk -->
<div class="modal fade" id="editBarangMasukModal" tabindex="-1" role="dialog" aria-labelledby="editBarangMasukLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBarangMasukLabel">Edit Barang Masuk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formeditBarangMasuk" enctype="multipart/form-data">
        <input type="hidden" name="id_barangin" id="e_id_barangin">
        <div class="modal-body">
          <div class="form-group">
            <label for="e_tgl_masuk">Tanggal masuk</label>
            <input type="date" class="form-control" id="e_tgl_masuk" name="tgl_masuk" required>
            <small class="text-danger pl-1" id="err_tgl_masuk"></small>
          </div>
          <div class="form-group">
            <label for="e_sumberbarang">Sumber barang</label>
            <select class="form-control" id="e_sumberbarang" name="sumberbarang" required>
              <option value="">Pilih sumber barang</option>
              <option value="Pembelian">Pembelian</option>
              <option value="Hibah">Hibah</option>
              <option value="Pinjaman">Pinjaman</option>
              <option value="Mutasi">Mutasi</option>
              <option value="Lainnya">Lainnya</option>
            </select>
            <small class="text-danger pl-1" id="err_sumberbarang"></small>
          </div>
          <div class="form-group">
            <label for="e_jumlah">Jumlah</label>
            <input type="number" class="form-control" id="e_jumlah" name="jumlah" required>
            <small class="text-danger pl-1" id="err_jumlah"></small>
          </div>
          <div class="form-group">
            <label for="e_dokumen_pendukung">Dokumen pendukung</label>
            <small class="form-text text-muted" id="current_dokumen_pendukung"></small>
            <input type="file" class="form-control" id="e_dokumen_pendukung" name="dokumen_pendukung" accept="application/pdf">
            <small class="text-danger pl-1" id="err_dokumen_pendukung"></small>
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