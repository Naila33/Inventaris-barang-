# 🚀 Panduan Instalasi Fitur Peminjaman Barang

## Langkah 1: Import Database

### Buka phpMyAdmin atau MySQL CLI:
1. Pilih database `pengelolaan`
2. Import file SQL berikut:

```sql
-- Jalankan ini di phpMyAdmin atau MySQL CLI:

-- 1. Buat tabel peminjaman
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
  KEY `id_barang` (`id_barang`),
  KEY `id_peminjam` (`id_peminjam`),
  CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `databarang` (`id_barang`) ON DELETE CASCADE,
  CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_peminjam`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Tambah menu peminjaman
INSERT INTO `user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon`, `is_active`) VALUES
(13, 4, 'Peminjaman Barang', 'peminjaman', 'fas fa-fw fa-hand-holding', 1);

-- 3. Tambah akses untuk Administrator
INSERT INTO `user_access_menu` (`id`, `role_id`, `menu_id`) VALUES
(21, 1, 8),
(22, 1, 13);

-- 4. Tambah akses untuk Petugas
INSERT INTO `user_access_menu` (`id`, `role_id`, `menu_id`) VALUES
(23, 2, 8),
(24, 2, 13);

-- 5. Update auto increment
ALTER TABLE `user_sub_menu` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
ALTER TABLE `user_access_menu` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
```

## Langkah 2: Verifikasi File

Pastikan file berikut sudah ada:
- ✅ `application/controllers/Peminjaman.php`
- ✅ `application/models/Peminjaman_model.php`
- ✅ `application/views/peminjaman/index.php`
- ✅ `application/views/peminjaman/aktif.php`
- ✅ `application/views/peminjaman/terlambat.php`

## Langkah 3: Akses Fitur

1. Login ke sistem: `http://localhost/Inventaris-barang-/`
2. Menu **Barang** → **Peminjaman Barang**
3. Atau langsung: `http://localhost/Inventaris-barang-/peminjaman`

## 🎯 Cara Test Fitur

### Test 1: Tambah Peminjaman
1. Klik "Tambah Peminjaman"
2. Pilih barang (contoh: "MMK01 - Meja Belajar Kayu")
3. Pilih peminjam (contoh: "naila" atau "lilian")
4. Set tanggal pinjam = hari ini
5. Set batas waktu = 3 hari dari sekarang
6. Klik "Simpan"

### Test 2: Lihat Peminjaman Aktif
1. Klik "Peminjaman Aktif"
2. Harus muncul data yang baru ditambahkan
3. Cek perhitungan hari tersisa

### Test 3: Test Keterlambatan (Manual)
1. Edit database: ubah batas_waktu ke tanggal kemarin
2. Refresh halaman "Peminjaman Aktif"
3. Status harus berubah menjadi "Terlambat"

### Test 4: Pengembalian Barang
1. Klik tombol "Kembalikan"
2. Konfirmasi "OK"
3. Status harus berubah menjadi "Dikembalikan"

## 🔧 Troubleshooting

### Error 404 - Page Not Found
**Penyebab:** File controller tidak ditemukan
**Solusi:** Pastikan `Peminjaman.php` ada di `application/controllers/`

### Error Database - Table Not Found
**Penyebab:** Tabel peminjaman belum dibuat
**Solusi:** Jalankan SQL pembuatan tabel di phpMyAdmin

### Menu Tidak Muncul
**Penyebab:** Menu belum ditambahkan ke database
**Solusi:** Jalankan SQL untuk menambah submenu

### Blank Page
**Penyebab:** Error PHP atau view tidak ditemukan
**Solusi:** Cek error log di `application/logs/`

## 📊 Expected Results

Setelah instalasi berhasil:
- ✅ Menu "Peminjaman Barang" muncul di sidebar
- ✅ Halaman peminjaman bisa diakses
- ✅ Form tambah peminjaman berfungsi
- ✅ Tabel menampilkan data peminjaman
- ✅ Tombol edit/delete berfungsi
- ✅ Pengembalian barang berfungsi
- ✅ Status otomatis terupdate

## 🎨 UI Features
- **Responsive Design** - Bekerja di desktop & mobile
- **Color Coding** - Badge warna untuk status
- **Interactive Tables** - Sorting & filtering
- **Modal Forms** - Form yang user-friendly
- **Real-time Updates** - Update tanpa reload

## 🛡️ Security
- Session validation
- Input sanitization  
- CSRF protection
- Role-based access
- Foreign key constraints

## 📈 Next Steps
1. Test semua fitur sesuai guide di atas
2. Tambah data sample untuk testing
3. Cek laporan dan statistik
4. Customization sesuai kebutuhan

## 🆘 Support
Jika ada masalah:
1. Cek error log: `application/logs/`
2. Verify database structure
3. Clear cache: `application/cache/`
4. Restart web server

Selamat menggunakan fitur Peminjaman Barang! 🎉
