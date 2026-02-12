<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_DB $db
 * @property CI_Upload $upload
 * @property Barangin_model $barangin_model
 */

class Barang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('barangin_model');
        if (!$this->session->userdata('email')) {
            if ($this->input->is_ajax_request()) {
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['status' => false, 'message' => 'Unauthorized']));
                return;
            }
            redirect('auth');
        }
    }

    public function getbarangmasukrow()
    {
        if (!$this->db->table_exists('barangin')) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([]));
            return;
        }

        $id = (int)$this->input->post('id_barangin');
        if (!$id) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([]));
            return;
        }

        $row = $this->db->where('id_barangin', $id)->get('barangin')->row_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($row ?: []));
    }

    public function updatebarangmasuk()
    {
        if (!$this->db->table_exists('barangin')) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Tabel barangin belum ada']));
            return;
        }

        $this->form_validation->set_rules('id_barangin', 'ID', 'required|integer');
        $this->form_validation->set_rules('tgl_masuk', 'Tanggal Masuk', 'required|trim');
        $this->form_validation->set_rules('sumberbarang', 'Sumber Barang', 'required|trim');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'errors' => [
                        'tgl_masuk' => strip_tags(form_error('tgl_masuk')),
                        'sumberbarang' => strip_tags(form_error('sumberbarang')),
                        'jumlah' => strip_tags(form_error('jumlah')),
                        'dokumen_pendukung' => '',
                    ]
                ]));
            return;
        }

        $id = (int)$this->input->post('id_barangin');
        $exists = $this->db->where('id_barangin', $id)->get('barangin')->row_array();
        if (!$exists) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Data tidak ditemukan']));
            return;
        }

        $data = [
            'tgl_masuk' => $this->input->post('tgl_masuk'),
            'sumberbarang' => $this->input->post('sumberbarang'),
            'jumlah' => $this->input->post('jumlah'),
        ];

        if (isset($_FILES['dokumen_pendukung']) && !empty($_FILES['dokumen_pendukung']['name'])) {
            $uploadPath = FCPATH . 'assets/uploads/barangin';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $config = [
                'upload_path' => $uploadPath,
                'allowed_types' => 'pdf',
                'max_size' => 5120,
                'encrypt_name' => false,
                'overwrite' => false,
            ];

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('dokumen_pendukung')) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => false,
                        'errors' => [
                            'dokumen_pendukung' => strip_tags($this->upload->display_errors('', '')),
                        ]
                    ]));
                return;
            }

            $fileData = $this->upload->data();
            $data['dokumen_pendukung'] = $fileData['file_name'];
        }

        if (!isset($data['dokumen_pendukung'])) {
            $data['dokumen_pendukung'] = $exists['dokumen_pendukung'];
        }

        $this->db->where('id_barangin', $id)->update('barangin', $data);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true]));
    }

    public function deletebarangmasuk()
    {
        if (!$this->db->table_exists('barangin')) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'Tabel barangin belum ada']));
            return;
        }

        $id = (int)$this->input->post('id_barangin');
        if (!$id) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'ID tidak valid']));
            return;
        }

        $this->db->delete('barangin', ['id_barangin' => $id]);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true]));
    }

    public function barangin()
    {
        $data['title'] = 'Barang Masuk';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('user')->row_array();

        $data['barangin'] = $this->db->get('barangin')->result_array();

        $this->form_validation->set_rules('tgl_masuk', 'Tanggal Masuk', 'required|trim');
        $this->form_validation->set_rules('sumberbarang', 'Sumber Barang', 'required|trim');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('barang/barang_masuk', $data);
            $this->load->view('templates/footer');
        } else {
            if (!isset($_FILES['dokumen_pendukung']) || empty($_FILES['dokumen_pendukung']['name'])) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Dokumen pendukung wajib diupload (PDF).</div>');
                redirect('barang/barangin');
                return;
            }

            $uploadPath = FCPATH . 'assets/uploads/barangin';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $config = [
                'upload_path' => $uploadPath,
                'allowed_types' => 'pdf',
                'max_size' => 5120,
                'encrypt_name' => false,
                'overwrite' => false,
            ];

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('dokumen_pendukung')) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Upload dokumen gagal: ' . strip_tags($this->upload->display_errors('', '')) . '</div>');
                redirect('barang/barangin');
                return;
            }

            $fileData = $this->upload->data();
            $this->db->insert('barangin', [
                'tgl_masuk' => $this->input->post('tgl_masuk'),
                'sumberbarang' => $this->input->post('sumberbarang'),
                'jumlah' => $this->input->post('jumlah'),
                'dokumen_pendukung' => $fileData['file_name'],
            ]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Barang masuk berhasil ditambahkan!</div>');
            redirect('barang/barangin');
        }
    }

    public function getdatabarangmasuk()
    {
        try {
            if (!$this->db->table_exists('barangin')
                || !$this->db->field_exists('id_barangin', 'barangin')
                || !$this->db->field_exists('tgl_masuk', 'barangin')
                || !$this->db->field_exists('sumberbarang', 'barangin')
                || !$this->db->field_exists('jumlah', 'barangin')
                || !$this->db->field_exists('dokumen_pendukung', 'barangin')) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        "draw" => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,
                        "recordsTotal" => 0,
                        "recordsFiltered" => 0,
                        "data" => [],
                        "error" => true,
                        "message" => "Tabel/kolom barangin belum sesuai (butuh: id_barangin,tgl_masuk,sumberbarang,jumlah,dokumen_pendukung)"
                    ]));
                return;
            }

            $list = $this->barangin_model->get_datatables();
            $data = [];
            $no = isset($_POST['start']) ? (int)$_POST['start'] : 0;

            foreach ($list as $row) {
                $no++;
                $dokUrl = '';
                if (!empty($row->dokumen_pendukung)) {
                    $dokUrl = '<a href="' . base_url('assets/uploads/barangin/' . $row->dokumen_pendukung) . '" target="_blank">' . $row->dokumen_pendukung . '</a>';
                }
                $data[] = [
                    'no' => $no,
                    'tgl_masuk' => $row->tgl_masuk,
                    'sumberbarang' => $row->sumberbarang,
                    'jumlah' => $row->jumlah,
                    'dokumen_pendukung' => $dokUrl,
                    'aksi' => '<button class="btn btn-sm btn-primary btn-edit-barangmasuk" data-id_barangin="'.$row->id_barangin.'">Edit</button>
                           <button class="btn btn-sm btn-danger btn-delete-barangmasuk" data-id_barangin="'.$row->id_barangin.'">Delete</button>'
                ];
            }

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    "draw" => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,
                    "recordsTotal" => $this->barangin_model->count_all(),
                    "recordsFiltered" => $this->barangin_model->count_filtered(),
                    "data" => $data
                ]));
        } catch (Exception $e) {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    "error" => true,
                    "message" => $e->getMessage()
                ]));
        }
    }

    public function barangout()
    {
        $data['title'] = 'Barang Keluar';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('user')->row_array();

        $data['barangkeluar'] = $this->db->get('barangout')->result_array();

        $this->form_validation->set_rules('id_barang', 'ID Barang', 'required|trim');
        $this->form_validation->set_rules('tgl_keluar', 'Tanggal Keluar', 'required|trim');
        $this->form_validation->set_rules('jenis_tras', 'Jenis Transaksi', 'required|trim');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required|trim');
        $this->form_validation->set_rules('pj', 'Tujuan', 'required|trim');
        $this->form_validation->set_rules('jumlah', 'Tujuan', 'required|trim');
        $this->form_validation->set_rules('batas_wp', 'Tujuan', 'required|trim');
        $this->form_validation->set_rules('tgl_kembali', 'Tujuan', 'required|trim');
        $this->form_validation->set_rules('status_keterlambatan', 'Tujuan', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('barang/barang_keluar', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('barangout', [
                'tgl_keluar' => $this->input->post('tgl_keluar'),
                'id_barang' => $this->input->post('id_barang'),
                'jumlah' => $this->input->post('jumlah'),
                'dokumen_pendukung' => $this->input->post('dokumen_pendukung'),
            ]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Barang keluar berhasil ditambahkan!</div>');
            redirect('barang/barangout');
        }
    }
}