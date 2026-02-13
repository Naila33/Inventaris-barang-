<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>


<!-- Prodi: Alerts & Actions -->
<?= $this->session->flashdata('message'); ?>

<a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newBarangModal">Tambah Barang</a>


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
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
  </div>

  <div class="modal fade" id="newBarangModal" tabindex="-1" role="dialog" aria-labelledby="newBarangLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newBarangLabel">Tambah Barang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formAddBarang">
          <div class="modal-body">
            <div class="form-group">
              <label for="nama_barang">Nama Barang</label>
              <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
              <small class="text-danger pl-1" id="err_nama_barang"></small>
            </div>
            <div class="form-group">
              <label for="id_kategori">Kategori</label>
              <select class="form-control" id="id_kategori" name="id_kategori" required>
                <option value="">Pilih</option>
                <?php if (isset($kategori) && is_array($kategori)) : ?>
                  <?php foreach ($kategori as $k) : ?>
                    <option value="<?= $k['id_kategori'] ?>"><?= $k['nama_kategori'] ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <small class="text-danger pl-1" id="err_id_kategori"></small>
            </div>
            <div class="form-group">
              <label for="spesifikasi">Spesifikasi</label>
              <input type="text" class="form-control" id="spesifikasi" name="spesifikasi" required>
              <small class="text-danger pl-1" id="err_spesifikasi"></small>
            </div>
            <div class="form-group">
              <label for="satuan">Satuan</label>
              <input type="text" class="form-control" id="satuan" name="satuan" required>
              <small class="text-danger pl-1" id="err_satuan"></small>
            </div>
            <div class="form-group">
              <label for="harga_perolehan">Harga Perolehan</label>
              <input type="number" class="form-control" id="harga_perolehan" name="harga_perolehan" required>
              <small class="text-danger pl-1" id="err_harga_perolehan"></small>
            </div>
            <div class="form-group">
              <label for="tanggal_perolehan">Tanggal Perolehan</label>
              <input type="date" class="form-control" id="tanggal_perolehan" name="tanggal_perolehan" required>
              <small class="text-danger pl-1" id="err_tanggal_perolehan"></small>
            </div>
            <div class="form-group">
              <label for="umur_ekonomis">Umur Ekonomis</label>
              <input type="text" class="form-control" id="umur_ekonomis" name="umur_ekonomis" required>
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

  <div class="modal fade" id="editBarangModal" tabindex="-1" role="dialog" aria-labelledby="editBarangLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editBarangLabel">Edit Barang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formEditBarang">
          <div class="modal-body">
            <input type="hidden" name="id_barang" id="e_id_barang">
            <div class="form-group">
              <label for="e_nama_barang">Nama Barang</label>
              <input type="text" class="form-control" id="e_nama_barang" name="nama_barang" required>
              <small class="text-danger pl-1" id="err_e_nama_barang"></small>
            </div>
            <div class="form-group">
              <label for="e_id_kategori">Kategori</label>
              <select class="form-control" id="e_id_kategori" name="id_kategori" required>
                <option value="">Pilih</option>
                <?php if (isset($kategori) && is_array($kategori)) : ?>
                  <?php foreach ($kategori as $k) : ?>
                    <option value="<?= $k['id_kategori'] ?>"><?= $k['nama_kategori'] ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <small class="text-danger pl-1" id="err_e_id_kategori"></small>
            </div>
            <div class="form-group">
              <label for="e_spesifikasi">Spesifikasi</label>
              <input type="text" class="form-control" id="e_spesifikasi" name="spesifikasi" required>
              <small class="text-danger pl-1" id="err_e_spesifikasi"></small>
            </div>
            <div class="form-group">
              <label for="e_satuan">Satuan</label>
              <input type="text" class="form-control" id="e_satuan" name="satuan" required>
              <small class="text-danger pl-1" id="err_e_satuan"></small>
            </div>
            <div class="form-group">
              <label for="e_harga_perolehan">Harga Perolehan</label>
              <input type="number" class="form-control" id="e_harga_perolehan" name="harga_perolehan" required>
              <small class="text-danger pl-1" id="err_e_harga_perolehan"></small>
            </div>
            <div class="form-group">
              <label for="e_tanggal_perolehan">Tanggal Perolehan</label>
              <input type="date" class="form-control" id="e_tanggal_perolehan" name="tanggal_perolehan" required>
              <small class="text-danger pl-1" id="err_e_tanggal_perolehan"></small>
            </div>
            <div class="form-group">
              <label for="e_umur_ekonomis">Umur Ekonomis</label>
              <input type="text" class="form-control" id="e_umur_ekonomis" name="umur_ekonomis" required>
              <small class="text-danger pl-1" id="err_e_umur_ekonomis"></small>
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
