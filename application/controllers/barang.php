<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_DB $db
 * @property CI_Upload $upload
 * @property Barang_model $barang_model
 * @property Barangin_model $barangin_model
 * @property Barangout_model $Barangout_model
 * @property Databarang_model $Databarang_model
 */

class Barang extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->model('barang_model');
        $this->load->model('barangin_model');
        $this->load->model('Barangout_model');
        $this->load->model('Databarang_model');
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

    public function index()
    {
        $data['title'] = 'Barang';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('user')->row_array();
        $data['barang'] = $this->barang_model->get_all();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('barang/index', $data);
        $this->load->view('templates/footer');
    }

    public function tambah()
    {
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required|trim');
        $this->form_validation->set_rules('satuan', 'Satuan', 'required|trim');
        $this->form_validation->set_rules('harga', 'Harga Perolehan', 'required|trim|numeric');
        $this->form_validation->set_rules('tanggal', 'Tanggal Perolehan', 'required|trim');
        $this->form_validation->set_rules('umur', 'Umur Ekonomis', 'required|trim|numeric');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data barang gagal ditambahkan. Periksa kembali inputan Anda!</div>');
            redirect('barang');
        } else {
            // Generate QR Code (if GD library is available)
            $qr_code = '';
            $kode_barang = 'BRG' . date('YmdHis');
            
            if (extension_loaded('gd') && function_exists('ImageCreate')) {
                $this->load->library('ciqrcode');
                $qr_code = $kode_barang . '.png';
                
                $config['cacheable']    = true;
                $config['cachedir']     = './assets/';
                $config['errorlog']     = './assets/';
                $config['imagedir']     = './assets/img/qrcode/';
                $config['quality']      = true;
                $config['size']         = '1024';
                $config['black']        = array(224,255,255);
                $config['white']        = array(70,130,180);
                
                $params['data'] = $kode_barang;
                $params['level'] = 'H';
                $params['size'] = 10;
                $params['savename'] = FCPATH.$config['imagedir'].$qr_code;
                
                $this->ciqrcode->generate($params);
            } else {
                // GD library not available, skip QR code generation
                $qr_code = '';
                log_message('error', 'GD library not available - QR code generation skipped');
            }

            // Handle file upload
            $foto = '';
            if (isset($_FILES['foto']) && $_FILES['foto']['name'] != '') {
                $config['upload_path'] = './assets/img/barang/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = 2048;
                $config['encrypt_name'] = TRUE;
                
                $this->upload->initialize($config);
                
                if ($this->upload->do_upload('foto')) {
                    $upload_data = $this->upload->data();
                    $foto = $upload_data['file_name'];
                }
            }

            // Prepare data for insertion
            $data = [
                'kode_barang' => $kode_barang,
                'qr_code' => $qr_code,
                'nama_barang' => $this->input->post('nama_barang'),
                'kategori' => $this->input->post('kategori'),
                'spesifikasi' => $this->input->post('spesifikasi'),
                'satuan' => $this->input->post('satuan'),
                'harga_perolehan' => str_replace('.', '', $this->input->post('harga')),
                'tanggal_perolehan' => $this->input->post('tanggal'),
                'umur_ekonomis' => $this->input->post('umur'),
                'foto' => $foto
            ];

            $this->barang_model->insert($data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data barang berhasil ditambahkan!</div>');
            redirect('barang');
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

            $this->upload->initialize($config);
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
                    'aksi' => '<button class="btn btn-sm btn-primary btn-edit-barangmasuk" data-id_barangin="' . $row->id_barangin . '">Edit</button>
                           <button class="btn btn-sm btn-danger btn-delete-barangmasuk" data-id_barangin="' . $row->id_barangin . '">Delete</button>'
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
        $this->form_validation->set_rules('pj', 'Penanggung jawab', 'required|trim');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|trim');
        $this->form_validation->set_rules('batas_wp', 'Batas waktu', 'required|trim');
        $this->form_validation->set_rules('tgl_kembali', 'Tanggal kembali', 'required|trim');
        $this->form_validation->set_rules('status_keterlambatan', 'Status keterlambatan', 'required|trim|in_list[Tepat Waktu,Terlambat,Belum Kembali]');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('barang/barang_keluar', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('barangout', [
                'id_barang' => $this->input->post('id_barang'),
                'tgl_keluar' => $this->input->post('tgl_keluar'),
                'jenis_tras' => $this->input->post('jenis_tras'),
                'tujuan' => $this->input->post('tujuan'),
                'pj' => $this->input->post('pj'),
                'jumlah' => $this->input->post('jumlah'),
                'batas_wp' => $this->input->post('batas_wp'),
                'tgl_kembali' => $this->input->post('tgl_kembali'),
                'status_keterlambatan' => $this->input->post('status_keterlambatan'),
            ]);

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => true]));
        }
    }

    public function getbarangkeluarrow()
    {
        $id = (int)$this->input->post('id_barangout');
        if (!$id) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([]));
            return;
        }

        $row = $this->db->where('id_barangout', $id)->get('barangout')->row_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($row ?: []));
    }

    public function addbarangkeluar()
    {
        $this->form_validation->set_rules('id_barang', 'ID Barang', 'required|trim');
        $this->form_validation->set_rules('tgl_keluar', 'Tanggal Keluar', 'required|trim');
        $this->form_validation->set_rules('jenis_tras', 'Jenis Transaksi', 'required|trim|in_list[Dipinjam,Dipindahkan,Dihapus]');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required|trim');
        $this->form_validation->set_rules('pj', 'Penanggung jawab', 'required|trim');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|trim');
        $this->form_validation->set_rules('batas_wp', 'Batas waktu', 'required|trim');
        $this->form_validation->set_rules('tgl_kembali', 'Tanggal kembali', 'required|trim');
        $this->form_validation->set_rules('status_keterlambatan', 'Status keterlambatan', 'required|trim|in_list[Tepat Waktu,Terlambat,Belum Kembali]');

        if ($this->form_validation->run() == false) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'errors' => [
                        'id_barang' => strip_tags(form_error('id_barang')),
                        'tgl_keluar' => strip_tags(form_error('tgl_keluar')),
                        'jenis_tras' => strip_tags(form_error('jenis_tras')),
                        'tujuan' => strip_tags(form_error('tujuan')),
                        'pj' => strip_tags(form_error('pj')),
                        'jumlah' => strip_tags(form_error('jumlah')),
                        'batas_wp' => strip_tags(form_error('batas_wp')),
                        'tgl_kembali' => strip_tags(form_error('tgl_kembali')),
                        'status_keterlambatan' => strip_tags(form_error('status_keterlambatan')),
                    ]
                ]));
            return;
        }

        $this->db->insert('barangout', [
            'id_barang' => $this->input->post('id_barang'),
            'tgl_keluar' => $this->input->post('tgl_keluar'),
            'jenis_tras' => $this->input->post('jenis_tras'),
            'tujuan' => $this->input->post('tujuan'),
            'pj' => $this->input->post('pj'),
            'jumlah' => $this->input->post('jumlah'),
            'batas_wp' => $this->input->post('batas_wp'),
            'tgl_kembali' => $this->input->post('tgl_kembali'),
            'status_keterlambatan' => $this->input->post('status_keterlambatan'),
        ]);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true]));
    }

    public function updatebarangkeluar()
    {
        $this->form_validation->set_rules('id_barangout', 'ID', 'required|integer');
        $this->form_validation->set_rules('id_barang', 'ID Barang', 'required|trim');
        $this->form_validation->set_rules('tgl_keluar', 'Tanggal Keluar', 'required|trim');
        $this->form_validation->set_rules('jenis_tras', 'Jenis Transaksi', 'required|trim|in_list[Dipinjam,Dipindahkan,Dihapus]');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required|trim');
        $this->form_validation->set_rules('pj', 'Penanggung jawab', 'required|trim');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|trim');
        $this->form_validation->set_rules('batas_wp', 'Batas waktu', 'required|trim');
        $this->form_validation->set_rules('tgl_kembali', 'Tanggal kembali', 'required|trim');
        $this->form_validation->set_rules('status_keterlambatan', 'Status keterlambatan', 'required|trim|in_list[Tepat Waktu,Terlambat,Belum Kembali]');

        if ($this->form_validation->run() == false) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'errors' => [
                        'id_barang' => strip_tags(form_error('id_barang')),
                        'tgl_keluar' => strip_tags(form_error('tgl_keluar')),
                        'jenis_tras' => strip_tags(form_error('jenis_tras')),
                        'tujuan' => strip_tags(form_error('tujuan')),
                        'pj' => strip_tags(form_error('pj')),
                        'jumlah' => strip_tags(form_error('jumlah')),
                        'batas_wp' => strip_tags(form_error('batas_wp')),
                        'tgl_kembali' => strip_tags(form_error('tgl_kembali')),
                        'status_keterlambatan' => strip_tags(form_error('status_keterlambatan')),
                    ]
                ]));
            return;
        }

        $id = (int)$this->input->post('id_barangout');
        $this->db->where('id_barangout', $id)->update('barangout', [
            'id_barang' => $this->input->post('id_barang'),
            'tgl_keluar' => $this->input->post('tgl_keluar'),
            'jenis_tras' => $this->input->post('jenis_tras'),
            'tujuan' => $this->input->post('tujuan'),
            'pj' => $this->input->post('pj'),
            'jumlah' => $this->input->post('jumlah'),
            'batas_wp' => $this->input->post('batas_wp'),
            'tgl_kembali' => $this->input->post('tgl_kembali'),
            'status_keterlambatan' => $this->input->post('status_keterlambatan'),
        ]);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true]));
    }

    public function deletebarangkeluar()
    {
        $id = (int)$this->input->post('id_barangout');
        if (!$id) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'ID tidak valid']));
            return;
        }

        $this->db->delete('barangout', ['id_barangout' => $id]);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true]));
    }

    public function getdatabarangkeluar()
    {
        try {
            $list = $this->Barangout_model->get_datatables();
            $data = [];

            $no = isset($_POST['start']) ? (int)$_POST['start'] : 0;

            foreach ($list as $row) {
                $no++;
                $data[] = [
                    'no' => $no,
                    'id_barang' => $row->id_barang,
                    'tgl_keluar' => $row->tgl_keluar,
                    'jenis_tras' => $row->jenis_tras,
                    'tujuan' => $row->tujuan,
                    'pj' => $row->pj,
                    'jumlah' => $row->jumlah,
                    'batas_wp' => $row->batas_wp,
                    'tgl_kembali' => $row->tgl_kembali,
                    'status_keterlambatan' => $row->status_keterlambatan,
                    'aksi' => '<button class="btn btn-sm btn-primary btn-edit-barangkeluar" data-id_barangout="' . $row->id_barangout . '">Edit</button> <button class="btn btn-sm btn-danger btn-delete-barangkeluar" data-id_barangout="' . $row->id_barangout . '">Delete</button>'
                ];
            }

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'draw' => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,
                    'recordsTotal' => $this->Barangout_model->count_all(),
                    'recordsFiltered' => $this->Barangout_model->count_filtered(),
                    'data' => $data
                ]));
        } catch (Exception $e) {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'error' => true,
                    'message' => $e->getMessage()
                ]));
        }
    }
}