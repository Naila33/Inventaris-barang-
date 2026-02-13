<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<!-- Prodi: Alerts & Actions -->
<?= $this->session->flashdata('message'); ?>

<a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newMutasiModal">Tambah Mutasi</a>



<!-- Prodi: Table List -->
<div class="table-responsive">
  <table id="datatable-mutasi" class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Id Barang</th>
        <th scope="col">Tanggal mutasi</th>
        <th scope="col">Lokasi asal</th>
        <th scope="col">Lokasi tujuan</th>
        <th scope="col">unit asal</th>
        <th scope="col">unit tujuan</th>
        <th scope="col">jumlah</th>
        <th scope="col">penanggung jawab</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
  </div>

<!-- Modal: Tambah Mutasi -->
<div class="modal fade" id="newMutasiModal" tabindex="-1" role="dialog" aria-labelledby="newMutasiLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newMutasiLabel">Tambah Mutasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('mutasi/mutasi'); ?>" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label for="id_barang">Id Barang</label>
            <input type="number" class="form-control" id="id_barang" name="id_barang" value="<?= set_value('id_barang') ?>" required>
            <?= form_error('id_barang', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="tanggal_mutasi">Tanggal mutasi</label>
            <input type="date" class="form-control" id="tanggal_mutasi" name="tanggal_mutasi" value="<?= set_value('tanggal_mutasi') ?>" required>
            <?= form_error('tanggal_mutasi', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="lokasi_asal">Lokasi asal</label>
            <input type="text" class="form-control" id="lokasi_asal" name="lokasi_asal" value="<?= set_value('lokasi_asal') ?>" required>
            <?= form_error('lokasi_asal', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="lokasi_tujuan">Lokasi tujuan</label>
            <input type="text" class="form-control" id="lokasi_tujuan" name="lokasi_tujuan" value="<?= set_value('lokasi_tujuan') ?>" required>
            <?= form_error('lokasi_tujuan', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="unit_asal">Unit asal</label>
            <input type="text" class="form-control" id="unit_asal" name="unit_asal" value="<?= set_value('unit_asal') ?>">
            <?= form_error('unit_asal', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="unit_tujuan">Unit tujuan</label>
            <input type="text" class="form-control" id="unit_tujuan" name="unit_tujuan" value="<?= set_value('unit_tujuan') ?>">
            <?= form_error('unit_tujuan', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="jumlah">Jumlah</label>
            <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?= set_value('jumlah') ?>" required>
            <?= form_error('jumlah', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="penanggung_jawab">Penanggung jawab</label>
            <input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab" value="<?= set_value('penanggung_jawab') ?>" required>
            <?= form_error('penanggung_jawab', '<small class="text-danger pl-1">', '</small>'); ?>
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

<!-- Modal: Edit Mutasi -->
<div class="modal fade" id="editMutasiModal" tabindex="-1" role="dialog" aria-labelledby="editMutasiLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editMutasiLabel">Edit Mutasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formeditMutasi">
        <input type="hidden" name="id_mutasi" id="e_id_mutasi">
        <div class="modal-body">
          <div class="form-group">
            <label for="e_id_barang">Id Barang</label>
            <input type="number" class="form-control" id="e_id_barang" name="id_barang" required>
            <small class="text-danger pl-1" id="err_id_barang"></small>
          </div>
          <div class="form-group">
            <label for="e_tanggal_mutasi">Tanggal mutasi</label>
            <input type="date" class="form-control" id="e_tanggal_mutasi" name="tanggal_mutasi" required>
            <small class="text-danger pl-1" id="err_tanggal_mutasi"></small>
          </div>
          <div class="form-group">
            <label for="e_lokasi_asal">Lokasi asal</label>
            <input type="text" class="form-control" id="e_lokasi_asal" name="lokasi_asal" required>
            <small class="text-danger pl-1" id="err_lokasi_asal"></small>
          </div>
          <div class="form-group">
            <label for="e_lokasi_tujuan">Lokasi tujuan</label>
            <input type="text" class="form-control" id="e_lokasi_tujuan" name="lokasi_tujuan" required>
            <small class="text-danger pl-1" id="err_lokasi_tujuan"></small>
          </div>
          <div class="form-group">
            <label for="e_unit_asal">Unit asal</label>
            <input type="text" class="form-control" id="e_unit_asal" name="unit_asal">
            <small class="text-danger pl-1" id="err_unit_asal"></small>
          </div>
          <div class="form-group">
            <label for="e_unit_tujuan">Unit tujuan</label>
            <input type="text" class="form-control" id="e_unit_tujuan" name="unit_tujuan">
            <small class="text-danger pl-1" id="err_unit_tujuan"></small>
          </div>
          <div class="form-group">
            <label for="e_jumlah">Jumlah</label>
            <input type="number" class="form-control" id="e_jumlah" name="jumlah" required>
            <small class="text-danger pl-1" id="err_jumlah"></small>
          </div>
          <div class="form-group">
            <label for="e_penanggung_jawab">Penanggung jawab</label>
            <input type="text" class="form-control" id="e_penanggung_jawab" name="penanggung_jawab" required>
            <small class="text-danger pl-1" id="err_penanggung_jawab"></small>
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

