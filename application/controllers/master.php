 <?php

defined('BASEPATH') or exit('No direct script access allowed');

/**

 * @property CI_Form_validation $form_validation

 * @property CI_Input $input

 * @property CI_Session $session

 * @property CI_DB $db

 * @property CI_Upload $upload

 * @property Databarang_model $Databarang_model

 * @property Kategoribarang_model $Kategoribarang_model

 * @property Lokasi_model $Lokasi_model

 * @property supplier_model $supplier_model

 * @property pengguna_model $pengguna_model

 */



class Master extends CI_Controller

{



    public function __construct()

    {

        parent::__construct();

        $this->load->library('session');

        $this->load->library('form_validation');

        $this->load->model('Databarang_model');

        $this->load->model('Kategoribarang_model');

        $this->load->model('Lokasi_model');

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



        $this->load->model('Kategoribarang_model');

        $data['kategori'] = $this->db->get('kategoribarang')->result_array();

        $data['lokasi'] = $this->db->get('lokasi')->result_array(); // Tambah data lokasi

        $data['databarang'] = $this->db->get('databarang')->result_array();

        $data['open_modal'] = false;



        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');

        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required|integer');

        $this->form_validation->set_rules('spesifikasi', 'Spesifikasi', 'required|trim');

        $this->form_validation->set_rules('satuan', 'Satuan', 'required|trim');
        $this->form_validation->set_rules('id_lokasi', 'Lokasi', 'required|integer');
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
                'id_kategori' => (int)$this->input->post('id_kategori'),
                'spesifikasi' => $this->input->post('spesifikasi'),
                'satuan' => $this->input->post('satuan'),
                'id_lokasi' => (int)$this->input->post('id_lokasi'), // Tambah field id_lokasi
                'harga_perolehan' => $this->input->post('harga_perolehan'),
                'tanggal_perolehan' => $this->input->post('tanggal_perolehan'),
                'umur_ekonomis' => $this->input->post('umur_ekonomis')
            ]);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data barang berhasil ditambahkan!</div>');
            redirect('master/databarang');

        }

    }



    public function addbarang()

    {

        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');

        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required|integer');

        $this->form_validation->set_rules('spesifikasi', 'Spesifikasi', 'required|trim');

        $this->form_validation->set_rules('satuan', 'Satuan', 'required|trim');
        $this->form_validation->set_rules('id_lokasi', 'Lokasi', 'required|integer');
        $this->form_validation->set_rules('harga_perolehan', 'Harga Perolehan', 'required|trim');

        $this->form_validation->set_rules('tanggal_perolehan', 'Tanggal Perolehan', 'required|trim');

        $this->form_validation->set_rules('umur_ekonomis', 'Umur Ekonomis', 'required|trim');



        if ($this->form_validation->run() == false) {

            $this->output

                ->set_content_type('application/json')

                ->set_output(json_encode([

                    'status' => false,

                    'errors' => [

                        'nama_barang' => strip_tags(form_error('nama_barang')),

                        'id_kategori' => strip_tags(form_error('id_kategori')),

                        'spesifikasi' => strip_tags(form_error('spesifikasi')),

                        'satuan' => strip_tags(form_error('satuan')),
                        'id_lokasi' => strip_tags(form_error('id_lokasi')),
                        'harga_perolehan' => strip_tags(form_error('harga_perolehan')),

                        'tanggal_perolehan' => strip_tags(form_error('tanggal_perolehan')),

                        'umur_ekonomis' => strip_tags(form_error('umur_ekonomis')),

                    ]

                ]));

            return;

        }



        $this->db->insert('databarang', [

            'nama_barang' => $this->input->post('nama_barang'),

            'id_kategori' => (int)$this->input->post('id_kategori'),

            'spesifikasi' => $this->input->post('spesifikasi'),

            'satuan' => $this->input->post('satuan'),

            'id_lokasi' => (int)$this->input->post('id_lokasi'), // Tambah field id_lokasi

            'harga_perolehan' => $this->input->post('harga_perolehan'),

            'tanggal_perolehan' => $this->input->post('tanggal_perolehan'),

            'umur_ekonomis' => $this->input->post('umur_ekonomis'),

        ]);



        $this->output

            ->set_content_type('application/json')

            ->set_output(json_encode(['status' => true]));

    }



    public function getbarangrow()

    {

        $id = (int)$this->input->post('id_barang');

        if (!$id) {

            $this->output

                ->set_content_type('application/json')

                ->set_output(json_encode([]));

            return;

        }



        $row = $this->db->select('databarang.*, lokasi.nama_lokasi')
            ->from('databarang')
            ->join('lokasi', 'lokasi.id_lokasi = databarang.id_lokasi', 'left')
            ->where('databarang.id_barang', $id)
            ->get()
            ->row_array();

        $this->output

            ->set_content_type('application/json')

            ->set_output(json_encode($row ?: []));

    }



    public function updatebarang()

    {

        $this->form_validation->set_rules('id_barang', 'ID', 'required|integer');

        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');

        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required|integer');

        $this->form_validation->set_rules('spesifikasi', 'Spesifikasi', 'required|trim');

        $this->form_validation->set_rules('satuan', 'Satuan', 'required|trim');
        $this->form_validation->set_rules('id_lokasi', 'Lokasi', 'required|integer');
        $this->form_validation->set_rules('harga_perolehan', 'Harga Perolehan', 'required|trim');

        $this->form_validation->set_rules('tanggal_perolehan', 'Tanggal Perolehan', 'required|trim');

        $this->form_validation->set_rules('umur_ekonomis', 'Umur Ekonomis', 'required|trim');



        if ($this->form_validation->run() == false) {

            $this->output

                ->set_content_type('application/json')

                ->set_output(json_encode([

                    'status' => false,

                    'errors' => [

                        'nama_barang' => strip_tags(form_error('nama_barang')),

                        'id_kategori' => strip_tags(form_error('id_kategori')),

                        'spesifikasi' => strip_tags(form_error('spesifikasi')),

                        'satuan' => strip_tags(form_error('satuan')),
                        'id_lokasi' => strip_tags(form_error('id_lokasi')),
                        'harga_perolehan' => strip_tags(form_error('harga_perolehan')),

                        'tanggal_perolehan' => strip_tags(form_error('tanggal_perolehan')),

                        'umur_ekonomis' => strip_tags(form_error('umur_ekonomis')),

                    ]

                ]));

            return;

        }



        $id = (int)$this->input->post('id_barang');

        $this->db->where('id_barang', $id)->update('databarang', [

            'nama_barang' => $this->input->post('nama_barang'),

            'id_kategori' => (int)$this->input->post('id_kategori'),

            'spesifikasi' => $this->input->post('spesifikasi'),

            'satuan' => $this->input->post('satuan'),

            'id_lokasi' => (int)$this->input->post('id_lokasi'), // Tambah field id_lokasi

            'harga_perolehan' => $this->input->post('harga_perolehan'),

            'tanggal_perolehan' => $this->input->post('tanggal_perolehan'),

            'umur_ekonomis' => $this->input->post('umur_ekonomis'),

        ]);



        $this->output

            ->set_content_type('application/json')

            ->set_output(json_encode(['status' => true]));

    }



    public function deletebarang()

    {

        $id = (int)$this->input->post('id_barang');

        if (!$id) {

            $this->output

                ->set_content_type('application/json')

                ->set_output(json_encode(['status' => false, 'message' => 'ID tidak valid']));

            return;

        }



        $this->db->delete('databarang', ['id_barang' => $id]);

        $this->output

            ->set_content_type('application/json')

            ->set_output(json_encode(['status' => true]));

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

                    'kategori' => isset($row->nama_kategori) ? $row->nama_kategori : '',

                    'spesifikasi' => $row->spesifikasi,

                    'satuan' => $row->satuan,

                    'lokasi' => $row->nama_lokasi ?? '',

                    'harga_perolehan' => $row->harga_perolehan,

                    'tanggal_perolehan' => $row->tanggal_perolehan,

                    'umur_ekonomis' => $row->umur_ekonomis,

                    'aksi' => '<button class="btn btn-sm btn-primary btn-edit-barang" data-id_barang="' . $row->id_barang . '">Edit</button> <button class="btn btn-sm btn-danger btn-delete-barang" data-id_barang="' . $row->id_barang . '">Delete</button>'

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

                    'aksi' => '<button class="btn btn-sm btn-danger btn-delete-kategori" data-id_kategori="' . $row->id_kategori . '">Hapus</button>'

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



    public function getkategorirow()

    {

        $id = (int)$this->input->post('id_kategori');

        if (!$id) {

            $this->output

                ->set_content_type('application/json')

                ->set_output(json_encode([]));

            return;

        }



        $row = $this->db->where('id_kategori', $id)->get('kategoribarang')->row_array();

        $this->output

            ->set_content_type('application/json')

            ->set_output(json_encode($row ?: []));

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

                    'aksi' => '<button class="btn btn-sm btn-primary btn-edit-lokasi" data-id_lokasi="' . $row->id_lokasi . '">Edit</button> <button class="btn btn-sm btn-danger btn-delete-lokasi" data-id_lokasi="' . $row->id_lokasi . '">Hapus</button>'

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



    public function getlokasirow()

    {

        $id = (int)$this->input->post('id_lokasi');

        if (!$id) {

            $this->output

                ->set_content_type('application/json')

                ->set_output(json_encode([]));

            return;

        }



        $row = $this->db->where('id_lokasi', $id)->get('lokasi')->row_array();

        $this->output

            ->set_content_type('application/json')

            ->set_output(json_encode($row ?: []));

    }



    public function updatelokasi()

    {

        $this->form_validation->set_rules('id_lokasi', 'ID', 'required|integer');

        $this->form_validation->set_rules('kode_lokasi', 'Kode Lokasi', 'required|trim');

        $this->form_validation->set_rules('nama_lokasi', 'Nama Lokasi', 'required|trim');

        $this->form_validation->set_rules('gedung', 'Gedung', 'required|trim');

        $this->form_validation->set_rules('lantai', 'Lantai', 'required|trim');

        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|trim');



        if ($this->form_validation->run() == false) {

            $this->output

                ->set_content_type('application/json')

                ->set_output(json_encode([

                    'status' => false,

                    'errors' => [

                        'kode_lokasi' => strip_tags(form_error('kode_lokasi')),

                        'nama_lokasi' => strip_tags(form_error('nama_lokasi')),

                        'gedung' => strip_tags(form_error('gedung')),

                        'lantai' => strip_tags(form_error('lantai')),

                        'keterangan' => strip_tags(form_error('keterangan')),

                    ]

                ]));

            return;

        }



        $id = (int)$this->input->post('id_lokasi');

        $this->db->where('id_lokasi', $id)->update('lokasi', [

            'kode_lokasi' => $this->input->post('kode_lokasi'),

            'nama_lokasi' => $this->input->post('nama_lokasi'),

            'gedung' => $this->input->post('gedung'),

            'lantai' => $this->input->post('lantai'),

            'keterangan' => $this->input->post('keterangan'),

        ]);



        $this->output

            ->set_content_type('application/json')

            ->set_output(json_encode(['status' => true]));

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

                    'status' => $row->status,

                    'aksi' => '<button class="btn btn-sm btn-primary btn-edit-supplier" data-id_supplier="' . $row->id_supplier . '">Edit</button> <button class="btn btn-sm btn-danger btn-delete-supplier" data-id_supplier="' . $row->id_supplier . '">Hapus</button>'

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



    public function getsupplierrow()

    {

        $id = (int)$this->input->post('id_supplier');

        if (!$id) {

            $this->output

                ->set_content_type('application/json')

                ->set_output(json_encode([]));

            return;

        }



        $row = $this->db->where('id_supplier', $id)->get('supplier')->row_array();

        $this->output

            ->set_content_type('application/json')

            ->set_output(json_encode($row ?: []));

    }



    public function updatesupplier()

    {

        $this->form_validation->set_rules('id_supplier', 'ID', 'required|integer');

        $this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'required|trim');

        $this->form_validation->set_rules('kontak', 'Kontak Supplier', 'required|trim');

        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'required|trim');

        $this->form_validation->set_rules('kota', 'Kota', 'required|trim');

        $this->form_validation->set_rules('status', 'Status', 'required|trim');



        if ($this->form_validation->run() == false) {

            $this->output

                ->set_content_type('application/json')

                ->set_output(json_encode([

                    'status' => false,

                    'errors' => [

                        'nama_supplier' => strip_tags(form_error('nama_supplier')),

                        'kontak' => strip_tags(form_error('kontak')),

                        'no_telp' => strip_tags(form_error('no_telp')),

                        'kota' => strip_tags(form_error('kota')),

                        'status' => strip_tags(form_error('status')),

                    ]

                ]));

            return;

        }



        $id = (int)$this->input->post('id_supplier');

        $this->db->where('id_supplier', $id)->update('supplier', [

            'nama_supplier' => $this->input->post('nama_supplier'),

            'kontak' => $this->input->post('kontak'),

            'no_telp' => $this->input->post('no_telp'),

            'kota' => $this->input->post('kota'),

            'status' => $this->input->post('status'),

        ]);



        $this->output

            ->set_content_type('application/json')

            ->set_output(json_encode(['status' => true]));

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

                    'keterangan' => $row->keterangan,

                    'aksi' => '<button class="btn btn-sm btn-primary btn-edit-pengguna" data-id_pengguna="' . $row->id_pengguna . '">Edit</button> <button class="btn btn-sm btn-danger btn-delete-pengguna" data-id_pengguna="' . $row->id_pengguna . '">Hapus</button>'

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



    public function getpenggunarow()

    {

        $id = (int)$this->input->post('id_pengguna');

        if (!$id) {

            $this->output

                ->set_content_type('application/json')

                ->set_output(json_encode([]));

            return;

        }



        $row = $this->db->where('id_pengguna', $id)->get('pengguna_barang')->row_array();

        $this->output

            ->set_content_type('application/json')

            ->set_output(json_encode($row ?: []));

    }



    public function updatepengguna()

    {

        $this->form_validation->set_rules('id_pengguna', 'ID', 'required|integer');

        $this->form_validation->set_rules('nama_pengguna', 'Nama Pengguna', 'required|trim');

        $this->form_validation->set_rules('jenis_pengguna', 'Jenis Pengguna', 'required|trim');

        $this->form_validation->set_rules('no_identitas', 'Nomor Identitas', 'required|trim');

        $this->form_validation->set_rules('divisi', 'Divisi', 'required|trim');

        $this->form_validation->set_rules('unit', 'Unit', 'required|trim');

        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'required|trim');

        $this->form_validation->set_rules('status', 'Status', 'required|trim');

        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|trim');



        if ($this->form_validation->run() == false) {

            $this->output

                ->set_content_type('application/json')

                ->set_output(json_encode([

                    'status' => false,

                    'errors' => [

                        'nama_pengguna' => strip_tags(form_error('nama_pengguna')),

                        'jenis_pengguna' => strip_tags(form_error('jenis_pengguna')),

                        'no_identitas' => strip_tags(form_error('no_identitas')),

                        'divisi' => strip_tags(form_error('divisi')),

                        'unit' => strip_tags(form_error('unit')),

                        'no_telp' => strip_tags(form_error('no_telp')),

                        'status' => strip_tags(form_error('status')),

                        'keterangan' => strip_tags(form_error('keterangan')),

                    ]

                ]));

            return;

        }



        $id = (int)$this->input->post('id_pengguna');

        $this->db->where('id_pengguna', $id)->update('pengguna_barang', [

            'nama_pengguna' => $this->input->post('nama_pengguna'),

            'jenis_pengguna' => $this->input->post('jenis_pengguna'),

            'no_identitas' => $this->input->post('no_identitas'),

            'divisi' => $this->input->post('divisi'),

            'unit' => $this->input->post('unit'),

            'no_telp' => $this->input->post('no_telp'),

            'status' => $this->input->post('status'),

            'keterangan' => $this->input->post('keterangan'),

        ]);



        $this->output

            ->set_content_type('application/json')

            ->set_output(json_encode(['status' => true]));

    }

}



