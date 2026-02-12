<div class="container mt-4">
    <h4>Edit Kondisi Barang</h4>

    <form method="post" action="<?= base_url('kondisi/update'); ?>">
        <input type="hidden" name="id_barang" value="<?= $barang->id_barang; ?>">

        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" class="form-control" 
                   value="<?= $barang->nama_barang; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Kondisi</label>
            <select name="kondisi" class="form-control" required>
                <option value="Baik" <?= $barang->kondisi == 'Baik' ? 'selected' : '' ?>>Baik</option>
                <option value="Rusak Ringan" <?= $barang->kondisi == 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                <option value="Rusak Berat" <?= $barang->kondisi == 'Rusak Berat' ? 'selected' : '' ?>>Rusak Berat</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= base_url('kondisi'); ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>
