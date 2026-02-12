<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_DB $db
 * @property CI_Upload $upload
 * @property mutasi_model $mutasi_model
 */

class Mutasi extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('mutasi_model');
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
    }

    public function mutasi()
    {
        $data['title'] = 'Mutasi';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('user')->row_array();

        $data['mutasi_barang'] = []; // Kosongkan karena pakai DataTables AJAX

        $this->form_validation->set_rules('id_barang', 'ID Barang', 'required|trim');
        $this->form_validation->set_rules('tanggal_mutasi', 'Tanggal Mutasi', 'required|trim');
        $this->form_validation->set_rules('lokasi_asal', 'Lokasi Asal', 'required|trim');
        $this->form_validation->set_rules('lokasi_tujuan', 'Lokasi Tujuan', 'required|trim');
        $this->form_validation->set_rules('unit_asal', 'Unit Asal', 'trim');
        $this->form_validation->set_rules('unit_tujuan', 'Unit Tujuan', 'trim');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|trim');
        $this->form_validation->set_rules('penanggung_jawab', 'Penanggung Jawab', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('mutasi/mutasi', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('mutasi_barang', [
                'id_barang' => $this->input->post('id_barang'),
                'tanggal_mutasi' => $this->input->post('tanggal_mutasi'),
                'lokasi_asal' => $this->input->post('lokasi_asal'),
                'lokasi_tujuan' => $this->input->post('lokasi_tujuan'),
                'unit_asal' => $this->input->post('unit_asal'),
                'unit_tujuan' => $this->input->post('unit_tujuan'),
                'jumlah' => $this->input->post('jumlah'),
                'penanggung_jawab' => $this->input->post('penanggung_jawab'),
            ]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Mutasi berhasil ditambahkan!</div>');
            redirect('mutasi/mutasi');
        }
    }

    public function getmutasirow()
    {
        if (!$this->db->table_exists('mutasi_barang')) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([]));
            return;
        }

        $id = (int)$this->input->post('id_mutasi');
        if (!$id) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([]));
            return;
        }

        $row = $this->db->where('id_mutasi', $id)->get('mutasi_barang')->row_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($row ?: []));
    }

    public function updatemutasi()
    {
        if (!$this->db->table_exists('mutasi_barang')) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Tabel mutasi_barang belum ada']));
            return;
        }

        $this->form_validation->set_rules('id_mutasi', 'ID', 'required|integer');
        $this->form_validation->set_rules('id_barang', 'ID Barang', 'required|trim');
        $this->form_validation->set_rules('tanggal_mutasi', 'Tanggal Mutasi', 'required|trim');
        $this->form_validation->set_rules('lokasi_asal', 'Lokasi Asal', 'required|trim');
        $this->form_validation->set_rules('lokasi_tujuan', 'Lokasi Tujuan', 'required|trim');
        $this->form_validation->set_rules('unit_asal', 'Unit Asal', 'trim');
        $this->form_validation->set_rules('unit_tujuan', 'Unit Tujuan', 'trim');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|trim');
        $this->form_validation->set_rules('penanggung_jawab', 'Penanggung Jawab', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'errors' => [
                        'id_barang' => strip_tags(form_error('id_barang')),
                        'tanggal_mutasi' => strip_tags(form_error('tanggal_mutasi')),
                        'lokasi_asal' => strip_tags(form_error('lokasi_asal')),
                        'lokasi_tujuan' => strip_tags(form_error('lokasi_tujuan')),
                        'unit_asal' => strip_tags(form_error('unit_asal')),
                        'unit_tujuan' => strip_tags(form_error('unit_tujuan')),
                        'jumlah' => strip_tags(form_error('jumlah')),
                        'penanggung_jawab' => strip_tags(form_error('penanggung_jawab')),
                    ]
                ]));
            return;
        }

        $id = (int)$this->input->post('id_mutasi');
        $exists = $this->db->where('id_mutasi', $id)->get('mutasi_barang')->row_array();
        if (!$exists) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Data tidak ditemukan']));
            return;
        }

        $data = [
            'id_barang' => $this->input->post('id_barang'),
            'tanggal_mutasi' => $this->input->post('tanggal_mutasi'),
            'lokasi_asal' => $this->input->post('lokasi_asal'),
            'lokasi_tujuan' => $this->input->post('lokasi_tujuan'),
            'unit_asal' => $this->input->post('unit_asal'),
            'unit_tujuan' => $this->input->post('unit_tujuan'),
            'jumlah' => $this->input->post('jumlah'),
            'penanggung_jawab' => $this->input->post('penanggung_jawab'),
        ];

        $this->db->where('id_mutasi', $id)->update('mutasi_barang', $data);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true]));
    }

    public function deletemutasi()
    {
        if (!$this->db->table_exists('mutasi_barang')) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Tabel mutasi_barang belum ada']));
            return;
        }

        $id = (int)$this->input->post('id_mutasi');
        if (!$id) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'ID tidak valid']));
            return;
        }

        $this->db->delete('mutasi_barang', ['id_mutasi' => $id]);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true]));
    }

    public function get_datatables()
    {
        $list = $this->mutasi_model->get_datatables();
        $data = [];
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $mutasi) {
            $no++;
            $data[] = [
                'no' => $no,
                'id_barang' => $mutasi->id_barang,
                'tanggal_mutasi' => $mutasi->tanggal_mutasi,
                'lokasi_asal' => $mutasi->lokasi_asal,
                'lokasi_tujuan' => $mutasi->lokasi_tujuan,
                'unit_asal' => $mutasi->unit_asal,
                'unit_tujuan' => $mutasi->unit_tujuan,
                'jumlah' => $mutasi->jumlah,
                'penanggung_jawab' => $mutasi->penanggung_jawab,
                'aksi' => '<button class="btn btn-sm btn-primary btn-edit-mutasi" data-id_mutasi="'.$mutasi->id_mutasi.'">Edit</button>
                          <button class="btn btn-sm btn-danger btn-delete-mutasi" data-id_mutasi="'.$mutasi->id_mutasi.'">Delete</button>'
            ];
        }

        $output = [
            "draw" => isset($_POST['draw']) ? $_POST['draw'] : 0,
            "recordsTotal" => $this->mutasi_model->count_all(),
            "recordsFiltered" => $this->mutasi_model->count_filtered(),
            "data" => $data
        ];

        echo json_encode($output);
    }
}