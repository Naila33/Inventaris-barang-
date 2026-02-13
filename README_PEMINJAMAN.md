# Fitur Peminjaman Barang - Panduan Instalasi dan Penggunaan

## 📋 Fitur yang Telah Diimplementasikan

### ✅ Pencatatan Peminjaman
- Form peminjaman dengan validasi lengkap
- Pemilihan barang dari database
- Pemilihan peminjam dari data user
- Penentuan tanggal pinjam dan batas waktu
- Keterangan tambahan (opsional)

### ✅ Batas Waktu Peminjaman
- Sistem otomatis menghitung batas waktu
- Validasi tanggal (batas waktu tidak boleh sebelum tanggal pinjam)
- Peringatan visual untuk peminjaman yang mendekati batas waktu

### ✅ Pengembalian Barang
- Tombol kembalikan untuk setiap peminjaman aktif
- Pencatatan otomatis tanggal pengembalian
- Perubahan status menjadi "Dikembalikan"

### ✅ Status Keterlambatan
- 3 Status: Dipinjam, Dikembalikan, Terlambat
- Update otomatis status menjadi "Terlambat" jika melewati batas waktu
- Halaman khusus untuk peminjaman terlambat
- Perhitungan jumlah hari keterlambatan

## 🗃️ Struktur Database

### Tabel `peminjaman`
```sql
CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT,
  `id_barang` int(11) NOT NULL,
  `id_peminjam` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `batas_waktu` date NOT NULL,
  `status` enum('Dipinjam','Dikembalikan','Terlambat') NOT NULL DEFAULT 'Dipinjam',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_peminjaman`),
  FOREIGN KEY (`id_barang`) REFERENCES `databarang` (`id_barang`) ON DELETE CASCADE,
  FOREIGN KEY (`id_peminjam`) REFERENCES `user` (`id`) ON DELETE CASCADE
);
```

## 📁 File yang Dibuat

### Model
- `application/models/Peminjaman_model.php` - Model untuk operasi database peminjaman

### Controller
- `application/controllers/Peminjaman.php` - Controller untuk manajemen peminjaman

### Views
- `application/views/peminjaman/index.php` - Halaman utama peminjaman
- `application/views/peminjaman/aktif.php` - Halaman peminjaman aktif
- `application/views/peminjaman/terlambat.php` - Halaman peminjaman terlambat

### SQL Files
- `peminjaman_table.sql` - Script untuk membuat tabel peminjaman
- `update_menu.sql` - Script untuk menambah menu peminjaman

## 🚀 Cara Instalasi

### 1. Import Database
```sql
-- Jalankan file peminjaman_table.sql
SOURCE peminjaman_table.sql;

-- Jalankan file update_menu.sql  
SOURCE update_menu.sql;
```

### 2. Akses Fitur
- Login ke sistem
- Menu "Barang" → "Peminjaman Barang"
- Atau langsung ke: `http://localhost/Inventaris-barang-/peminjaman`

## 🎯 Cara Penggunaan

### Menambah Peminjaman
1. Klik tombol "Tambah Peminjaman"
2. Pilih barang dari dropdown
3. Pilih peminjam dari dropdown user aktif
4. Tentukan tanggal pinjam (default: hari ini)
5. Tentukan batas waktu pengembalian
6. Tambahkan keterangan (opsional)
7. Klik "Simpan"

### Mengembalikan Barang
1. Pada halaman peminjaman, klik tombol "Kembalikan"
2. Konfirmasi pengembalian
3. Status otomatis berubah menjadi "Dikembalikan"

### Melihat Peminjaman Aktif
1. Klik tombol "Peminjaman Aktif"
2. Lihat semua barang yang sedang dipinjam
3. Peringatan visual untuk yang mendekati batas waktu

### Melihat Peminjaman Terlambat
1. Klik tombol "Peminjaman Terlambat"
2. Lihat semua peminjaman yang melewati batas waktu
3. Informasi jumlah hari keterlambatan

## 🔧 Fitur Tambahan

### Validasi Sistem
- Barang tidak bisa dipinjam jika masih dipinjam orang lain
- Tanggal batas waktu tidak boleh sebelum tanggal pinjam
- Hanya user aktif yang bisa dipilih sebagai peminjam

### Status Otomatis
- Status "Terlambat" diupdate otomatis saat melewati batas waktu
- Perhitungan hari tersisa/hari terlambat
- Badge warna untuk berbagai status

### Integrasi Menu
- Menu "Peminjaman Barang" di bawah menu "Barang"
- Akses untuk Administrator dan Petugas
- Icon yang sesuai untuk identifikasi visual

## 📊 Laporan yang Tersedia

### Statistik Peminjaman
- Total peminjaman aktif
- Total peminjaman terlambat
- Total peminjaman per status

### History Peminjaman
- Riwayat peminjaman per barang
- Tracking peminjaman per user
- Timeline lengkap peminjaman

## 🔄 Flow Peminjaman

1. **Input** → User mengisi form peminjaman
2. **Validasi** → Sistem cek ketersediaan barang
3. **Simpan** → Data peminjaman tersimpan dengan status "Dipinjam"
4. **Monitoring** → Sistem monitor batas waktu
5. **Update Status** → Otomatis ubah ke "Terlambat" jika lewat batas
6. **Pengembalian** → User kembalikan barang, status jadi "Dikembalikan"

## 🎨 UI/UX Features

- **Responsive Design** → Bekerja di desktop dan mobile
- **Color Coding** → Warna berbeda untuk setiap status
- **Interactive Tables** → Sorting dan filtering data
- **Modal Forms** → Form yang user-friendly
- **Real-time Updates** → Update status tanpa reload
- **Confirmation Dialogs** → Konfirmasi untuk aksi penting

## 🛡️ Security Features

- **Session Validation** → Cek login sebelum akses
- **Input Sanitization** → HTML escaping untuk XSS protection
- **CSRF Protection** → Built-in CodeIgniter CSRF
- **Role-based Access** → Hanya role yang bisa akses
- **Foreign Key Constraints** → Data integrity protection

## 📈 Future Enhancements

- Notifikasi email untuk pengingat pengembalian
- Export data ke Excel/PDF
- Barcode/QR code untuk tracking
- Mobile app integration
- Analytics dashboard
- Multi-location support
