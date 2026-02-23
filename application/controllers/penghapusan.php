<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_DB $db
 */ 

class Penghapusan extends CI_Controller {

public function __construct()
{
    parent::__construct();
    $this->load->library('session');
    $this->load->library('form_validation');
    $this->load->helper('form');
    $this->load->helper('pengelolaan');
    is_logged_in();
    
    // Check if user is admin
    if ($this->session->userdata('role_id') != 1) {
        redirect('auth/blocked');
    }
}

    public function index()
{
    $data['title'] = 'Penghapusan barang';
    $jenis = $this->input->get('jenis');

    $this->db->from('penghapusan_barang');
    $this->db->join('databarang', 'databarang.id_barang = penghapusan_barang.id_barang');

    if ($jenis) {
        $this->db->where('jenis_penghapusan', $jenis);
    }

    $data['penghapusan_barang'] = $this->db->get()->result();

    $this->load->view('templates/header');
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('penghapusan/penghapusan_list', $data);
    $this->load->view('templates/footer');
}

    public function get_datatables()
    {
        $jenis = $this->input->post('jenis');
        
        $this->db->from('penghapusan_barang');
        $this->db->join('databarang', 'databarang.id_barang = penghapusan_barang.id_barang');
        
        if ($jenis) {
            $this->db->where('penghapusan_barang.jenis_penghapusan', $jenis);
        }
        
        // DataTables parameters
        $draw = $this->input->post('draw');
        $length = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $this->input->post('order');
        $search = $this->input->post('search');
        
        // Search
        if ($search && $search['value']) {
            $searchValue = $search['value'];
            $this->db->group_start();
            $this->db->like('databarang.nama_barang', $searchValue);
            $this->db->or_like('penghapusan_barang.jenis_penghapusan', $searchValue);
            $this->db->or_like('penghapusan_barang.tanggal_penghapusan', $searchValue);
            $this->db->or_like('penghapusan_barang.keterangan', $searchValue);
            $this->db->group_end();
        }
        
        // Order
        if ($order) {
            $columnIndex = $order[0]['column'];
            $columnName = $this->input->post('columns')[$columnIndex]['data'];
            $columnDir = $order[0]['dir'];
            
            $columnMap = [
                'nama_barang' => 'databarang.nama_barang',
                'jenis_penghapusan' => 'penghapusan_barang.jenis_penghapusan',
                'tanggal_penghapusan' => 'penghapusan_barang.tanggal_penghapusan',
                'keterangan' => 'penghapusan_barang.keterangan'
            ];
            
            if (isset($columnMap[$columnName])) {
                $this->db->order_by($columnMap[$columnName], $columnDir);
            }
        } else {
            $this->db->order_by('penghapusan_barang.tanggal_penghapusan', 'DESC');
        }
        
        // Get total records
        $totalRecords = $this->db->count_all_results('', false);
        
        // Get filtered records
        $totalFiltered = $this->db->count_all_results('', false);
        
        // Get data with limit
        $this->db->limit($length, $start);
        $query = $this->db->get();
        $data = $query->result();
        
        // Format data for DataTables
        $formattedData = [];
        $no = $start + 1;
        
        foreach ($data as $item) {
            $badge_class = '';
            switch($item->jenis_penghapusan) {
                case 'rusak': $badge_class = 'badge-danger'; break;
                case 'hilang': $badge_class = 'badge-warning'; break;
                case 'kadaluarsa': $badge_class = 'badge-info'; break;
                default: $badge_class = 'badge-secondary';
            }
            
            $formattedData[] = [
                'no' => $no++,
                'nama_barang' => $item->nama_barang,
                'jenis_penghapusan' => '<span class="badge ' . $badge_class . '">' . $item->jenis_penghapusan . '</span>',
                'tanggal_penghapusan' => date('d/m/Y', strtotime($item->tanggal_penghapusan)),
                'keterangan' => $item->keterangan ?: '-',
                'aksi' => '<a href="' . base_url('penghapusan/berita_acara/' . $item->id_penghapusan) . '" 
                           class="btn btn-sm btn-info" title="Cetak Berita Acara">
                           <i class="fas fa-file-alt"></i>
                           </a>'
            ];
        }
        
        $response = [
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $formattedData
        ];
        
        echo json_encode($response);
    }

    public function simpan()
{
    $data = [
        'id_barang' => $this->input->post('id_barang'),
        'jenis_penghapusan' => $this->input->post('jenis_penghapusan'),
        'tanggal_penghapusan' => $this->input->post('tanggal_penghapusan'),
        'keterangan' => $this->input->post('keterangan')
    ];

    $this->db->insert('penghapusan_barang', $data);

    $idBarang = (int)$data['id_barang'];
    if ($idBarang) {
        if ($this->db->field_exists('status', 'databarang')) {
            $this->db->where('id_barang', $idBarang)->update('databarang', ['status' => 'Dihapus']);
        } else {
            $this->db->delete('databarang', ['id_barang' => $idBarang]);
        }
    }

    redirect('penghapusan');
}

    public function berita_acara($id)
{
    $this->load->model('Penghapusan_model');

    $data['penghapusan'] = $this->Penghapusan_model->getByIdWithBarang($id);

    $this->load->view('penghapusan/berita_acara_html', $data);
}

    public function cetak_pdf($id)
    {
        $data['judul'] = 'Berita Acara Penghapusan';

        $html = $this->load->view('penghapusan/berita_acara_pdf', $data, true);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("berita_acara_penghapusan.pdf", [
            'Attachment' => false
        ]);
    }
}
