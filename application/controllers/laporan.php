<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load Dompdf
require_once APPPATH . '../vendor/autoload.php';

use Dompdf\Dompdf;

// Load PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * @property CI_DB $db
 * @property CI_Session $session
 * @property CI_Form_validation $form_validation
 */
class Laporan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('pengelolaan');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('date');
        is_logged_in();
        
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
    }

    public function index()
    {
        $data['title'] = 'Laporan Inventaris';
        $data['user'] = $this->db->where('email', 
        $this->session->userdata('email'))->get('user')->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('laporan/index', $data);
        $this->load->view('templates/footer');
    }

    public function get_data()
    {
        $jenis_laporan = $this->input->post('jenis_laporan');
        
        if (!$jenis_laporan) {
            echo json_encode(['status' => 'error', 'message' => 'Jenis laporan harus dipilih']);
            return;
        }
        
        $data = [];
        switch($jenis_laporan) {
            case 'inventaris':
                $data = $this->get_inventaris_data();
                break;
            case 'barang_masuk':
                $data = $this->get_barang_masuk_data();
                break;
            case 'barang_keluar':
                $data = $this->get_barang_keluar_data();
                break;
            case 'peminjaman':
                $data = $this->get_peminjaman_data();
                break;
            case 'kondisi':
                $data = $this->get_kondisi_data();
                break;
            case 'penghapusan':
                $data = $this->get_penghapusan_data();
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Jenis laporan tidak valid']);
                return;
        }
        
        echo json_encode([
            'status' => 'success',
            'data' => $data,
            'jenis_laporan' => $jenis_laporan
        ]);
    }

    private function get_inventaris_data($tanggal_awal = null, $tanggal_akhir = null)
    {
        $this->db->select('d.*, k.nama_kategori, l.nama_lokasi');
        $this->db->from('databarang d');
        $this->db->join('kategoribarang k', 'k.id_kategori = d.id_kategori', 'left');
        $this->db->join('lokasi l', 'l.id_lokasi = d.id_lokasi', 'left');
        
        if ($tanggal_awal) {
            $this->db->where('d.tanggal_perolehan >=', $tanggal_awal);
        }
        if ($tanggal_akhir) {
            $this->db->where('d.tanggal_perolehan <=', $tanggal_akhir);
        }
        
        return $this->db->get()->result_array();
    }

    private function get_barang_masuk_data($tanggal_awal = null, $tanggal_akhir = null)
    {
        $this->db->from('barangin');
        
        if ($tanggal_awal) {
            $this->db->where('tgl_masuk >=', $tanggal_awal);
        }
        if ($tanggal_akhir) {
            $this->db->where('tgl_masuk <=', $tanggal_akhir);
        }
        
        return $this->db->get()->result_array();
    }

    private function get_barang_keluar_data($tanggal_awal = null, $tanggal_akhir = null)
    {
        $this->db->select('bo.*, d.nama_barang, d.kode_barang');
        $this->db->from('barangout bo');
        $this->db->join('databarang d', 'd.id_barang = bo.id_barang', 'left');
        
        if ($tanggal_awal) {
            $this->db->where('bo.tgl_keluar >=', $tanggal_awal);
        }
        if ($tanggal_akhir) {
            $this->db->where('bo.tgl_keluar <=', $tanggal_akhir);
        }
        
        return $this->db->get()->result_array();
    }

    private function get_peminjaman_data($tanggal_awal = null, $tanggal_akhir = null)
    {
        $this->db->select('bo.*, d.nama_barang, d.kode_barang');
        $this->db->from('barangout bo');
        $this->db->join('databarang d', 'd.id_barang = bo.id_barang', 'left');
        $this->db->where('bo.jenis_tras', 'Dipinjam');
        
        if ($tanggal_awal) {
            $this->db->where('bo.tgl_keluar >=', $tanggal_awal);
        }
        if ($tanggal_akhir) {
            $this->db->where('bo.tgl_keluar <=', $tanggal_akhir);
        }
        
        return $this->db->get()->result_array();
    }

    private function get_kondisi_data($tanggal_awal = null, $tanggal_akhir = null)
    {
        $this->db->select('d.*, k.nama_kategori, l.nama_lokasi');
        $this->db->from('databarang d');
        $this->db->join('kategoribarang k', 'k.id_kategori = d.id_kategori', 'left');
        $this->db->join('lokasi l', 'l.id_lokasi = d.id_lokasi', 'left');
        
        if ($tanggal_awal) {
            $this->db->where('d.tanggal_perolehan >=', $tanggal_awal);
        }
        if ($tanggal_akhir) {
            $this->db->where('d.tanggal_perolehan <=', $tanggal_akhir);
        }
        
        return $this->db->get()->result_array();
    }

    private function get_penghapusan_data($tanggal_awal = null, $tanggal_akhir = null)
    {
        $this->db->select('pb.*, d.nama_barang, d.kode_barang');
        $this->db->from('penghapusan_barang pb');
        $this->db->join('databarang d', 'd.id_barang = pb.id_barang', 'left');
        
        if ($tanggal_awal) {
            $this->db->where('pb.tanggal_penghapusan >=', $tanggal_awal);
        }
        if ($tanggal_akhir) {
            $this->db->where('pb.tanggal_penghapusan <=', $tanggal_akhir);
        }
        
        return $this->db->get()->result_array();
    }

    public function export_pdf()
    {
        $jenis_laporan = $this->input->post('jenis_laporan');
        
        $data['title'] = 'Export PDF';
        $data['user'] = $this->db->where('email', 
        $this->session->userdata('email'))->get('user')->row_array();
        $data['jenis_laporan'] = $jenis_laporan;
        $data['tanggal_awal'] = null;
        $data['tanggal_akhir'] = null;
        
        switch($jenis_laporan) {
            case 'inventaris':
                $data['laporan_data'] = $this->get_inventaris_data();
                $view = 'laporan/export/inventaris_pdf';
                $filename = 'laporan_inventaris_' . date('Y-m-d') . '.pdf';
                break;
            case 'barang_masuk':
                $data['laporan_data'] = $this->get_barang_masuk_data();
                $view = 'laporan/export/barang_masuk_pdf';
                $filename = 'laporan_barang_masuk_' . date('Y-m-d') . '.pdf';
                break;
            case 'barang_keluar':
                $data['laporan_data'] = $this->get_barang_keluar_data();
                $view = 'laporan/export/barang_keluar_pdf';
                $filename = 'laporan_barang_keluar_' . date('Y-m-d') . '.pdf';
                break;
            case 'peminjaman':
                $data['laporan_data'] = $this->get_peminjaman_data();
                $view = 'laporan/export/peminjaman_pdf';
                $filename = 'laporan_peminjaman_' . date('Y-m-d') . '.pdf';
                break;
            case 'kondisi':
                $data['laporan_data'] = $this->get_kondisi_data();
                $view = 'laporan/export/kondisi_pdf';
                $filename = 'laporan_kondisi_barang_' . date('Y-m-d') . '.pdf';
                break;
            case 'penghapusan':
                $data['laporan_data'] = $this->get_penghapusan_data();
                $view = 'laporan/export/penghapusan_pdf';
                $filename = 'laporan_penghapusan_' . date('Y-m-d') . '.pdf';
                break;
            default:
                redirect('laporan');
                return;
        }

        // Generate HTML content
        $html = $this->load->view($view, $data, true);
        
        // Create PDF using DomPDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        // Download PDF
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }

    public function export_excel()
    {
        $jenis_laporan = $this->input->post('jenis_laporan');
        
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Get data
        switch($jenis_laporan) {
            case 'inventaris':
                $data = $this->get_inventaris_data();
                $filename = 'laporan_inventaris_' . date('Y-m-d') . '.xlsx';
                $title = 'LAPORAN DAFTAR INVENTARIS';
                $headers = ['No', 'Kode Barang', 'Nama Barang', 'Kategori', 'Lokasi', 'Kondisi', 'Harga Perolehan', 'Tanggal Perolehan'];
                break;
            case 'barang_masuk':
                $data = $this->get_barang_masuk_data();
                $filename = 'laporan_barang_masuk_' . date('Y-m-d') . '.xlsx';
                $title = 'LAPORAN BARANG MASUK';
                $headers = ['No', 'Tanggal Masuk', 'Sumber Barang', 'Jumlah', 'Dokumen Pendukung'];
                break;
            case 'barang_keluar':
                $data = $this->get_barang_keluar_data();
                $filename = 'laporan_barang_keluar_' . date('Y-m-d') . '.xlsx';
                $title = 'LAPORAN BARANG KELUAR';
                $headers = ['No', 'Tanggal Keluar', 'Kode Barang', 'Nama Barang', 'Jenis Transaksi', 'Tujuan', 'Penanggung Jawab', 'Jumlah'];
                break;
            case 'peminjaman':
                $data = $this->get_peminjaman_data();
                $filename = 'laporan_peminjaman_' . date('Y-m-d') . '.xlsx';
                $title = 'LAPORAN PEMINJAMAN BARANG';
                $headers = ['No', 'Tanggal Pinjam', 'Kode Barang', 'Nama Barang', 'Peminjam', 'Tujuan', 'Jumlah', 'Status'];
                break;
            case 'kondisi':
                $data = $this->get_kondisi_data();
                $filename = 'laporan_kondisi_barang_' . date('Y-m-d') . '.xlsx';
                $title = 'LAPORAN KONDISI BARANG';
                $headers = ['No', 'Kode Barang', 'Nama Barang', 'Kategori', 'Lokasi', 'Kondisi', 'Spesifikasi'];
                break;
            case 'penghapusan':
                $data = $this->get_penghapusan_data();
                $filename = 'laporan_penghapusan_' . date('Y-m-d') . '.xlsx';
                $title = 'LAPORAN PENGHAPUSAN BARANG';
                $headers = ['No', 'Tanggal Penghapusan', 'Kode Barang', 'Nama Barang', 'Jenis Penghapusan', 'Keterangan'];
                break;
            default:
                redirect('laporan');
                return;
        }
        
        // Set page title and info
        $sheet->setCellValue('A1', $title);
        $sheet->setCellValue('A2', 'Tanggal Cetak: ' . date('d/m/Y H:i:s'));
        $sheet->setCellValue('A3', 'Periode: Semua Data');
        
        // Style title
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2:A3')->getFont()->setSize(10);
        
        // Set headers
        $col = 'A';
        foreach($headers as $header) {
            $sheet->setCellValue($col . '5', $header);
            $col++;
        }
        
        // Style headers
        $headerRange = 'A5:' . chr(ord('A') + count($headers) - 1) . '5';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($headerRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Add data
        $row = 6;
        $no = 1;
        foreach($data as $item) {
            $col = 'A';
            
            switch($jenis_laporan) {
                case 'inventaris':
                    $values = [
                        $no,
                        $item['kode_barang'],
                        $item['nama_barang'],
                        $item['nama_kategori'] ?? '-',
                        $item['nama_lokasi'] ?? '-',
                        $item['kondisi'] ?? 'Baik',
                        $item['harga_perolehan'] ?? 0,
                        date('d/m/Y', strtotime($item['tanggal_perolehan']))
                    ];
                    break;
                case 'barang_masuk':
                    $values = [
                        $no,
                        date('d/m/Y', strtotime($item['tgl_masuk'])),
                        $item['sumberbarang'],
                        $item['jumlah'],
                        $item['dokumen_pendukung'] ?? '-'
                    ];
                    break;
                case 'barang_keluar':
                    $values = [
                        $no,
                        date('d/m/Y', strtotime($item['tgl_keluar'])),
                        $item['kode_barang'] ?? '-',
                        $item['nama_barang'] ?? '-',
                        $item['jenis_tras'],
                        $item['tujuan'],
                        $item['pj'],
                        $item['jumlah']
                    ];
                    break;
                case 'peminjaman':
                    $values = [
                        $no,
                        date('d/m/Y', strtotime($item['tgl_keluar'])),
                        $item['kode_barang'] ?? '-',
                        $item['nama_barang'] ?? '-',
                        $item['pj'],
                        $item['tujuan'],
                        $item['jumlah'],
                        $item['status_keterlambatan']
                    ];
                    break;
                case 'kondisi':
                    $values = [
                        $no,
                        $item['kode_barang'],
                        $item['nama_barang'],
                        $item['nama_kategori'] ?? '-',
                        $item['nama_lokasi'] ?? '-',
                        $item['kondisi'] ?? 'Baik',
                        $item['spesifikasi'] ?? '-'
                    ];
                    break;
                case 'penghapusan':
                    $values = [
                        $no,
                        date('d/m/Y', strtotime($item['tanggal_penghapusan'])),
                        $item['kode_barang'] ?? '-',
                        $item['nama_barang'] ?? '-',
                        $item['jenis_penghapusan'],
                        $item['keterangan'] ?? '-'
                    ];
                    break;
            }
            
            foreach($values as $value) {
                $sheet->setCellValue($col . $row, $value);
                $col++;
            }
            
            // Style data row
            $dataRange = 'A' . $row . ':' . chr(ord('A') + count($headers) - 1) . $row;
            $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            
            $row++;
            $no++;
        }
        
        // Auto-size columns
        foreach(range('A', chr(ord('A') + count($headers) - 1)) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        // Set active sheet and download
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: 0');
        header('Pragma: public');
        
        $writer->save('php://output');
        exit;
    }
}
