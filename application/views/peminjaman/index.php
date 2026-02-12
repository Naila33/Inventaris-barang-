<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<!-- Alerts & Actions -->
<div id="success-message"></div>
<?= $this->session->flashdata('message'); ?>
<div class="mb-3">
  <a href="" class="btn btn-primary" data-toggle="modal" data-target="#newpeminjaman">Tambah Peminjaman</a>
</div>

<!-- Table List -->
<div class="table-responsive">
  <table id="datatable-index" class="table table-hover">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Tanggal Pinjam</th>
        <th scope="col">Kode Barang</th>
        <th scope="col">Nama Barang</th>
        <th scope="col">Peminjam</th>
        <th scope="col">Batas Waktu</th>
        <th scope="col">Tanggal Kembali</th>
        <th scope="col">Status</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($peminjaman)) : ?>
        <?php $i = 1; foreach ($peminjaman as $dt) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td><?= date('d F Y', strtotime($dt->tanggal_pinjam)) ?></td>
            <td><?= htmlspecialchars($dt->kode_barang ?? '') ?></td>
            <td><?= htmlspecialchars($dt->nama_barang ?? '') ?></td>
            <td><?= htmlspecialchars($dt->nama_peminjam ?? '') ?></td>
            <td><?= date('d F Y', strtotime($dt->batas_waktu)) ?></td>
            <td><?= $dt->tanggal_kembali ? date('d F Y', strtotime($dt->tanggal_kembali)) : '-' ?></td>
            <td>
              <?php if ($dt->status == 'Dipinjam'): ?>
                <span class="badge badge-primary">Dipinjam</span>
              <?php elseif ($dt->status == 'Dikembalikan'): ?>
                <span class="badge badge-success">Dikembalikan</span>
              <?php elseif ($dt->status == 'Terlambat'): ?>
                <span class="badge badge-danger">Terlambat</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($dt->status == 'Dipinjam' || $dt->status == 'Terlambat'): ?>
                <a href="<?= base_url('peminjaman/kembalikan/' . $dt->id_peminjaman) ?>" 
                   class="badge badge-success" 
                   onclick="return confirm('Yakin ingin mengembalikan barang ini?')">Kembalikan</a>
              <?php endif; ?>
              <a href="#" class="badge badge-info btn-edit-peminjaman" data-id="<?= $dt->id_peminjaman ?>">edit</a>
              <a href="#" class="badge badge-danger btn-delete-peminjaman" data-id="<?= $dt->id_peminjaman ?>">delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr>
          <td colspan="9" class="text-center">Belum ada data peminjaman.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Modal: Tambah Peminjaman -->
<div class="modal fade" id="newpeminjaman" tabindex="-1" role="dialog" aria-labelledby="newpeminjamanModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newpeminjamanModalLabel">Tambah Peminjaman</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="newpeminjamanForm" action="<?= base_url('peminjaman'); ?>" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label for="id_barang">Barang</label>
            <select class="form-control" id="id_barang" name="id_barang" required>
              <option value="">Pilih Barang</option>
              <?php foreach ($barang as $item): ?>
                <option value="<?= $item['id_barang'] ?>" <?= set_select('id_barang', $item['id_barang']) ?>>
                  <?= htmlspecialchars($item['kode_barang'] . ' - ' . $item['nama_barang']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <?= form_error('id_barang', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="id_peminjam">Peminjam</label>
            <input type="text" class="form-control" id="id_peminjam" name="id_peminjam" placeholder="Masukkan nama peminjam" value="<?= set_value('id_peminjam') ?>" required>
            <?= form_error('id_peminjam', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="tanggal_pinjam">Tanggal Pinjam</label>
            <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" 
                   value="<?= set_value('tanggal_pinjam', date('Y-m-d')) ?>" required>
            <?= form_error('tanggal_pinjam', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="batas_waktu">Batas Waktu Pengembalian</label>
            <input type="date" class="form-control" id="batas_waktu" name="batas_waktu" 
                   value="<?= set_value('batas_waktu') ?>" required>
            <?= form_error('batas_waktu', '<small class="text-danger pl-1">', '</small>'); ?>
          </div>
          <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" 
                      placeholder="Masukkan keterangan (opsional)"><?= set_value('keterangan') ?></textarea>
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
            <input type="text" class="form-control" id="e_id_peminjam" name="id_peminjam" placeholder="Masukkan nama peminjam" required>
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

<?php if ((isset($open_modal) && $open_modal)
    || form_error('id_barang')
    || form_error('id_peminjam')
    || form_error('tanggal_pinjam')
    || form_error('batas_waktu')): ?>
<script>
$(document).ready(function(){
  $('#newpeminjaman').modal('show');
});
</script>
<?php endif; ?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function(){
  // Destroy existing DataTable if it exists
  if ($.fn.DataTable.isDataTable('#datatable-index')) {
    $('#datatable-index').DataTable().destroy();
  }
  
  // Initialize DataTable with unique ID
  $('#datatable-index').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
      "url": "<?= base_url('peminjaman/getpeminjaman') ?>",
      "type": "POST",
      "dataSrc": function(json) {
        // Re-bind event handlers after data is loaded
        setTimeout(function() {
          bindEventHandlers();
        }, 100);
        return json.data;
      }
    },
    "columns": [
      { "data": "no" },
      { "data": "tanggal_pinjam" },
      { "data": "kode_barang" },
      { "data": "nama_barang" },
      { "data": "nama_peminjam" },
      { "data": "batas_waktu" },
      { "data": "tanggal_kembali" },
      { "data": "status" },
      { "data": "aksi", "orderable": false, "searchable": false }
    ],
    "responsive": true,
    "autoWidth": false
  });
  
  // Function to bind event handlers
  function bindEventHandlers() {
    // Edit peminjaman
    $('.btn-edit-peminjaman').off('click').on('click', function(e){
      e.preventDefault();
      const id = $(this).data('id');
      
      console.log('Edit button clicked, ID:', id); // Debug
      
      // Fallback: try direct approach first
      $.ajax({
        url: '<?= base_url('peminjaman/getpeminjamanrow') ?>',
        method: 'POST',
        data: {id_peminjaman: id},
        dataType: 'json',
        success: function(response){
          console.log('Response:', response); // Debug
          
          if(response && response.id_peminjaman) {
            $('#e_id').val(response.id_peminjaman);
            $('#e_id_barang').val(response.id_barang);
            $('#e_id_peminjam').val(response.nama_peminjam); // ISI DENGAN NAMA PEMINJAM
            $('#e_tanggal_pinjam').val(response.tanggal_pinjam);
            $('#e_batas_waktu').val(response.batas_waktu);
            $('#e_keterangan').val(response.keterangan || '');
            $('#editpeminjamanModal').modal('show');
          } else {
            alert('Data tidak ditemukan!');
          }
        },
        error: function(xhr, status, error) {
          console.log('AJAX Error:', error); // Debug
          console.log('XHR:', xhr.responseText); // Debug
          alert('Gagal mengambil data: ' + error);
        }
      });
    });

    // Delete peminjaman
    $('.btn-delete-peminjaman').off('click').on('click', function(e){
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
              // Show success message
              $('#success-message').html('<div class="alert alert-success" role="alert">Data peminjaman berhasil dihapus!</div>');
              // Reload DataTables
              $('#datatable-index').DataTable().ajax.reload(null, false);
              // Hide success message after 3 seconds
              setTimeout(function(){
                $('#success-message').html('');
              }, 3000);
            } else {
              alert('Gagal menghapus data: ' + response.message);
            }
          }
        });
      }
    });
  }
  
  // Initial bind
  bindEventHandlers();

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
          // Show success message
          $('#success-message').html('<div class="alert alert-success" role="alert">Data peminjaman berhasil diupdate!</div>');
          // Reload DataTables
          $('#datatable-index').DataTable().ajax.reload(null, false);
          // Hide success message after 3 seconds
          setTimeout(function(){
            $('#success-message').html('');
          }, 3000);
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
  $('#tanggal_pinjam').on('change', function(){
    const tanggalPinjam = $(this).val();
    $('#batas_waktu').attr('min', tanggalPinjam);
  });

  $('#e_tanggal_pinjam').on('change', function(){
    const tanggalPinjam = $(this).val();
    $('#e_batas_waktu').attr('min', tanggalPinjam);
  });
});
</script>
