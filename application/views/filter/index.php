<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Filter Data Barang</h5>
        </div>

        <div class="card-body">

            <!-- FILTER FORM -->
            <div class="row mb-4">

                <div class="col-md-2">
                    <select id="kode_barang" class="form-control">
                        <option value="">Kode Barang</option>
                        <?php foreach($kode as $k): ?>
                            <option value="<?= $k->kode_barang ?>">
                                <?= $k->kode_barang ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="text" id="nama_barang" class="form-control" placeholder="Nama Barang">
                </div>

                <div class="col-md-2">
                    <select id="kategori" class="form-control">
                        <option value="">Kategori</option>
                        <?php foreach($kategori as $k): ?>
                            <option value="<?= $k->kategori ?>">
                                <?= $k->kategori ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <select id="lokasi" class="form-control">
                        <option value="">Lokasi</option>
                        <?php foreach($lokasi as $l): ?>
                            <option value="<?= $l->lokasi ?>">
                                <?= $l->lokasi ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <select id="kondisi" class="form-control">
                        <option value="">Kondisi</option>
                        <option value="Baik">Baik</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="date" id="tanggal_awal" class="form-control mb-2">
                    <input type="date" id="tanggal_akhir" class="form-control">
                </div>

            </div>

            <!-- TABLE -->
            <div class="table-responsive">
                <table id="table-filter" class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Kondisi</th>
                            <th>Tanggal Masuk</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>