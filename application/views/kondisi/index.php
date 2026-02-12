<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Kondisi Barang</h1>

    <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <table id="tableKondisi" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kode Barang</th>
                <th>Kondisi</th>
                <th>Ubah Kondisi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($barang)) : ?>
                <?php $no = 1; foreach ($barang as $b) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $b->nama_barang; ?></td>
                        <td><?= $b->kode_barang; ?></td>
                        <td><?= $b->kondisi; ?></td>
                        <td>
                            <a href="<?= base_url('kondisi/edit/'.$b->id_barang); ?>" 
                               class="btn btn-warning btn-sm">
                               Edit
                            </a>

                            <a href="<?= base_url('kondisi/hapus/'.$b->id_barang); ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin mau hapus data ini?')">
                               Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<!-- DataTables JavaScript -->
<script>
$(document).ready(function() {
    $('#tableKondisi').DataTable({
        columnDefs: [
            { searchable: false, targets: [0, 4] }
        ]
    });
});
</script>
