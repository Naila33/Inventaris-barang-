<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<!-- Prodi: Alerts & Actions -->
<?= $this->session->flashdata('message'); ?>
<a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newSupplierModal">Tambah Supplier</a>


<!-- Prodi: Table List -->
<div class="table-responsive">
  <table id="suppliertable" class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nama Supplier</th>
        <th scope="col">Kontak Supplier</th>
        <th scope="col">Nomor Telepon</th>
        <th scope="col">Kota</th>
        <th scope="col">Status</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
  </div>


  <!-- Modal: Tambah supplier -->
<div class="modal fade" id="newSupplierModal" tabindex="-1" role="dialog" aria-labelledby="newSupplierLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newSupplierLabel">Tambah Supplier</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('master/supplierbarang'); ?>" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <label for="nama_supplier">Nama supplier</label>
            <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" value="<?= set_value('nama_supplier') ?>" required>
            <?= form_error('nama_supplier', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="kontak">Kontak</label>
            <input type="text" class="form-control" id="kontak" name="kontak" value="<?= set_value('kontak') ?>" required>
            <?= form_error('kontak', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="no_telp">Nomor Telepon</label>
            <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?= set_value('no_telp') ?>" required>
            <?= form_error('no_telp', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="kota">Kota</label>
            <input type="text" class="form-control" id="kota" name="kota" value="<?= set_value('kota') ?>" required>
            <?= form_error('kota', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="status">Status</label>
            <input type="text" class="form-control" id="status" name="status" value="<?= set_value('status') ?>" required>
            <?= form_error('status', '<small class="text-danger pl-1">', '</small>'); ?>
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

    <!-- Modal: Edit supplier -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" role="dialog" aria-labelledby="editSupplierLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editSupplierLabel">Edit Supplier</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formEditSupplier">
        <div class="modal-body">
          <input type="hidden" name="id_supplier" id="e_id_supplier">

          <div class="form-group">
            <label for="e_nama_supplier">Nama supplier</label>
            <input type="text" class="form-control" id="e_nama_supplier" name="nama_supplier" required>
            <small class="text-danger pl-1" id="err_e_nama_supplier"></small>
          </div>
          <div class="form-group">
            <label for="e_kontak">Kontak</label>
            <input type="text" class="form-control" id="e_kontak" name="kontak" required>
            <small class="text-danger pl-1" id="err_e_kontak"></small>
          </div>
          <div class="form-group">
            <label for="e_no_telp">Nomor Telepon</label>
            <input type="text" class="form-control" id="e_no_telp" name="no_telp" required>
            <small class="text-danger pl-1" id="err_e_no_telp"></small>
          </div>
          <div class="form-group">
            <label for="e_kota">Kota</label>
            <input type="text" class="form-control" id="e_kota" name="kota" required>
            <small class="text-danger pl-1" id="err_e_kota"></small>
          </div>
          <div class="form-group">
            <label for="e_status">Status</label>
            <input type="text" class="form-control" id="e_status" name="status" required>
            <small class="text-danger pl-1" id="err_e_status"></small>
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