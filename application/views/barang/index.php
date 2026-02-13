<button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambah">
    + Tambah Barang
</button>

<div class="table-responsive">
<table id="tableBarang" class="table table-bordered">
<thead>
<tr>
  <th scope="col">#</th>
  <th scope="col">QR</th>
        <th scope="col">Kode Barang</th>
        <th scope="col">Nama Barang</th>
        <th scope="col">Kategori</th>
        <th scope="col">Spesifikasi</th>
        <th scope="col">Satuan</th>
        <th scope="col">Harga perolehan</th>
        <th scope="col">Tanggal perolehan</th>
        <th scope="col">Umur ekonomis</th>
        <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php $i = 1; foreach($barang as $b): ?>
<tr>
    <td><?= $i++; ?></td>

    <td>
        <img src="<?= base_url('assets/img/qrcode/'.$b->qr_code) ?>" width="60">
    </td>

    <td><?= htmlspecialchars($b->kode_barang); ?></td>
    <td><?= htmlspecialchars($b->nama_barang); ?></td>
    <td><?= htmlspecialchars($b->kategori); ?></td>
    <td><?= htmlspecialchars($b->spesifikasi); ?></td>
    <td><?= htmlspecialchars($b->satuan); ?></td>
    <td><?= number_format($b->harga_perolehan,0,',','.'); ?></td>
    <td><?= $b->tanggal_perolehan; ?></td>
    <td><?= $b->umur_ekonomis; ?></td>

    <td>
        <button 
            class="btn btn-warning btn-sm btn-edit"
            data-id="<?= $b->id_barang ?>"
            data-nama="<?= $b->nama_barang ?>"
            data-kategori="<?= $b->kategori ?>"
            data-spesifikasi="<?= $b->spesifikasi ?>"
            data-satuan="<?= $b->satuan ?>"
            data-harga="<?= $b->harga_perolehan ?>"
            data-tanggal="<?= $b->tanggal_perolehan ?>"
            data-umur="<?= $b->umur_ekonomis ?>"
        >
            Edit
        </button>

        <button 
          class="btn btn-danger btn-sm btn-hapus"
          data-toggle="modal"
          data-target="#modalPenghapusan"
          data-id="<?= $b->id_barang ?>"
          data-nama="<?= $b->nama_barang ?>"
        >
          Penghapusan
        </button>

    </td>
</tr>
<?php endforeach ?>
</tbody>
</table>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <form method="post" action="<?= base_url('barang/tambah') ?>" enctype="multipart/form-data">

        <div class="modal-header">
          <h5 class="modal-title">Tambah Barang</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">

          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="kategori" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Spesifikasi</label>
            <textarea name="spesifikasi" class="form-control"></textarea>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Satuan</label>
                <input type="text" name="satuan" class="form-control">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Harga Perolehan</label>
                   <input 
      type="text"
      name="harga"
      id="harga"
      class="form-control"
      placeholder="Contoh: 1.500.000"
      autocomplete="off"
      required
    >
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Tanggal Perolehan</label>
                <input type="date" name="tanggal" class="form-control">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Umur Ekonomis (Tahun)</label>
                <input type="number" name="umur" class="form-control">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Foto Barang</label>
            <input type="file" name="foto" class="form-control">
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>

      </form>

    </div>
  </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <form method="post" action="<?= base_url('barang/update') ?>">

        <div class="modal-header">
          <h5 class="modal-title">Edit Barang</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">

          <input type="hidden" name="id_barang" id="edit_id">

          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" id="edit_nama" class="form-control">
          </div>

          <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="kategori" id="edit_kategori" class="form-control">
          </div>

          <div class="form-group">
            <label>Spesifikasi</label>
            <textarea name="spesifikasi" id="edit_spesifikasi" class="form-control"></textarea>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label>Satuan</label>
              <input type="text" name="satuan" id="edit_satuan" class="form-control">
            </div>
            <div class="col-md-6">
              <label>Harga</label>
              <input type="number" step="0.01" name="harga" id="edit_harga" class="form-control">
            </div>
          </div>

          <div class="row mt-2">
            <div class="col-md-6">
              <label>Tanggal Perolehan</label>
              <input type="date" name="tanggal" id="edit_tanggal" class="form-control">
            </div>
            <div class="col-md-6">
              <label>Umur Ekonomis</label>
              <input type="number" name="umur" id="edit_umur" class="form-control">
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>

      </form>

    </div>
  </div>
</div>

<div class="modal fade" id="modalPenghapusan">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="post" action="<?= base_url('penghapusan/simpan') ?>">

        <div class="modal-header">
          <h5 class="modal-title">Konfirmasi Penghapusan</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">

          <input type="hidden" name="id_barang" id="hapus_id">

          <p id="hapus_info" class="mb-3"></p>

          <div class="form-group">
            <label>Jenis Penghapusan</label>
            <select name="jenis_penghapusan" id="hapus_jenis" class="form-control" required>
              <option value="">-- Pilih --</option>
              <option value="Rusak Berat">Rusak Berat</option>
              <option value="Hilang">Hilang</option>
              <option value="Kadaluarsa">Kadaluarsa</option>
            </select>
          </div>

          <div class="form-group">
            <label>Tanggal Penghapusan</label>
            <input type="date" name="tanggal_penghapusan" id="hapus_tanggal" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control" required></textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button class="btn btn-danger">Proses Penghapusan</button>
        </div>

      </form>

    </div>
  </div>
</div>



<script>
function formatRupiah(angka) {
    return angka.replace(/\D/g, '')
                .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

$('#harga, #edit_harga').on('keyup', function () {
    this.value = formatRupiah(this.value);
});

$('.btn-hapus').on('click', function () {

    var row = $(this).closest('tr');

    var id = $(this).data('id');
    var nama = row.find('td:eq(3)').text();
    var kategori = row.find('td:eq(4)').text();
    var harga = row.find('td:eq(7)').text();
    var tanggal = row.find('td:eq(8)').text();

    $('#hapus_id').val(id);
    $('#hapus_jenis').val('Rusak Berat');
    $('#hapus_tanggal').val(new Date().toISOString().split('T')[0]);

    $('#hapus_info').html(
        "Anda akan menghapus barang berikut:<br><br>" +
        "<strong>Nama:</strong> " + nama + "<br>" +
        "<strong>Kategori:</strong> " + kategori + "<br>" +
        "<strong>Harga Perolehan:</strong> Rp " + harga + "<br>" +
        "<strong>Tanggal Perolehan:</strong> " + tanggal + "<br><br>" +
        "<span class='text-danger'>Data yang sudah dihapus tidak dapat dikembalikan.</span>"
    );

});
</script>



