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
            { data: 'lokasi' },
            { data: 'harga_perolehan' },
            { data: 'tanggal_perolehan' },
            { data: 'umur_ekonomis' },
            { data: 'aksi', orderable: false }
        ]
    });

    $('#formAddBarang').on('submit', function(e) {
        e.preventDefault();
        $('#err_nama_barang, #err_id_kategori, #err_spesifikasi, #err_satuan, #err_lokasi, #err_harga_perolehan, #err_tanggal_perolehan, #err_umur_ekonomis').text('');
        $.post('<?= site_url('master/addbarang') ?>', $(this).serialize(), function(res) {
            if (res && res.status) {
                $('#newBarangModal').modal('hide');
                $('#datatable').DataTable().ajax.reload(null, false);
                $('#formAddBarang')[0].reset();
            } else if (res && res.errors) {
                $('#err_nama_barang').text(res.errors.nama_barang || '');
                $('#err_id_kategori').text(res.errors.id_kategori || '');
                $('#err_spesifikasi').text(res.errors.spesifikasi || '');
                $('#err_satuan').text(res.errors.satuan || '');
                $('#err_lokasi').text(res.errors.lokasi || ''); 
                $('#err_harga_perolehan').text(res.errors.harga_perolehan || '');
                $('#err_tanggal_perolehan').text(res.errors.tanggal_perolehan || '');
                $('#err_umur_ekonomis').text(res.errors.umur_ekonomis || '');
            } else {
                alert(res && res.message ? res.message : 'Gagal menyimpan');
            }
        }, 'json');
    });

    $(document).on('click', '.btn-edit-barang', function(e) {
        e.preventDefault();
        const id = $(this).data('id_barang');
        $('#err_e_nama_barang, #err_e_id_kategori, #err_e_spesifikasi, #err_e_satuan, #err_e_lokasi, #err_e_harga_perolehan, #err_e_tanggal_perolehan, #err_e_umur_ekonomis').text('');
        $.post('<?= site_url('master/getbarangrow') ?>', { id_barang: id }, function(res) {
            if (!res || !res.id_barang) return;
            $('#e_id_barang').val(res.id_barang);
            $('#e_nama_barang').val(res.nama_barang || '');
            $('#e_id_kategori').val(res.id_kategori || '');
            $('#e_spesifikasi').val(res.spesifikasi || '');
            $('#e_satuan').val(res.satuan || '');
            $('#e_lokasi').val(res.id_lokasi || ''); 
            $('#e_harga_perolehan').val(res.harga_perolehan || '');
            $('#e_tanggal_perolehan').val(res.tanggal_perolehan || '');
            $('#e_umur_ekonomis').val(res.umur_ekonomis || '');
            $('#editBarangModal').modal('show');
        }, 'json');
    });

    $('#formEditBarang').on('submit', function(e) {
        e.preventDefault();
        $('#err_e_nama_barang, #err_e_id_kategori, #err_e_spesifikasi, #err_e_satuan, #err_e_lokasi, #err_e_harga_perolehan, #err_e_tanggal_perolehan, #err_e_umur_ekonomis').text('');
        $.post('<?= site_url('master/updatebarang') ?>', $(this).serialize(), function(res) {
            if (res && res.status) {
                $('#editBarangModal').modal('hide');
                $('#datatable').DataTable().ajax.reload(null, false);
            } else if (res && res.errors) {
                $('#err_e_nama_barang').text(res.errors.nama_barang || '');
                $('#err_e_id_kategori').text(res.errors.id_kategori || '');
                $('#err_e_spesifikasi').text(res.errors.spesifikasi || '');
                $('#err_e_satuan').text(res.errors.satuan || '');
                $('#err_e_lokasi').text(res.errors.lokasi || ''); 
                $('#err_e_harga_perolehan').text(res.errors.harga_perolehan || '');
                $('#err_e_tanggal_perolehan').text(res.errors.tanggal_perolehan || '');
                $('#err_e_umur_ekonomis').text(res.errors.umur_ekonomis || '');
            } else {
                alert(res && res.message ? res.message : 'Gagal menyimpan');
            }
        }, 'json');
    });

    $(document).on('click', '.btn-delete-barang', function(e) {
        e.preventDefault();
        if (!confirm('Yakin hapus data barang ini?')) return;
        const id = $(this).data('id_barang');
        $.post('<?= site_url('master/deletebarang') ?>', { id_barang: id }, function(res) {
            if (res && res.status) {
                $('#datatable').DataTable().ajax.reload(null, false);
            } else {
                alert(res && res.message ? res.message : 'Gagal menghapus');
            }
        }, 'json');
    });

    //kondisi
    $('#datatable-kondisi').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: '<?= site_url('kondisi/getdatakondisi') ?>',
            type: 'POST'
        },
        columnDefs: [{
            targets: [0, 4],
            orderable: false
        }],
        columns: [
            { data: 'no' },
            { data: 'nama_barang' },
            { data: 'kode_barang' },
            { data: 'kondisi' },
            { data: 'aksi', orderable: false }
        ]
    });

    // Kondisi: open edit modal
    $(document).on('click', '.btn-edit-kondisi', function(e) {
        e.preventDefault();
        const id = $(this).data('id_barang');
        $('#err_kondisi').text('');
        $('#err_id_barang').text('');
        $.post('<?= site_url('kondisi/getkondisirow') ?>', { id_barang: id }, function(res) {
            if (!res || !res.id_barang) return;
            const currentId = String(res.id_barang);
            $.get('<?= site_url('kondisi/getbarangoptions') ?>', function(opts) {
                const $sel = $('#e_id_barang');
                $sel.empty();
                $sel.append('<option value="">Pilih barang</option>');
                if (Array.isArray(opts)) {
                    opts.forEach(function(b) {
                        if (!b || b.id_barang === undefined || b.id_barang === null) return;
                        const idVal = String(b.id_barang);
                        const nama = b.nama_barang ? String(b.nama_barang) : '';
                        const kode = b.kode_barang ? String(b.kode_barang) : '';
                        const text = (nama ? nama : idVal) + (kode ? (' (' + kode + ')') : '');
                        $sel.append('<option value="' + idVal.replace(/"/g, '&quot;') + '">' + text + '</option>');
                    });
                }
                $sel.val(currentId);
                $('#e_kondisi').val(res.kondisi || '');
                $('#editKondisiModal').modal('show');
            }, 'json');
        }, 'json');
    });

    $('#formEditKondisi').on('submit', function(e) {
        e.preventDefault();
        $('#err_kondisi').text('');
        $('#err_id_barang').text('');
        const form = document.getElementById('formEditKondisi');
        const fd = new FormData(form);
        $.ajax({
            url: '<?= site_url('kondisi/updatekondisi_ajax') ?>',
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(res) {
                if (res && res.status) {
                    $('#editKondisiModal').modal('hide');
                    $('#datatable-kondisi').DataTable().ajax.reload(null, false);
                } else if (res && res.errors) {
                    $('#err_id_barang').text(res.errors.id_barang || '');
                    $('#err_kondisi').text(res.errors.kondisi || '');
                }
            }
        });
    });

    // Kondisi: delete
    $(document).on('click', '.btn-delete-kondisi', function(e) {
        e.preventDefault();
        if (!confirm('Yakin hapus data barang ini?')) return;
        const id = $(this).data('id_barang');
        $.post('<?= site_url('kondisi/deletekondisi_ajax') ?>', { id_barang: id }, function(res) {
            if (res && res.status) {
                $('#datatable-kondisi').DataTable().ajax.reload(null, false);
            } else {
                alert(res && res.message ? res.message : 'Gagal menghapus');
            }
        }, 'json');
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
            { data: 'nama_kategori' },
            { data: 'aksi', orderable: false }
        ]
    });

    $(document).on('click', '.btn-delete-kategori', function(e) {
        e.preventDefault();
        if (!confirm('Yakin hapus kategori ini?')) return;
        const id = $(this).data('id_kategori');
        $.post('<?= site_url('master/deletekategori') ?>', { id_kategori: id }, function(res) {
            if (res && res.status) {
                $('#datatable-kategori').DataTable().ajax.reload(null, false);
            } else {
                alert(res && res.message ? res.message : 'Gagal menghapus');
            }
        }, 'json');
    });

    // Kategori: Edit - modal
    $(document).on('click', '.btn-edit-kategori', function(e) {
        e.preventDefault();
        const id = $(this).data('id_kategori');
        $('#err_e_nama_kategori').text('');
        $.post('<?= site_url('master/getkategorirow') ?>', { id_kategori: id }, function(res) {
            if (!res || !res.id_kategori) return;
            $('#e_id_kategori').val(res.id_kategori);
            $('#e_nama_kategori').val(res.nama_kategori || '');
            $('#editKategoriModal').modal('show');
        }, 'json');
    });

    // Kategori: Edit - submit
    $('#formEditKategori').on('submit', function(e) {
        e.preventDefault();
        $('#err_e_nama_kategori').text('');
        $.post('<?= site_url('master/updatekategori') ?>', $(this).serialize(), function(res) {
            if (res && res.status) {
                $('#editKategoriModal').modal('hide');
                $('#datatable-kategori').DataTable().ajax.reload(null, false);
            } else if (res && res.errors) {
                $('#err_e_nama_kategori').text(res.errors.nama_kategori || '');
            } else {
                alert(res && res.message ? res.message : 'Gagal menyimpan');
            }
        }, 'json');
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
            { data: 'status' },
            { data: 'aksi', orderable: false }
        ]
    });

     // supplier: Edit - modal
    $(document).on('click', '.btn-edit-supplier', function(e) {
        e.preventDefault();
        const id = $(this).data('id_supplier');
        $('#err_e_nama_supplier, #err_e_kontak, #err_e_no_telp, #err_e_kota, #err_e_status').text('');
        $.post('<?= site_url('master/getsupplierrow') ?>', { id_supplier: id }, function(res) {
            if (!res || !res.id_supplier) return;
            $('#e_id_supplier').val(res.id_supplier);
            $('#e_nama_supplier').val(res.nama_supplier || '');
            $('#e_kontak').val(res.kontak || '');
            $('#e_no_telp').val(res.no_telp || '');
            $('#e_kota').val(res.kota || '');
            $('#e_status').val(res.status || '');
            $('#editSupplierModal').modal('show');
        }, 'json');
    });

    // supplier: Edit - submit
    $('#formEditSupplier').on('submit', function(e) {
        e.preventDefault();
        $('#err_e_nama_supplier, #err_e_kontak, #err_e_no_telp, #err_e_kota, #err_e_status').text('');
        $.post('<?= site_url('master/updatesupplier') ?>', $(this).serialize(), function(res) {
            if (res && res.status) {
                $('#editSupplierModal').modal('hide');
                $('#suppliertable').DataTable().ajax.reload(null, false);
            } else if (res && res.errors) {
                $('#err_e_nama_supplier').text(res.errors.nama_supplier || '');
                $('#err_e_kontak').text(res.errors.kontak || '');
                $('#err_e_no_telp').text(res.errors.no_telp || '');
                $('#err_e_kota').text(res.errors.kota || '');
                $('#err_e_status').text(res.errors.status || '');
            } else {
                alert(res && res.message ? res.message : 'Gagal menyimpan');
            }
        }, 'json');
    });

    // Supplier: Delete
    $(document).on('click', '.btn-delete-supplier', function(e) {
        e.preventDefault();
        if (!confirm('Yakin hapus supplier ini?')) return;
        const id = $(this).data('id_supplier');
        $.post('<?= site_url('master/deletesupplier') ?>', { id_supplier: id }, function(res) {
            if (res && res.status) {
                $('#suppliertable').DataTable().ajax.reload(null, false);
            } else {
                alert(res && res.message ? res.message : 'Gagal menghapus');
            }
        }, 'json');
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
            { data: 'keterangan' },
            { data: 'aksi', orderable: false }
        ]
    });

    // Lokasi: Edit - modal
    $(document).on('click', '.btn-edit-lokasi', function(e) {
        e.preventDefault();
        const id = $(this).data('id_lokasi');
        $('#err_e_kode_lokasi, #err_e_nama_lokasi, #err_e_gedung, #err_e_lantai, #err_e_keterangan').text('');
        $.post('<?= site_url('master/getlokasirow') ?>', { id_lokasi: id }, function(res) {
            const rowId = (res && (res.id_lokasi || res.kode_lokasi)) ? (res.id_lokasi || res.kode_lokasi) : '';
            if (!rowId) return;
            $('#e_id_lokasi').val(rowId);
            $('#e_kode_lokasi').val(res.kode_lokasi || '');
            $('#e_nama_lokasi').val(res.nama_lokasi || '');
            $('#e_gedung').val(res.gedung || '');
            $('#e_lantai').val(res.lantai || '');
            $('#e_keterangan').val(res.keterangan || '');
            $('#editLokasiModal').modal('show');
        }, 'json');
    });

    // Lokasi: Edit - submit
    $('#formEditLokasi').on('submit', function(e) {
        e.preventDefault();
        $('#err_e_kode_lokasi, #err_e_nama_lokasi, #err_e_gedung, #err_e_lantai, #err_e_keterangan').text('');
        $.post('<?= site_url('master/updatelokasi') ?>', $(this).serialize(), function(res) {
            if (res && res.status) {
                $('#editLokasiModal').modal('hide');
                $('#datatable-lokasi').DataTable().ajax.reload(null, false);
            } else if (res && res.errors) {
                $('#err_e_kode_lokasi').text(res.errors.kode_lokasi || '');
                $('#err_e_nama_lokasi').text(res.errors.nama_lokasi || '');
                $('#err_e_gedung').text(res.errors.gedung || '');
                $('#err_e_lantai').text(res.errors.lantai || '');
                $('#err_e_keterangan').text(res.errors.keterangan || '');
            } else {
                alert(res && res.message ? res.message : 'Gagal menyimpan');
            }
        }, 'json');
    });

    // Lokasi: Delete
    $(document).on('click', '.btn-delete-lokasi', function(e) {
        e.preventDefault();
        if (!confirm('Yakin hapus lokasi ini?')) return;
        const id = $(this).data('id_lokasi');
        $.post('<?= site_url('master/deletelokasi') ?>', { id_lokasi: id }, function(res) {
            if (res && res.status) {
                $('#datatable-lokasi').DataTable().ajax.reload(null, false);
            } else {
                alert(res && res.message ? res.message : 'Gagal menghapus');
            }
        }, 'json');
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
            { data: 'keterangan' },
            { data: 'aksi', orderable: false }
        ]
    });

    // pengguna: Edit - modal
    $(document).on('click', '.btn-edit-pengguna', function(e) {
        e.preventDefault();
        const id = $(this).data('id_pengguna');
        $('#err_e_nama_pengguna, #err_e_jenis_pengguna, #err_e_no_identitas, #err_e_divisi, #err_e_unit, #err_e_no_telp, #err_e_status, #err_e_keterangan').text('');
        $.post('<?= site_url('master/getpenggunarow') ?>', { id_pengguna: id }, function(res) {
            if (!res || !res.id_pengguna) return;
            $('#e_id_pengguna').val(res.id_pengguna);
            $('#e_nama_pengguna').val(res.nama_pengguna || '');
            $('#e_jenis_pengguna').val(res.jenis_pengguna || '');
            $('#e_no_identitas').val(res.no_identitas || '');
            $('#e_divisi').val(res.divisi || '');
            $('#e_unit').val(res.unit || '');
            $('#e_no_telp').val(res.no_telp || '');
            $('#e_status').val(res.status || '');
            $('#e_keterangan').val(res.keterangan || '');
            $('#editPenggunaModal').modal('show');
        }, 'json');
    });

    // pengguna: Edit - submit
    $('#formEditPengguna').on('submit', function(e) {
        e.preventDefault();
        $('#err_e_nama_pengguna, #err_e_jenis_pengguna, #err_e_no_identitas, #err_e_divisi, #err_e_unit, #err_e_no_telp, #err_e_status, #err_e_keterangan').text('');
        $.post('<?= site_url('master/updatepengguna') ?>', $(this).serialize(), function(res) {
            if (res && res.status) {
                $('#editPenggunaModal').modal('hide');
                $('#datatable-pengguna').DataTable().ajax.reload(null, false);
            } else if (res && res.errors) {
                $('#err_e_nama_pengguna').text(res.errors.nama_pengguna || '');
                $('#err_e_jenis_pengguna').text(res.errors.jenis_pengguna || '');
                $('#err_e_no_identitas').text(res.errors.no_identitas || '');
                $('#err_e_divisi').text(res.errors.divisi || '');
                $('#err_e_unit').text(res.errors.unit || '');
                $('#err_e_no_telp').text(res.errors.no_telp || '');
                $('#err_e_status').text(res.errors.status || '');
                $('#err_e_keterangan').text(res.errors.keterangan || '');
            } else {
                alert(res && res.message ? res.message : 'Gagal menyimpan');
            }
        }, 'json');
    });

    // Pengguna: Delete
    $(document).on('click', '.btn-delete-pengguna', function(e) {
        e.preventDefault();
        if (!confirm('Yakin hapus pengguna ini?')) return;
        const id = $(this).data('id_pengguna');
        $.post('<?= site_url('master/deletepengguna') ?>', { id_pengguna: id }, function(res) {
            if (res && res.status) {
                $('#datatable-pengguna').DataTable().ajax.reload(null, false);
            } else {
                alert(res && res.message ? res.message : 'Gagal menghapus');
            }
        }, 'json');
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
        processing: true,
        serverSide: true,
        order: [[1, 'asc']],
        ajax: {
            url: '<?= site_url('barang/getdatabarangkeluar') ?>',
            type: 'POST'
        },
        columnDefs: [{
            targets: [0],
            orderable: false
        }],
        columns: [
            { data: 'no' },
            { data: 'id_barang' },
            { data: 'tgl_keluar' },
            { data: 'jenis_tras' },
            { data: 'tujuan' },
            { data: 'pj' },
            { data: 'jumlah' },
            { data: 'batas_wp' },
            { data: 'tgl_kembali' },
            { data: 'status_keterlambatan' },
            { data: 'aksi', orderable: false }
        ]
    });

    $('#formAddBarangKeluar').on('submit', function(e) {
        e.preventDefault();
        $('#err_o_id_barang, #err_o_tgl_keluar, #err_o_jenis_tras, #err_o_tujuan, #err_o_pj, #err_o_jumlah, #err_o_batas_wp, #err_o_tgl_kembali, #err_o_status_keterlambatan').text('');
        $.post('<?= site_url('barang/addbarangkeluar') ?>', $(this).serialize(), function(res) {
            if (res && res.status) {
                $('#newBarangKeluarModal').modal('hide');
                $('#datatable-barangkeluar').DataTable().ajax.reload(null, false);
                $('#formAddBarangKeluar')[0].reset();
            } else if (res && res.errors) {
                $('#err_o_id_barang').text(res.errors.id_barang || '');
                $('#err_o_tgl_keluar').text(res.errors.tgl_keluar || '');
                $('#err_o_jenis_tras').text(res.errors.jenis_tras || '');
                $('#err_o_tujuan').text(res.errors.tujuan || '');
                $('#err_o_pj').text(res.errors.pj || '');
                $('#err_o_jumlah').text(res.errors.jumlah || '');
                $('#err_o_batas_wp').text(res.errors.batas_wp || '');
                $('#err_o_tgl_kembali').text(res.errors.tgl_kembali || '');
                $('#err_o_status_keterlambatan').text(res.errors.status_keterlambatan || '');
            }
        }, 'json');
    });

    $(document).on('click', '.btn-delete-barangkeluar', function(e) {
        e.preventDefault();
        if (!confirm('Yakin hapus data barang keluar ini?')) return;
        const id = $(this).data('id_barangout');
        $.post('<?= site_url('barang/deletebarangkeluar') ?>', { id_barangout: id }, function(res) {
            if (res && res.status) {
                $('#datatable-barangkeluar').DataTable().ajax.reload(null, false);
            } else {
                alert(res && res.message ? res.message : 'Gagal menghapus');
            }
        }, 'json');
    });

    $(document).on('click', '.btn-edit-barangkeluar', function(e) {
        e.preventDefault();
        const id = $(this).data('id_barangout');
        $('#err_e_o_id_barang, #err_e_o_tgl_keluar, #err_e_o_jenis_tras, #err_e_o_tujuan, #err_e_o_pj, #err_e_o_jumlah, #err_e_o_batas_wp, #err_e_o_tgl_kembali, #err_e_o_status_keterlambatan').text('');
        $.post('<?= site_url('barang/getbarangkeluarrow') ?>', { id_barangout: id }, function(res) {
            if (!res || !res.id_barangout) return;
            $('#e_id_barangout').val(res.id_barangout);
            $('#e_o_id_barang').val(res.id_barang || '');
            $('#e_o_tgl_keluar').val(res.tgl_keluar || '');
            $('#e_o_jenis_tras').val(res.jenis_tras || '');
            $('#e_o_tujuan').val(res.tujuan || '');
            $('#e_o_pj').val(res.pj || '');
            $('#e_o_jumlah').val(res.jumlah || '');
            $('#e_o_batas_wp').val(res.batas_wp || '');
            $('#e_o_tgl_kembali').val(res.tgl_kembali || '');
            $('#e_o_status_keterlambatan').val(res.status_keterlambatan || '');
            $('#editBarangKeluarModal').modal('show');
        }, 'json');
    });

    $('#formEditBarangKeluar').on('submit', function(e) {
        e.preventDefault();
        $('#err_e_o_id_barang, #err_e_o_tgl_keluar, #err_e_o_jenis_tras, #err_e_o_tujuan, #err_e_o_pj, #err_e_o_jumlah, #err_e_o_batas_wp, #err_e_o_tgl_kembali, #err_e_o_status_keterlambatan').text('');
        $.post('<?= site_url('barang/updatebarangkeluar') ?>', $(this).serialize(), function(res) {
            if (res && res.status) {
                $('#editBarangKeluarModal').modal('hide');
                $('#datatable-barangkeluar').DataTable().ajax.reload(null, false);
            } else if (res && res.errors) {
                $('#err_e_o_id_barang').text(res.errors.id_barang || '');
                $('#err_e_o_tgl_keluar').text(res.errors.tgl_keluar || '');
                $('#err_e_o_jenis_tras').text(res.errors.jenis_tras || '');
                $('#err_e_o_tujuan').text(res.errors.tujuan || '');
                $('#err_e_o_pj').text(res.errors.pj || '');
                $('#err_e_o_jumlah').text(res.errors.jumlah || '');
                $('#err_e_o_batas_wp').text(res.errors.batas_wp || '');
                $('#err_e_o_tgl_kembali').text(res.errors.tgl_kembali || '');
                $('#err_e_o_status_keterlambatan').text(res.errors.status_keterlambatan || '');
            } else {
                alert(res && res.message ? res.message : 'Gagal menyimpan');
            }
        }, 'json');
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
                d.id_kategori  = $('#id_kategori').val();
                d.id_lokasi    = $('#id_lokasi').val();
                d.kondisi      = $('#kondisi').val();
                d.tanggal_awal = $('#tanggal_awal').val();
                d.tanggal_akhir= $('#tanggal_akhir').val();
            }
        }
    });

    $('#kode_barang, #nama_barang, #id_kategori, #id_lokasi, #kondisi, #tanggal_awal, #tanggal_akhir')
        .on('change keyup', function() {
            table.ajax.reload();
        });

});
</script>