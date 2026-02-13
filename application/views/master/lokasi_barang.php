<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<!-- Prodi: Alerts & Actions -->
<?= $this->session->flashdata('message'); ?>
<a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newLokasiModal">Tambah Lokasi</a>


<!-- Prodi: Table List -->
<div class="table-responsive">
  <table id="datatable-lokasi" class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Kode Lokasi</th>
        <th scope="col">Nama Lokasi</th>
        <th scope="col">Gedung</th>
        <th scope="col">Lantai</th>
        <th scope="col">Keterangan</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
  </div>

 <!-- Modal: Tambah lokasi -->
<div class="modal fade" id="newLokasiModal" tabindex="-1" role="dialog" aria-labelledby="newLokasiLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newLokasiLabel">Tambah Lokasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('master/lokasibarang'); ?>" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <label for="kode_lokasi">Kode lokasi</label>
            <input type="text" class="form-control" id="kode_lokasi" name="kode_lokasi" value="<?= set_value('kode_lokasi') ?>" required>
            <?= form_error('kode_lokasi', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="nama_lokasi">Nama lokasi</label>
            <input type="text" class="form-control" id="nama_lokasi" name="nama_lokasi" value="<?= set_value('nama_lokasi') ?>" required>
            <?= form_error('nama_lokasi', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="gedung">Gedung</label>
            <input type="text" class="form-control" id="gedung" name="gedung" value="<?= set_value('gedung') ?>" required>
            <?= form_error('gedung', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="lantai">Lantai</label>
            <input type="text" class="form-control" id="lantai" name="lantai" value="<?= set_value('lantai') ?>" required>
            <?= form_error('lantai', '<small class="text-danger pl-1">', '</small>'); ?>
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

<!-- Modal: Edit lokasi -->
<div class="modal fade" id="editLokasiModal" tabindex="-1" role="dialog" aria-labelledby="editLokasiLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editLokasiLabel">Edit Lokasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formEditLokasi">
        <div class="modal-body">
          <input type="hidden" name="id_lokasi" id="e_id_lokasi">

          <div class="form-group">
            <label for="e_kode_lokasi">Kode lokasi</label>
            <input type="text" class="form-control" id="e_kode_lokasi" name="kode_lokasi" required>
            <small class="text-danger pl-1" id="err_e_kode_lokasi"></small>
          </div>
          <div class="form-group">
            <label for="e_nama_lokasi">Nama lokasi</label>
            <input type="text" class="form-control" id="e_nama_lokasi" name="nama_lokasi" required>
            <small class="text-danger pl-1" id="err_e_nama_lokasi"></small>
          </div>
          <div class="form-group">
            <label for="e_gedung">Gedung</label>
            <input type="text" class="form-control" id="e_gedung" name="gedung" required>
            <small class="text-danger pl-1" id="err_e_gedung"></small>
          </div>
          <div class="form-group">
            <label for="e_lantai">Lantai</label>
            <input type="text" class="form-control" id="e_lantai" name="lantai" required>
            <small class="text-danger pl-1" id="err_e_lantai"></small>
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