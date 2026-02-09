<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>


<!-- Prodi: Alerts & Actions -->
<?= $this->session->flashdata('message'); ?>
<a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newdatabarang">Tambah Barang</a>

<!-- Prodi: Table List -->
<div class="table-responsive">
  <table id="datatable" class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Kode Barang</th>
        <th scope="col">Nama Barang</th>
        <th scope="col">Kategori</th>
        <th scope="col">Spesifikasi</th>
        <th scope="col">Satuan</th>
        <th scope="col">Harga perolehan</th>
        <th scope="col">Tanggal perolehan</th>
        <th scope="col">Umur ekonomis</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($databarang)) : ?>
        <?php $i = 1; foreach ($databarang as $dt) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td><?= htmlspecialchars($dt['kode_barang'] ?? ''); ?></td>
            <td><?= htmlspecialchars($dt['nama_barang'] ?? ''); ?></td>
            <td><?= htmlspecialchars($dt['kategori'] ?? ''); ?></td>
            <td><?= htmlspecialchars($dt['spesifikasi'] ?? ''); ?></td>
            <td><?= htmlspecialchars($dt['satuan'] ?? ''); ?></td>
            <td><?= htmlspecialchars($dt['harga_perolehan'] ?? ''); ?></td>
            <td><?= htmlspecialchars($dt['tanggal_perolehan'] ?? ''); ?></td>
            <td><?= htmlspecialchars($dt['umur_ekonomis'] ?? ''); ?></td>
            <td>
              <a href="#" class="badge badge-success btn-edit-barang" data-id="<?= $dt['id_barang'] ?>">edit</a>
              <a href="#" class="badge badge-danger btn-delete-barang" data-id="<?= $dt['id_barang'] ?>">delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr>
          <td colspan="10" class="text-center">Belum ada data barang.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
  </div>

<!-- Modal: Tambah  -->
<div class="modal fade" id="newdatabarang" tabindex="-1" role="dialog" aria-labelledby="newdatabarangModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newdatabarangModalLabel">Tambah Barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= base_url('master/databarang'); ?>" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label for="nama_barang">Nama barang</label>
            <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Masukkan nama barang" value="<?= set_value('nama_barang') ?>" required>
            <?= form_error('nama_barang', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="kategori">Kategori</label>
            <input type="text" class="form-control" id="kategori" name="kategori" placeholder="Masukkan kategori" value="<?= set_value('kategori') ?>" required>
            <?= form_error('kategori', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="spesifikasi">Spesifikasi</label>
            <input type="text" class="form-control" id="spesifikasi" name="spesifikasi" placeholder="Masukkan spesifikasi" value="<?= set_value('spesifikasi') ?>" required>
            <?= form_error('spesifikasi', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="satuan">Satuan</label>
            <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Masukkan satuan" value="<?= set_value('satuan') ?>" required>
            <?= form_error('satuan', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="harga_perolehan">Harga perolehan</label>
            <input type="text" class="form-control" id="harga_perolehan" name="harga_perolehan" placeholder="Masukkan harga perolehan" value="<?= set_value('harga_perolehan') ?>" required>
            <?= form_error('harga_perolehan', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="tanggal_perolehan">Tanggal perolehan</label>
            <input type="text" class="form-control" id="tanggal_perolehan" name="tanggal_perolehan" placeholder="Masukkan tanggal perolehan" value="<?= set_value('tanggal_perolehan') ?>" required>
            <?= form_error('tanggal_perolehan', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="umur_ekonomis">Umur ekonomis</label>
            <input type="text" class="form-control" id="umur_ekonomis" name="umur_ekonomis" placeholder="Masukkan umur ekonomis" value="<?= set_value('umur_ekonomis') ?>" required>
            <?= form_error('umur_ekonomis', '<small class="text-danger pl-1">', '</small>'); ?>
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
    <div class="modal fade" id="editdatabarangModal" tabindex="-1" role="dialog" aria-labelledby="editdatabarangModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editdatabarangModalLabel">Edit Barang</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="formeditdatabarang">
            <input type="hidden" name="id_barang" id="e_id">
            <div class="modal-body">
            <div class="form-group">
                <label for="e_nama_barang">Nama Barang</label>
                <input type="text" class="form-control" id="e_nama_barang" name="nama_barang" required>
                <small class="text-danger pl-1" id="err_nama_barang"></small>
            </div>
            <div class="form-group">
                <label for="e_kategori">Kategori</label>
                <input type="text" class="form-control" id="e_kategori" name="kategori" required>
                <small class="text-danger pl-1" id="err_kategori"></small>
            </div>
            <div class="form-group">
                <label for="e_spesifikasi">Spesifikasi</label>
                <input type="text" class="form-control" id="e_spesifikasi" name="spesifikasi" required>
                <small class="text-danger pl-1" id="err_spesifikasi"></small>
            </div>
            <div class="form-group">
                <label for="e_satuan">Satuan</label>
                <input type="text" class="form-control" id="e_satuan" name="satuan" required>
                <small class="text-danger pl-1" id="err_satuan"></small>
            </div>
            <div class="form-group">
                <label for="e_harga_perolehan">Harga Perolehan</label>
                <input type="text" class="form-control" id="e_harga_perolehan" name="harga_perolehan" required>
                <small class="text-danger pl-1" id="err_harga_perolehan"></small>
            </div>
            <div class="form-group">
                <label for="e_tanggal_perolehan">Tanggal Perolehan</label>
                <input type="text" class="form-control" id="e_tanggal_perolehan" name="tanggal_perolehan" required>
                <small class="text-danger pl-1" id="err_tanggal_perolehan"></small>
            </div>
            <div class="form-group">
                <label for="e_umur_ekonomis">Umur Ekonomis</label>
                <input type="text" class="form-control" id="e_umur_ekonomis" name="umur_ekonomis" required>
                <small class="text-danger pl-1" id="err_umur_ekonomis"></small>
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

    <?php if ((isset($open_modal) && $open_modal)
    || form_error('nama_barang')
    || form_error('kategori')
    || form_error('spesifikasi')
    || form_error('satuan')
    || form_error('harga_perolehan')
    || form_error('tanggal_perolehan')
    || form_error('umur_ekonomis')): ?>
    <script>
    $(document).ready(function(){
    $('#newdatabarang').modal('show');
    });
    </script>
    <?php endif; ?>
