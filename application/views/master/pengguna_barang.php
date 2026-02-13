<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<!-- Prodi: Alerts & Actions -->
<?= $this->session->flashdata('message'); ?>

<a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newPenggunaModal">Tambah Pengguna</a>

<!-- Prodi: Table List -->
<div class="table-responsive">
  <table id="datatable-pengguna" class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nama Pengguna</th>
        <th scope="col">Jenis Pengguna</th>
        <th scope="col">No Identitas</th>
        <th scope="col">Divisi</th>
        <th scope="col">Unit</th>
        <th scope="col">Nomor Telepon</th>
        <th scope="col">Status</th>
        <th scope="col">Keterangan</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
  </div>

 <!-- Modal: Tambah pengguna -->
<div class="modal fade" id="newPenggunaModal" tabindex="-1" role="dialog" aria-labelledby="newPenggunaLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newPenggunaLabel">Tambah Pengguna</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('master/penggunabarang'); ?>" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label for="nama_pengguna">Nama pengguna</label>
            <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna" value="<?= set_value('nama_pengguna') ?>" required>
            <?= form_error('nama_pengguna', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="jenis_pengguna">Jenis pengguna</label>
            <select class="form-control" id="jenis_pengguna" name="jenis_pengguna" required>
              <option value="" <?= set_select('jenis_pengguna', '', true) ?>>Pilih</option>
              <option value="Pegawai" <?= set_select('jenis_pengguna', 'Pegawai') ?>>Pegawai</option>
              <option value="Umum" <?= set_select('jenis_pengguna', 'Umum') ?>>Umum</option>
              <option value="Lainnya" <?= set_select('jenis_pengguna', 'Lainnya') ?>>Lainnya</option>
            </select>
            <?= form_error('jenis_pengguna', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="no_identitas">No Identitas</label>
            <input type="text" class="form-control" id="no_identitas" name="no_identitas" value="<?= set_value('no_identitas') ?>" required>
            <?= form_error('no_identitas', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="divisi">Divisi</label>
            <select class="form-control" id="divisi" name="divisi" required>
              <option value="" <?= set_select('divisi', '', true) ?>>Pilih</option>
              <option value="IT" <?= set_select('divisi', 'IT') ?>>IT</option>
              <option value="Keuangan" <?= set_select('divisi', 'Keuangan') ?>>Keuangan</option>
              <option value="HRD" <?= set_select('divisi', 'HRD') ?>>HRD</option>
              <option value="Umum" <?= set_select('divisi', 'Umum') ?>>Umum</option>
              <option value="Logistik" <?= set_select('divisi', 'Logistik') ?>>Logistik</option>
            </select>
            <?= form_error('divisi', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="unit">Unit</label>
            <input type="text" class="form-control" id="unit" name="unit" value="<?= set_value('unit') ?>" required>
            <?= form_error('unit', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="no_telp">Nomor Telepon</label>
            <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?= set_value('no_telp') ?>" required>
            <?= form_error('no_telp', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
              <option value="" <?= set_select('status', '', true) ?>>Pilih</option>
              <option value="Aktif" <?= set_select('status', 'Aktif') ?>>Aktif</option>
              <option value="Nonaktif" <?= set_select('status', 'Nonaktif') ?>>Nonaktif</option>
            </select>
            <?= form_error('status', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?= set_value('keterangan') ?>" required>
            <?= form_error('keterangan', '<small class="text-danger pl-1">', '</small>'); ?>
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

    <!-- Modal: Edit pengguna -->
<div class="modal fade" id="editPenggunaModal" tabindex="-1" role="dialog" aria-labelledby="editPenggunaLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPenggunaLabel">Edit Pengguna</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formEditPengguna">
        <div class="modal-body">
          <input type="hidden" name="id_pengguna" id="e_id_pengguna">

          <div class="form-group">
            <label for="e_nama_pengguna">Nama pengguna</label>
            <input type="text" class="form-control" id="e_nama_pengguna" name="nama_pengguna" required>
            <small class="text-danger pl-1" id="err_e_nama_pengguna"></small>
          </div>
          <div class="form-group">
            <label for="e_jenis_pengguna">Jenis pengguna</label>
            <select class="form-control" id="e_jenis_pengguna" name="jenis_pengguna" required>
              <option value="">Pilih</option>
              <option value="Pegawai">Pegawai</option>
              <option value="Umum">Umum</option>
              <option value="Lainnya">Lainnya</option>
            </select>
            <small class="text-danger pl-1" id="err_e_jenis_pengguna"></small>
          </div>
          <div class="form-group">
            <label for="e_no_identitas">No Identitas</label>
            <input type="text" class="form-control" id="e_no_identitas" name="no_identitas" required>
            <small class="text-danger pl-1" id="err_e_no_identitas"></small>
          </div>
          <div class="form-group">
            <label for="e_divisi">Divisi</label>
            <select class="form-control" id="e_divisi" name="divisi" required>
              <option value="">Pilih</option>
              <option value="IT">IT</option>
              <option value="Keuangan">Keuangan</option>
              <option value="HRD">HRD</option>
              <option value="Umum">Umum</option>
              <option value="Logistik">Logistik</option>
            </select>
            <small class="text-danger pl-1" id="err_e_divisi"></small>
          </div>
          <div class="form-group">
            <label for="e_unit">Unit</label>
            <input type="text" class="form-control" id="e_unit" name="unit" required>
            <small class="text-danger pl-1" id="err_e_unit"></small>
          </div>
          <div class="form-group">
            <label for="e_no_telp">No Telepon</label>
            <input type="text" class="form-control" id="e_no_telp" name="no_telp" required>
            <small class="text-danger pl-1" id="err_e_no_telp"></small>
          </div>
          <div class="form-group">
            <label for="e_status">Status</label>
            <select class="form-control" id="e_status" name="status" required>
              <option value="">Pilih</option>
              <option value="Aktif">Aktif</option>
              <option value="Nonaktif">Nonaktif</option>
            </select>
            <small class="text-danger pl-1" id="err_e_status"></small>
          </div>
          <div class="form-group">
            <label for="e_keterangan">Keterangan</label>
            <input type="text" class="form-control" id="e_keterangan" name="keterangan" required>
            <small class="text-danger pl-1" id="err_e_keterangan"></small>
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

</div>