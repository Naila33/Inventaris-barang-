 <?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_DB $db
 * @property CI_Upload $upload
 * @property Databarang_model $Databarang_model
 */

class Master extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('Databarang_model');
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

        $data['databarang'] = $this->db->get('databarang')->result_array();
        $data['open_modal'] = false;

        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
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
                'kategori' => $this->input->post('kategori'),
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

    public function getdatabarangrow()
    {
        $id = $this->input->post('id_barang');
        $row = $this->db->get_where('databarang', ['id_barang' => $id])->row_array();
        
        // Debug: log the query and result
        error_log('Looking for ID: ' . $id);
        error_log('Query result: ' . print_r($row, true));
        
        echo json_encode($row ?: []);
    }

    public function updatedatabarang()
    {
        $this->form_validation->set_rules('id_barang', 'ID', 'required|integer');
        $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required|trim');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
        $this->form_validation->set_rules('spesifikasi', 'Spesifikasi', 'required|trim');
        $this->form_validation->set_rules('satuan', 'Satuan', 'required|trim');
        $this->form_validation->set_rules('harga_perolehan', 'Harga Perolehan', 'required|trim');
        $this->form_validation->set_rules('tanggal_perolehan', 'Tanggal Perolehan', 'required|trim');
        $this->form_validation->set_rules('umur_ekonomis', 'Umur Ekonomis', 'required|trim');

        if ($this->form_validation->run() == false) {
            echo json_encode([
                'status' => false,
                'errors' => [
                    'kode_barang' => form_error('kode_barang'),
                    'nama_barang' => form_error('nama_barang'),
                    'kategori' => form_error('kategori'),
                    'spesifikasi' => form_error('spesifikasi'),
                    'satuan' => form_error('satuan'),
                    'harga_perolehan' => form_error('harga_perolehan'),
                    'tanggal_perolehan' => form_error('tanggal_perolehan'),
                    'umur_ekonomis' => form_error('umur_ekonomis')
                ]
            ]);
            return;
        }

        $id = (int)$this->input->post('id_barang');
        $data = [
            'nama_barang' => $this->input->post('nama_barang'),
            'kode_barang' => $this->input->post('kode_barang'),
            'kategori' => $this->input->post('kategori'),
            'spesifikasi' => $this->input->post('spesifikasi'),
            'satuan' => $this->input->post('satuan'),
            'harga_perolehan' => $this->input->post('harga_perolehan'),
            'tanggal_perolehan' => $this->input->post('tanggal_perolehan'),
            'umur_ekonomis' => $this->input->post('umur_ekonomis')
        ];
        $this->db->where('id_barang', $id)->update('databarang', $data);
        echo json_encode(['status' => true, 'message' => 'Data barang berhasil diupdate']);
    }

    public function deletedatabarang()
    {
        $id = (int)$this->input->post('id_barang');
        if ($id) {
            $this->db->delete('databarang', ['id_barang' => $id]);
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false, 'message' => 'ID tidak valid']);
        }
    }

    public function getdatabarang()
    {
        $list = $this->Databarang_model->get_datatables();
        $data = [];
        $no = isset($_POST['start']) ? (int)$_POST['start'] : 0;

        foreach ($list as $row) {
            $no++;
            $data[] = [
                'no' => $no,
                'kode_barang' => $row->kode_barang,
                'nama_barang' => $row->nama_barang,
                'kategori' => $row->kategori,
                'spesifikasi' => $row->spesifikasi,
                'satuan' => $row->satuan,
                'harga_perolehan' => $row->harga_perolehan,
                'tanggal_perolehan' => $row->tanggal_perolehan,
                'umur_ekonomis' => $row->umur_ekonomis,
                'aksi' => '<a href="#" class="badge badge-success btn-edit-barang" data-id="'.$row->id_barang.'">edit</a> '
                           .'<a href="#" class="badge badge-danger btn-delete-barang" data-id="'.$row->id_barang.'">delete</a>'
            ];
        }

        echo json_encode([
            "draw" => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,  
            "recordsTotal" => $this->Databarang_model->count_all(),
            "recordsFiltered" => $this->Databarang_model->count_filtered(),
            "data" => $data
        ]);
    }
}