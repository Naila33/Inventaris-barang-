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
            targets: [0, 9],
            orderable: false
        }],
        columns: [
            { data: 'no' },
            { data: 'kode_barang' },
            { data: 'nama_barang' },
            { data: 'kategori' },
            { data: 'spesifikasi' },
            { data: 'satuan' },
            { data: 'harga_perolehan' },
            { data: 'tanggal_perolehan' },
            { data: 'umur_ekonomis' },
            { data: 'aksi' }
        ]
    });

    // Edit button click handler
    $(document).on('click', '.btn-edit-barang', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        console.log('Edit button clicked, ID:', id);
        $.post('<?= site_url('master/getdatabarangrow') ?>', { id_barang: id }, function(res) {
            console.log('Response from server:', res);
            if (!res || !res.id_barang) {
                console.log('No valid response or missing id_barang');
                return;
            }
            $('#e_id').val(res.id_barang);
            $('#e_nama_barang').val(res.nama_barang);
            $('#e_kategori').val(res.kategori);
            $('#e_spesifikasi').val(res.spesifikasi);
            $('#e_satuan').val(res.satuan);
            $('#e_harga_perolehan').val(res.harga_perolehan);
            $('#e_tanggal_perolehan').val(res.tanggal_perolehan);
            $('#e_umur_ekonomis').val(res.umur_ekonomis);
            $('.text-danger').text('');
            $('#editdatabarangModal').modal('show');
        }, 'json');
    });

    // Update form submit handler
    $('#formeditdatabarang').on('submit', function(e) {
        e.preventDefault();
        $.post('<?= site_url('master/updatedatabarang') ?>', $(this).serialize(), function(res) {
            if (res && res.status) {
                $('#editdatabarangModal').modal('hide');
                $('#datatable').DataTable().ajax.reload(null, false);
            } else if (res && res.errors) {
                $('#err_nama_barang').text(res.errors.nama_barang || '');
                $('#err_kategori').text(res.errors.kategori || '');
                $('#err_spesifikasi').text(res.errors.spesifikasi || '');
                $('#err_satuan').text(res.errors.satuan || '');
                $('#err_harga_perolehan').text(res.errors.harga_perolehan || '');
                $('#err_tanggal_perolehan').text(res.errors.tanggal_perolehan || '');
                $('#err_umur_ekonomis').text(res.errors.umur_ekonomis || '');
            }
        }, 'json');
    });
    $(document).on('click', '.btn-delete-barang', function(e) {
        e.preventDefault();
        if (!confirm('Yakin hapus data barang ini?')) return;
        const id = $(this).data('id');
        $.post('<?= site_url('master/deletedatabarang') ?>', { id_barang: id }, function(res) {
            if (res && res.status) {
                $('#datatable').DataTable().ajax.reload(null, false);
            } else {
                alert(res && res.message ? res.message : 'Gagal menghapus');
            }
        }, 'json');
    });
});
</script>

<script>
$(document).ready(function () {

    let table = $('#tableBarang').DataTable();

    $('#tableBarang tbody').on('click', '.btn-edit', function () {

        let btn = $(this);

        $('#edit_id').val(btn.data('id'));
        $('#edit_nama').val(btn.data('nama'));
        $('#edit_kategori').val(btn.data('kategori'));
        $('#edit_harga').val(btn.data('harga'));
        $('#edit_spesifikasi').val(btn.data('spesifikasi'));
        $('#edit_satuan').val(btn.data('satuan'));
        $('#edit_tanggal').val(btn.data('tanggal'));
        $('#edit_umur').val(btn.data('umur'));

        $('#modalEdit').modal('show');
    });

});
</script>

<script>
$(document).on('click', '.btn-hapus', function () {
    $('#hapus_id').val($(this).data('id'));
    $('#hapus_nama').val($(this).data('nama'));
    $('#modalPenghapusan').modal('show');
});
</script>

<script>
    $('#tablePenghapusan').DataTable();
</script>

<script>
$(document).ready(function() {

    var table = $('#table-filter').DataTable({
        "processing": true,
        "responsive": true,
        "ajax": {
            "url": "<?= base_url('filter/ajax_list') ?>",
            "type": "POST",
            "data": function(d) {
                d.kode_barang  = $('#kode_barang').val();
                d.nama_barang  = $('#nama_barang').val();
                d.kategori     = $('#kategori').val();
                d.lokasi       = $('#lokasi').val();
                d.kondisi      = $('#kondisi').val();
                d.tanggal_awal = $('#tanggal_awal').val();
                d.tanggal_akhir= $('#tanggal_akhir').val();
            }
        }
    });

    $('#kode_barang, #nama_barang, #kategori, #lokasi, #kondisi, #tanggal_awal, #tanggal_akhir')
        .on('change keyup', function() {
            table.ajax.reload();
        });

});
</script>