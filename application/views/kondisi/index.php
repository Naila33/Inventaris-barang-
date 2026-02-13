<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<!-- Prodi: Alerts & Actions -->
<?= $this->session->flashdata('message'); ?>

    <div class="table-responsive">
  <table id="datatable-kondisi" class="table table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kode Barang</th>
                <th>Kondisi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div class="modal fade" id="editKondisiModal" tabindex="-1" role="dialog" aria-labelledby="editKondisiLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editKondisiLabel">Edit Kondisi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formEditKondisi">
        <div class="modal-body">
          <div class="form-group">
            <label for="e_id_barang">Barang</label>
            <select class="form-control" id="e_id_barang" name="id_barang" required>
              <option value="">Pilih barang</option>
            </select>
            <small class="text-danger pl-1" id="err_id_barang"></small>
          </div>

          <div class="form-group">
            <label for="e_kondisi">Kondisi</label>
            <select class="form-control" id="e_kondisi" name="kondisi" required>
              <option value="">Pilih kondisi</option>
              <option value="Baik">Baik</option>
              <option value="Rusak Ringan">Rusak Ringan</option>
              <option value="Rusak Berat">Rusak Berat</option>
              <option value="Hilang">Hilang</option>
              <option value="Kadaluarsa">Kadaluarsa</option>
            </select>
            <small class="text-danger pl-1" id="err_kondisi"></small>
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