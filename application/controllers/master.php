 <?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_DB $db
 * @property CI_Upload $upload
 * @property Databarang_model $Databarang_model
 * @property Lokasi_model $Lokasi_model
 */

class Master extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('Databarang_model');
        $this->load->model('Lokasi_model');
        $this->load->model('Supplier_model');
        $this->load->model('Kategoribarang_model');
        $this->load->model('supplier_model');
        $this->load->model('pengguna_model'); 
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
    }

    public function databarang()
    {
        $data['title'] = 'Data Barang';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('user')->row_array();

        $this->db->select('databarang.*, kategoribarang.nama_kategori');
        $this->db->from('databarang');
        $this->db->join('kategoribarang', 'kategoribarang.id_kategori = databarang.id_kategori', 'left');
        $data['databarang'] = $this->db->get()->result_array();

        $data['open_modal'] = false;

        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');
        $this->form_validation->set_rules('id_kategori', 'ID kategori', 'required|trim');
        $this->form_validation->set_rules('spesifikasi', 'Spesifikasi', 'required|trim');
        $this->form_validation->set_rules('satuan', 'Satuan', 'required|trim');
        $this->form_validation->set_rules('harga_perolehan', 'Harga Perolehan', 'required|trim');
        $this->form_validation->set_rules('tanggal_perolehan', 'Tanggal Perolehan', 'required|trim');
        $this->form_validation->set_rules('umur_ekonomis', 'Umur Ekonomis', 'required|trim');

        if ($this->form_validation->run() == false) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data['open_modal'] = true;
            }
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('master/data_barang', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('databarang', [
                'nama_barang' => $this->input->post('nama_barang'),
                'id_kategori' => $this->input->post('id_kategori'),
                'spesifikasi' => $this->input->post('spesifikasi'),
                'satuan' => $this->input->post('satuan'),
                'harga_perolehan' => $this->input->post('harga_perolehan'),
                'tanggal_perolehan' => $this->input->post('tanggal_perolehan'),
                'umur_ekonomis' => $this->input->post('umur_ekonomis')
            ]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data barang berhasil ditambahkan!</div>');
            redirect('master/databarang');
        }
    }

    public function getdatabarang()
    {
        try {
            $list = $this->Databarang_model->get_datatables();
            $data = [];
            $no = isset($_POST['start']) ? (int)$_POST['start'] : 0;

            foreach ($list as $row) {
                $no++;
                $data[] = [
                    'no' => $no,
                    'kode_barang' => $row->kode_barang,
                    'nama_barang' => $row->nama_barang,
                    'nama_kategori' => $row->nama_kategori,
                    'spesifikasi' => $row->spesifikasi,
                    'satuan' => $row->satuan,
                    'harga_perolehan' => $row->harga_perolehan,
                    'tanggal_perolehan' => $row->tanggal_perolehan,
                    'umur_ekonomis' => $row->umur_ekonomis
                ];
            }

            echo json_encode([
                "draw" => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,  
                "recordsTotal" => $this->Databarang_model->count_all(),
                "recordsFiltered" => $this->Databarang_model->count_filtered(),
                "data" => $data
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "error" => true,
                "message" => $e->getMessage()
            ]);
        }
    }

    public function kategoribarang()
    {
        $data['title'] = 'Kategori Barang';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('user')->row_array();

        $data['kategoribarang'] = $this->db->get('kategoribarang')->result_array();
        $data['open_modal'] = false;

        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required|trim');

        if ($this->form_validation->run() == false) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data['open_modal'] = true;
            }
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('master/kategori_barang', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('kategoribarang', [
                'nama_kategori' => $this->input->post('nama_kategori')
            ]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Kategori berhasil ditambahkan!</div>');
            redirect('master/kategoribarang');
        }
    }

public function getkategori()
    {
        try {
            $list = $this->Kategoribarang_model->get_datatables();
            $data = [];
            $no = isset($_POST['start']) ? (int)$_POST['start'] : 0;

            foreach ($list as $row) {
                $no++;
                $data[] = [
                    'no' => $no,
                    'nama_kategori' => $row->nama_kategori,
                ];
            }

            echo json_encode([
                "draw" => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,  
                "recordsTotal" => $this->Kategoribarang_model->count_all(),
                "recordsFiltered" => $this->Kategoribarang_model->count_filtered(),
                "data" => $data
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "error" => true,
                "message" => $e->getMessage()
            ]);
        }
    }

    public function lokasibarang()
    {
        $data['title'] = 'Lokasi Barang';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('user')->row_array();

        $this->load->model('Lokasi_model');
        $data['lokasi'] = $this->db->get('lokasi')->result_array();
        $data['open_modal'] = false;

        $this->form_validation->set_rules('kode_lokasi', 'Kode Lokasi', 'required|trim');
        $this->form_validation->set_rules('nama_lokasi', 'Nama Lokasi', 'required|trim');
        $this->form_validation->set_rules('gedung', 'Gedung', 'required|trim');
        $this->form_validation->set_rules('lantai', 'Lantai', 'required|trim');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|trim');

        if ($this->form_validation->run() == false) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data['open_modal'] = true;
            }
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('master/lokasi_barang', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('lokasi', [
                'kode_lokasi' => $this->input->post('kode_lokasi'),
                'nama_lokasi' => $this->input->post('nama_lokasi'),
                'gedung' => $this->input->post('gedung'),
                'lantai' => $this->input->post('lantai'),
                'keterangan' => $this->input->post('keterangan')
            ]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Lokasi berhasil ditambahkan!</div>');
            redirect('master/lokasibarang');
        }
    }

    public function getdatalokasi()
    {
        try {
            $list = $this->Lokasi_model->get_datatables();
            $data = [];
            $no = isset($_POST['start']) ? (int)$_POST['start'] : 0;

            foreach ($list as $row) {
                $no++;
                $data[] = [
                    'no' => $no,
                    'kode_lokasi' => $row->kode_lokasi,
                    'nama_lokasi' => $row->nama_lokasi,
                    'gedung' => $row->gedung,
                    'lantai' => $row->lantai,
                    'keterangan' => $row->keterangan,
                ];
            }

            echo json_encode([
                "draw" => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,  
                "recordsTotal" => $this->Lokasi_model->count_all(),
                "recordsFiltered" => $this->Lokasi_model->count_filtered(),
                "data" => $data
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "error" => true,
                "message" => $e->getMessage()
            ]);
        }
    }

    
public function supplierbarang()
    {
        $data['title'] = 'Supplier Barang';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('user')->row_array();

        $this->load->model('supplier_model');
        $data['supplier'] = $this->db->get('supplier')->result_array();

        $this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'required|trim');
        $this->form_validation->set_rules('kontak', 'Kontak Supplier', 'required|trim');
        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'required|trim');
        $this->form_validation->set_rules('kota', 'Kota', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('master/supplier_barang', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('supplier', [
                'nama_supplier' => $this->input->post('nama_supplier'),
                'kontak' => $this->input->post('kontak'),
                'no_telp' => $this->input->post('no_telp'),
                'kota' => $this->input->post('kota'),
                'status' => $this->input->post('status')
            ]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Supplier berhasil ditambahkan!</div>');
            redirect('master/supplierbarang');
        }
    }

    public function getdatasupplier()
    {
        try {
            $list = $this->supplier_model->get_datatables();
            $data = [];
            $no = isset($_POST['start']) ? (int)$_POST['start'] : 0;

            foreach ($list as $row) {
                $no++;
                $data[] = [
                    'no' => $no,
                    'nama_supplier' => $row->nama_supplier,
                    'kontak' => $row->kontak,
                    'no_telp' => $row->no_telp,
                    'kota' => $row->kota,
                    'status' => $row->status
                ];
            }

            echo json_encode([
                "draw" => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,  
                "recordsTotal" => $this->supplier_model->count_all(),
                "recordsFiltered" => $this->supplier_model->count_filtered(),
                "data" => $data
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "error" => true,
                "message" => $e->getMessage()
            ]);
        }
    }


    public function penggunabarang()
    {
        $data['title'] = 'Pengguna Barang';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('user')->row_array();

        $data['open_modal'] = false;
        $data['pengguna'] = $this->db->get('pengguna_barang')->result_array();

        $this->form_validation->set_rules('nama_pengguna', 'Nama Pengguna', 'required|trim');
        $this->form_validation->set_rules('jenis_pengguna', 'Jenis Pengguna', 'required|trim');
        $this->form_validation->set_rules('no_identitas', 'Nomor Identitas', 'required|trim');
        $this->form_validation->set_rules('divisi', 'Divisi', 'required|trim');
        $this->form_validation->set_rules('unit', 'Unit', 'required|trim');
        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'required|trim');
        $this->form_validation->set_rules('status', 'Status', 'required|trim');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|trim');

        if ($this->form_validation->run() == false) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data['open_modal'] = true;
            }
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('master/pengguna_barang', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('pengguna_barang', [
                'nama_pengguna' => $this->input->post('nama_pengguna'),
                'jenis_pengguna' => $this->input->post('jenis_pengguna'),
                'no_identitas' => $this->input->post('no_identitas'),
                'divisi' => $this->input->post('divisi'),
                'unit' => $this->input->post('unit'),
                'no_telp' => $this->input->post('no_telp'),
                'status' => $this->input->post('status'),
                'keterangan' => $this->input->post('keterangan')
            ]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Pengguna barang berhasil ditambahkan!</div>');
            redirect('master/penggunabarang');
        }
    }

    public function getdatapengguna()
    {
        try {
            $list = $this->pengguna_model->get_datatables();
            $data = [];
            $no = isset($_POST['start']) ? (int)$_POST['start'] : 0;

            foreach ($list as $row) {
                $no++;
                $data[] = [
                    'no' => $no,
                    'nama_pengguna' => $row->nama_pengguna,
                    'jenis_pengguna' => $row->jenis_pengguna,
                    'no_identitas' => $row->no_identitas,
                    'divisi' => $row->divisi,
                    'unit' => $row->unit,
                    'no_telp' => $row->no_telp,
                    'status' => $row->status,
                    'keterangan' => $row->keterangan
                ];
            }

            echo json_encode([
                "draw" => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,  
                "recordsTotal" => $this->pengguna_model->count_all(),
                "recordsFiltered" => $this->pengguna_model->count_filtered(),
                "data" => $data
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "error" => true,
                "message" => $e->getMessage()
            ]);
        }
    }


}