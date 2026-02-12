<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Web pengelolaan barang <?= date('Y'); ?></span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="<?= base_url('Auth/logout') ?>">Logout</a>
            </div>
        </div>
    </div>
</div>


<script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>



<script src="<?= base_url('assets/js/sb-admin-2.min.js'); ?>"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/2.3.6/css/dataTables.dataTables.css">
<script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>

<script>
$(document).ready(function() {
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: "<?= site_url('master/getdatabarang') ?>",
            type: "POST"
        },
        columnDefs: [{
            targets: [0],
            orderable: false
        }],
        columns: [
            { data: 'no' },
            { data: 'kode_barang' },
            { data: 'nama_barang' },
            { data: 'nama_kategori' },
            { data: 'spesifikasi' },
            { data: 'satuan' },
            { data: 'harga_perolehan' },
            { data: 'tanggal_perolehan' },
            { data: 'umur_ekonomis' }
        ]
    });

    $('#datatable-kategori').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: "<?= site_url('master/getkategori') ?>",
            type: "POST"
        },
        columnDefs: [{
            targets: [0],
            orderable: false
        }],
        columns: [
            { data: 'no' },
            { data: 'nama_kategori' }
        ]
    });

    //supplier
    $('#suppliertable').DataTable({
        processing: true,
        serverSide: true,
        order: [[1, 'asc']],
        ajax: {
            url: '<?= site_url('master/getdatasupplier') ?>',
            type: 'POST'
        },
        columnDefs: [{
            targets: [0],
            orderable: false
        }],
        columns: [
            { data: 'no' },
            { data: 'nama_supplier' },
            { data: 'kontak' },
            { data: 'no_telp' },
            { data: 'kota' },
            { data: 'status' }
        ]
    });

    //lokasi
    $('#datatable-lokasi').DataTable({
        processing: true,
        serverSide: true,
        order: [[1, 'asc']],
        ajax: {
            url: '<?= site_url('master/getdatalokasi') ?>',
            type: 'POST'
        },
        columnDefs: [{
            targets: [0],
            orderable: false
        }],
        columns: [
            { data: 'no' },
            { data: 'kode_lokasi' },
            { data: 'nama_lokasi' },
            { data: 'gedung' },
            { data: 'lantai' },
            { data: 'keterangan' }
        ]
    });

    //pengguna 
    $('#datatable-pengguna').DataTable({
        processing: true,
        serverSide: true,
        order: [[1, 'asc']],
        ajax: {
            url: '<?= site_url('master/getdatapengguna') ?>',
            type: 'POST'
        },
        columnDefs: [{
            targets: [0],
            orderable: false
        }],
        columns: [
            { data: 'no' },
            { data: 'nama_pengguna' },
            { data: 'jenis_pengguna' },
            { data: 'no_identitas' },
            { data: 'divisi' },
            { data: 'unit' },
            { data: 'no_telp' },
            { data: 'status' },
            { data: 'keterangan' }
        ]
    });

//barang masuk 
    $('#datatable-barangmasuk').DataTable({
        processing: true,
        serverSide: true,
        order: [[1, 'asc']],
        ajax: {
            url: '<?= site_url('barang/getdatabarangmasuk') ?>',
            type: 'POST'
        },
        columnDefs: [{
            targets: [0],
            orderable: false
        }],
        columns: [
            { data: 'no' },
            { data: 'tgl_masuk' },
            { data: 'sumberbarang' },
            { data: 'jumlah' },
            { data: 'dokumen_pendukung' },
            { data: 'aksi', orderable: false }
        ]
    });


    // Barang Masuk: Edit -  modal
$(document).on('click', '.btn-edit-barangmasuk', function(e) {
    e.preventDefault();
    const id = $(this).data('id_barangin');
    $.post('<?= site_url('barang/getbarangmasukrow') ?>', { id_barangin: id }, function(res) {
        if (!res || !res.id_barangin) return;
        $('#e_id_barangin').val(res.id_barangin);
        $('#e_tgl_masuk').val(res.tgl_masuk);
        $('#e_sumberbarang').val(res.sumberbarang);
        $('#e_jumlah').val(res.jumlah);
        $('#current_dokumen_pendukung').text(res.dokumen_pendukung ? ('Dokumen saat ini: ' + res.dokumen_pendukung) : '');
        $('#err_tgl_masuk, #err_sumberbarang, #err_jumlah, #err_dokumen_pendukung').text('');
        $('#editBarangMasukModal').modal('show');
    }, 'json');
});

// Barang Masuk: Edit - submit 
$('#formeditBarangMasuk').on('submit', function(e) {
    e.preventDefault();
    $('#err_tgl_masuk, #err_sumberbarang, #err_jumlah, #err_dokumen_pendukung').text('');
    const form = document.getElementById('formeditBarangMasuk');
    const fd = new FormData(form);
    $.ajax({
        url: '<?= site_url('barang/updatebarangmasuk') ?>',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(res) {
            if (res && res.status) {
                $('#editBarangMasukModal').modal('hide');
                $('#datatable-barangmasuk').DataTable().ajax.reload(null, false);
            } else if (res && res.errors) {
                $('#err_tgl_masuk').text(res.errors.tgl_masuk || '');
                $('#err_sumberbarang').text(res.errors.sumberbarang || '');
                $('#err_jumlah').text(res.errors.jumlah || '');
                $('#err_dokumen_pendukung').text(res.errors.dokumen_pendukung || '');
            }
        }
    });
});

// Barang Masuk: Delete
$(document).on('click', '.btn-delete-barangmasuk', function(e) {
    e.preventDefault();
    if (!confirm('Yakin hapus data barang masuk ini?')) return;
    const id = $(this).data('id_barangin');
    $.post('<?= site_url('barang/deletebarangmasuk') ?>', { id_barangin: id }, function(res) {
        if (res && res.status) {
            $('#datatable-barangmasuk').DataTable().ajax.reload(null, false);
        } else {
            alert(res && res.message ? res.message : 'Gagal menghapus');
        }
    }, 'json');
});


    //barang keluar 
    $('#datatable-barangkeluar').DataTable({
        processing: false,
        serverSide: false,
        order: [[1, 'asc']],
        columnDefs: [{
            targets: [0],
            orderable: false
        }]
    });

    //mutasi 
    $('#datatable-mutasi').DataTable({
        processing: true,
        serverSide: true,
        order: [[1, 'asc']],
        columnDefs: [{
            targets: [0],
            orderable: false
        }],
        ajax: {
            url: '<?= site_url('mutasi/get_datatables') ?>',
            type: 'POST'
        },
        columns: [
            { data: 'no' },
            { data: 'id_barang' },
            { data: 'tanggal_mutasi' },
            { data: 'lokasi_asal' },
            { data: 'lokasi_tujuan' },
            { data: 'unit_asal' },
            { data: 'unit_tujuan' },
            { data: 'jumlah' },
            { data: 'penanggung_jawab' },
            { data: 'aksi', orderable: false }
        ]
    });  

    // Mutasi: Edit - modal
    $(document).on('click', '.btn-edit-mutasi', function(e) {
        e.preventDefault();
        const id = $(this).data('id_mutasi');
        $.post('<?= site_url('mutasi/getmutasirow') ?>', { id_mutasi: id }, function(res) {
            if (!res || !res.id_mutasi) return;
            $('#e_id_mutasi').val(res.id_mutasi);
            $('#e_id_barang').val(res.id_barang);
            $('#e_tanggal_mutasi').val(res.tanggal_mutasi);
            $('#e_lokasi_asal').val(res.lokasi_asal);
            $('#e_lokasi_tujuan').val(res.lokasi_tujuan);
            $('#e_unit_asal').val(res.unit_asal);
            $('#e_unit_tujuan').val(res.unit_tujuan);
            $('#e_jumlah').val(res.jumlah);
            $('#e_penanggung_jawab').val(res.penanggung_jawab);
            $('#err_id_barang, #err_tanggal_mutasi, #err_lokasi_asal, #err_lokasi_tujuan, #err_unit_asal, #err_unit_tujuan, #err_jumlah, #err_penanggung_jawab').text('');
            $('#editMutasiModal').modal('show');
        }, 'json');
    });

    // Mutasi: Edit - submit
    $('#formeditMutasi').on('submit', function(e) {
        e.preventDefault();
        $('#err_id_barang, #err_tanggal_mutasi, #err_lokasi_asal, #err_lokasi_tujuan, #err_unit_asal, #err_unit_tujuan, #err_jumlah, #err_penanggung_jawab').text('');
        $.post('<?= site_url('mutasi/updatemutasi') ?>', $(this).serialize(), function(res) {
            if (res && res.status) {
                $('#editMutasiModal').modal('hide');
                $('#datatable-mutasi').DataTable().ajax.reload(null, false);
            } else if (res && res.errors) {
                $('#err_id_barang').text(res.errors.id_barang || '');
                $('#err_tanggal_mutasi').text(res.errors.tanggal_mutasi || '');
                $('#err_lokasi_asal').text(res.errors.lokasi_asal || '');
                $('#err_lokasi_tujuan').text(res.errors.lokasi_tujuan || '');
                $('#err_unit_asal').text(res.errors.unit_asal || '');
                $('#err_unit_tujuan').text(res.errors.unit_tujuan || '');
                $('#err_jumlah').text(res.errors.jumlah || '');
                $('#err_penanggung_jawab').text(res.errors.penanggung_jawab || '');
            }
        }, 'json');
    });

    // Mutasi: Delete
    $(document).on('click', '.btn-delete-mutasi', function(e) {
        e.preventDefault();
        if (!confirm('Yakin hapus data mutasi ini?')) return;
        const id = $(this).data('id_mutasi');
        $.post('<?= site_url('mutasi/deletemutasi') ?>', { id_mutasi: id }, function(res) {
            if (res && res.status) {
                $('#datatable-mutasi').DataTable().ajax.reload(null, false);
            } else {
                alert(res && res.message ? res.message : 'Gagal menghapus');
            }
        }, 'json');
    });
});
</script>
