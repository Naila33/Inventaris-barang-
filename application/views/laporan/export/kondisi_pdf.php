<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KONDISI BARANG</h1>
        <p>Tanggal Cetak: <?= date('d/m/Y H:i:s') ?></p>
        <p>Periode: <?= ($tanggal_awal && $tanggal_akhir) ? date('d/m/Y', strtotime($tanggal_awal)) . ' - ' . date('d/m/Y', strtotime($tanggal_akhir)) : 'Semua Data' ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Kode Barang</th>
                <th width="25%">Nama Barang</th>
                <th width="15%">Kategori</th>
                <th width="15%">Lokasi</th>
                <th width="10%">Kondisi</th>
                <th width="18%">Spesifikasi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($laporan_data as $item): ?>
            <tr>
                <td class="text-center"><?= $no ?></td>
                <td><?= $item['kode_barang'] ?></td>
                <td><?= $item['nama_barang'] ?></td>
                <td><?= $item['nama_kategori'] ?? '-' ?></td>
                <td><?= $item['nama_lokasi'] ?? '-' ?></td>
                <td class="text-center"><?= $item['kondisi'] ?? 'Baik' ?></td>
                <td><?= $item['spesifikasi'] ?? '-' ?></td>
            </tr>
            <?php $no++; endforeach; ?>
            
            <?php if(empty($laporan_data)): ?>
            <tr>
                <td colspan="7" class="text-center">Tidak ada data</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
