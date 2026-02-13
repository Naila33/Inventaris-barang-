<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<!-- Alerts & Actions -->
<?= $this->session->flashdata('message'); ?>
<div class="mb-3">
  <a href="<?= base_url('peminjaman') ?>" class="btn btn-primary">Semua Peminjaman</a>
  <a href="<?= base_url('peminjaman/aktif') ?>" class="btn btn-info">Peminjaman Aktif</a>
</div>

<!-- Card for Statistics -->
<div class="row mb-4">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow border-left-warning">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
              Total Peminjaman Terlambat
            </div>
            <div class="h5 mb-0 font-weight-bold text-gray-800">
              <?= count($peminjaman) ?> Barang
            </div>
          </div>
          <div class="col-auto">
            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Table List -->
<div class="table-responsive">
  <table id="datatable-terlambat" class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Tanggal Pinjam</th>
        <th scope="col">Kode Barang</th>
        <th scope="col">Nama Barang</th>
        <th scope="col">Peminjam</th>
        <th scope="col">Batas Waktu</th>
        <th scope="col">Keterlambatan</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($peminjaman)) : ?>
        <?php $i = 1; foreach ($peminjaman as $dt) : ?>
          <?php 
            $hari_ini = new DateTime();
            $batas_waktu = new DateTime($dt->batas_waktu);
            $selisih = $hari_ini->diff($batas_waktu);
          ?>
          <tr class="table-danger">
            <th scope="row"><?= $i++; ?></th>
            <td><?= date('d F Y', strtotime($dt->tanggal_pinjam)) ?></td>
            <td><?= htmlspecialchars($dt->kode_barang ?? '') ?></td>
            <td><?= htmlspecialchars($dt->nama_barang ?? '') ?></td>
            <td><?= htmlspecialchars($dt->nama_peminjam ?? '') ?></td>
            <td><?= date('d F Y', strtotime($dt->batas_waktu)) ?></td>
            <td>
              <span class="badge badge-danger">
                <?= $selisih->days ?> hari
              </span>
              <br><small class="text-muted">
                Sejak <?= date('d F Y', strtotime($dt->batas_waktu)) ?>
              </small>
            </td>
            <td>
              <a href="<?= base_url('peminjaman/kembalikan/' . $dt->id_peminjaman) ?>" 
                 class="badge badge-success" 
                 onclick="return confirm('Yakin ingin mengembalikan barang ini?')">Kembalikan</a>
              <a href="#" class="badge badge-info btn-edit-peminjaman" data-id="<?= $dt->id_peminjaman ?>">edit</a>
              <a href="#" class="badge badge-danger btn-delete-peminjaman" data-id="<?= $dt->id_peminjaman ?>">delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr>
          <td colspan="8" class="text-center">
            <div class="alert alert-success" role="alert">
              <i class="fas fa-check-circle"></i> Tidak ada peminjaman yang terlambat.
            </div>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Modal: Edit Peminjaman -->
<div class="modal fade" id="editpeminjamanModal" tabindex="-1" role="dialog" aria-labelledby="editpeminjamanModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editpeminjamanModalLabel">Edit Peminjaman</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formeditpeminjaman">
        <input type="hidden" name="id_peminjaman" id="e_id">
        <div class="modal-body">
          <div class="form-group">
            <label for="e_id_barang">Barang</label>
            <select class="form-control" id="e_id_barang" name="id_barang" required>
              <option value="">Pilih Barang</option>
              <?php foreach ($barang as $item): ?>
                <option value="<?= $item['id_barang'] ?>">
                  <?= htmlspecialchars($item['kode_barang'] . ' - ' . $item['nama_barang']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <small class="text-danger pl-1" id="err_id_barang"></small>
          </div>
          <div class="form-group">
            <label for="e_id_peminjam">Peminjam</label>
            <select class="form-control" id="e_id_peminjam" name="id_peminjam" required>
              <option value="">Pilih Peminjam</option>
              <?php foreach ($users as $user): ?>
                <option value="<?= $user['id'] ?>">
                  <?= htmlspecialchars($user['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <small class="text-danger pl-1" id="err_id_peminjam"></small>
          </div>
          <div class="form-group">
            <label for="e_tanggal_pinjam">Tanggal Pinjam</label>
            <input type="date" class="form-control" id="e_tanggal_pinjam" name="tanggal_pinjam" required>
            <small class="text-danger pl-1" id="err_tanggal_pinjam"></small>
          </div>
          <div class="form-group">
            <label for="e_batas_waktu">Batas Waktu Pengembalian</label>
            <input type="date" class="form-control" id="e_batas_waktu" name="batas_waktu" required>
            <small class="text-danger pl-1" id="err_batas_waktu"></small>
          </div>
          <div class="form-group">
            <label for="e_keterangan">Keterangan</label>
            <textarea class="form-control" id="e_keterangan" name="keterangan" rows="3"></textarea>
            <small class="text-danger pl-1" id="err_keterangan"></small>
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

<script>
$(document).ready(function(){
  // Edit peminjaman
  $('.btn-edit-peminjaman').on('click', function(e){
    e.preventDefault();
    const id = $(this).data('id');
    
    $.ajax({
      url: '<?= base_url('peminjaman/getpeminjamanrow') ?>',
      method: 'POST',
      data: {id_peminjaman: id},
      dataType: 'json',
      success: function(response){
        $('#e_id').val(response.id_peminjaman);
        $('#e_id_barang').val(response.id_barang);
        $('#e_id_peminjam').val(response.id_peminjam);
        $('#e_tanggal_pinjam').val(response.tanggal_pinjam);
        $('#e_batas_waktu').val(response.batas_waktu);
        $('#e_keterangan').val(response.keterangan);
        $('#editpeminjamanModal').modal('show');
      }
    });
  });

  // Delete peminjaman
  $('.btn-delete-peminjaman').on('click', function(e){
    e.preventDefault();
    const id = $(this).data('id');
    
    if(confirm('Apakah Anda yakin ingin menghapus data peminjaman ini?')){
      $.ajax({
        url: '<?= base_url('peminjaman/deletepeminjaman') ?>',
        method: 'POST',
        data: {id_peminjaman: id},
        dataType: 'json',
        success: function(response){
          if(response.status){
            location.reload();
          } else {
            alert('Gagal menghapus data: ' + response.message);
          }
        }
      });
    }
  });

  // Submit edit form
  $('#formeditpeminjaman').on('submit', function(e){
    e.preventDefault();
    
    $.ajax({
      url: '<?= base_url('peminjaman/updatepeminjaman') ?>',
      method: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(response){
        if(response.status){
          $('#editpeminjamanModal').modal('hide');
          location.reload();
        } else {
          // Show errors
          if(response.errors){
            $('#err_id_barang').html(response.errors.id_barang || '');
            $('#err_id_peminjam').html(response.errors.id_peminjam || '');
            $('#err_tanggal_pinjam').html(response.errors.tanggal_pinjam || '');
            $('#err_batas_waktu').html(response.errors.batas_waktu || '');
            $('#err_keterangan').html(response.errors.keterangan || '');
          }
        }
      }
    });
  });

  // Set minimum date for batas_waktu
  $('#e_tanggal_pinjam').on('change', function(){
    const tanggalPinjam = $(this).val();
    $('#e_batas_waktu').attr('min', tanggalPinjam);
  });
});
</script>

</div>
