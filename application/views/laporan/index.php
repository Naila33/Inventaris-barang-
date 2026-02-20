<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

  <!-- Form Pilih Laporan -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Laporan Inventaris</h6>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="jenis_laporan">Pilih Jenis Laporan</label>
                <select class="form-control" id="jenis_laporan" onchange="loadData()">
                    <option value="">-- Pilih Laporan --</option>
                    <option value="inventaris">Daftar Inventaris</option>
                    <option value="barang_masuk">Barang Masuk</option>
                    <option value="barang_keluar">Barang Keluar</option>
                    <option value="peminjaman">Peminjaman</option>
                    <option value="kondisi">Kondisi Barang</option>
                    <option value="penghapusan">Penghapusan Barang</option>
                </select>
            </div>
            <div class="col-md-6">
                <label>&nbsp;</label><br>
            </div>
        </div>
    <p class="mt-2">Memuat data...</p>
  </div>

  <!-- Loading -->
  <div id="loading" class="text-center py-4" style="display: none;">
    <div class="spinner-border text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
    <p class="mt-2">Memuat data...</p>
  </div>

  <!-- Preview Data -->
  <div id="previewSection" style="display: none;">
    <!-- Info Laporan -->
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Data Laporan</h6>
        <div>
          <button onclick="exportPDF()" class="btn btn-danger btn-sm">
            <i class="fas fa-file-pdf"></i> Export PDF
          </button>
          <button onclick="exportExcel()" class="btn btn-success btn-sm ml-2">
            <i class="fas fa-file-excel"></i> Export Excel
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-3">
            <strong>Jenis Laporan:</strong><br>
            <span class="badge badge-primary" id="jenisLaporanBadge"></span>
          </div>
          <div class="col-md-3">
            <strong>Tanggal Cetak:</strong><br>
            <span id="tanggalCetak"></span>
          </div>
          <div class="col-md-3">
            <strong>Periode:</strong><br>
            <span id="periode"></span>
          </div>
          <div class="col-md-3">
            <strong>Total Data:</strong><br>
            <span id="totalData"></span> Record
          </div>
        </div>

        <!-- Tabel Data -->
        <div class="table-responsive">
          <table class="table table-bordered" id="tabelLaporan" width="100%" cellspacing="0">
            <thead id="tableHeader">
              <!-- Header akan diisi via JavaScript -->
            </thead>
            <tbody id="tableBody">
              <!-- Data akan diisi via JavaScript -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Empty State -->
  <div id="emptyState" class="text-center py-5">
    <i class="fas fa-clipboard-list fa-4x text-gray-300 mb-3"></i>
    <h5 class="text-gray-600">Pilih Jenis Laporan</h5>
    <p class="text-muted">Silakan pilih jenis laporan untuk melihat data</p>
  </div>

</div>

<style>
@media print {
  .btn, .d-flex .btn {
    display: none !important;
  }
  .card {
    box-shadow: none !important;
    border: 1px solid #ddd !important;
  }
}
</style>

<script>
let currentData = [];
let currentJenisLaporan = '';

// Load data via AJAX
function loadData() {
    const jenisLaporan = document.getElementById('jenis_laporan').value;
    
    console.log('Loading data for:', jenisLaporan);
    
    if (!jenisLaporan) {
        alert('Pilih jenis laporan terlebih dahulu!');
        return;
    }
    
    currentJenisLaporan = jenisLaporan;
    
    // Show loading
    document.getElementById('loading').style.display = 'block';
    document.getElementById('previewSection').style.display = 'none';
    
    // Send AJAX request
    fetch('<?= base_url('laporan/get_data') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `jenis_laporan=${jenisLaporan}`
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response:', data);
        document.getElementById('loading').style.display = 'none';
        
        if (data.status === 'success') {
            currentData = data.data;
            displayData(data.data, data.jenis_laporan);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        document.getElementById('loading').style.display = 'none';
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat data');
    });
}

// Display data in table
function displayData(data, jenisLaporan) {
    // Update info
    document.getElementById('jenisLaporanBadge').textContent = getLaporanName(jenisLaporan);
    document.getElementById('tanggalCetak').textContent = new Date().toLocaleString('id-ID');
    document.getElementById('periode').textContent = 'Semua Data';
    document.getElementById('totalData').textContent = data.length;
    
    // Setup table header and body
    setupTable(jenisLaporan, data);
    
    // Show preview section
    document.getElementById('previewSection').style.display = 'block';
}

// Setup table based on jenis laporan
function setupTable(jenisLaporan, data) {
    const tableHeader = document.getElementById('tableHeader');
    const tableBody = document.getElementById('tableBody');
    
    let headerHTML = '';
    let bodyHTML = '';
    
    switch(jenisLaporan) {
        case 'inventaris':
            headerHTML = `
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Kondisi</th>
                    <th>Harga</th>
                    <th>Tanggal Perolehan</th>
                </tr>
            `;
            data.forEach((item, index) => {
                bodyHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.kode_barang}</td>
                        <td>${item.nama_barang}</td>
                        <td>${item.nama_kategori || '-'}</td>
                        <td>${item.nama_lokasi || '-'}</td>
                        <td>${item.kondisi || 'Baik'}</td>
                        <td>Rp ${formatNumber(item.harga_perolehan || 0)}</td>
                        <td>${formatDate(item.tanggal_perolehan)}</td>
                    </tr>
                `;
            });
            break;
            
        case 'barang_masuk':
            headerHTML = `
                <tr>
                    <th>No</th>
                    <th>Tanggal Masuk</th>
                    <th>Sumber Barang</th>
                    <th>Jumlah</th>
                    <th>Dokumen</th>
                </tr>
            `;
            data.forEach((item, index) => {
                bodyHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${formatDate(item.tgl_masuk)}</td>
                        <td>${item.sumberbarang}</td>
                        <td>${item.jumlah}</td>
                        <td>${item.dokumen_pendukung || '-'}</td>
                    </tr>
                `;
            });
            break;
            
        case 'barang_keluar':
            headerHTML = `
                <tr>
                    <th>No</th>
                    <th>Tanggal Keluar</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Jenis Transaksi</th>
                    <th>Tujuan</th>
                    <th>Penanggung Jawab</th>
                    <th>Jumlah</th>
                </tr>
            `;
            data.forEach((item, index) => {
                bodyHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${formatDate(item.tgl_keluar)}</td>
                        <td>${item.kode_barang || '-'}</td>
                        <td>${item.nama_barang || '-'}</td>
                        <td>${item.jenis_tras}</td>
                        <td>${item.tujuan}</td>
                        <td>${item.pj}</td>
                        <td>${item.jumlah}</td>
                    </tr>
                `;
            });
            break;
            
        case 'peminjaman':
            headerHTML = `
                <tr>
                    <th>No</th>
                    <th>Tanggal Pinjam</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Peminjam</th>
                    <th>Tujuan</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>
            `;
            data.forEach((item, index) => {
                bodyHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${formatDate(item.tgl_keluar)}</td>
                        <td>${item.kode_barang || '-'}</td>
                        <td>${item.nama_barang || '-'}</td>
                        <td>${item.pj}</td>
                        <td>${item.tujuan}</td>
                        <td>${item.jumlah}</td>
                        <td>${item.status_keterlambatan}</td>
                    </tr>
                `;
            });
            break;
            
        case 'kondisi':
            headerHTML = `
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Kondisi</th>
                    <th>Spesifikasi</th>
                </tr>
            `;
            data.forEach((item, index) => {
                bodyHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.kode_barang}</td>
                        <td>${item.nama_barang}</td>
                        <td>${item.nama_kategori || '-'}</td>
                        <td>${item.nama_lokasi || '-'}</td>
                        <td>${item.kondisi || 'Baik'}</td>
                        <td>${item.spesifikasi || '-'}</td>
                    </tr>
                `;
            });
            break;
            
        case 'penghapusan':
            headerHTML = `
                <tr>
                    <th>No</th>
                    <th>Tanggal Penghapusan</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Jenis Penghapusan</th>
                    <th>Keterangan</th>
                </tr>
            `;
            data.forEach((item, index) => {
                bodyHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${formatDate(item.tanggal_penghapusan)}</td>
                        <td>${item.kode_barang || '-'}</td>
                        <td>${item.nama_barang || '-'}</td>
                        <td>${item.jenis_penghapusan}</td>
                        <td>${item.keterangan || '-'}</td>
                    </tr>
                `;
            });
            break;
    }
    
    tableHeader.innerHTML = headerHTML;
    tableBody.innerHTML = bodyHTML || '<tr><td colspan="100%" class="text-center">Tidak ada data</td></tr>';
}

// Export PDF
function exportPDF() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('laporan/export_pdf') ?>';
    
    const inputJenis = document.createElement('input');
    inputJenis.type = 'hidden';
    inputJenis.name = 'jenis_laporan';
    inputJenis.value = currentJenisLaporan;
    
    form.appendChild(inputJenis);
    document.body.appendChild(form);
    form.submit();
}

// Export Excel
function exportExcel() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= base_url('laporan/export_excel') ?>';
    
    const inputJenis = document.createElement('input');
    inputJenis.type = 'hidden';
    inputJenis.name = 'jenis_laporan';
    inputJenis.value = currentJenisLaporan;
    
    form.appendChild(inputJenis);
    document.body.appendChild(form);
    form.submit();
}

// Helper functions
function getLaporanName(jenis) {
    const names = {
        'inventaris': 'Daftar Inventaris',
        'barang_masuk': 'Barang Masuk',
        'barang_keluar': 'Barang Keluar',
        'peminjaman': 'Peminjaman',
        'kondisi': 'Kondisi Barang',
        'penghapusan': 'Penghapusan'
    };
    return names[jenis] || jenis;
}

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID');
}

function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}
</script>
