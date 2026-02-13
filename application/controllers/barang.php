<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('barang_model');
        $this->load->library(['upload','ciqrcode']);
    }

    public function index()
    {
        $data['barang'] = $this->barang_model->get_all();
        $this->load->view('templates/header');
        $this->load->view('barang/index', $data);
        $this->load->view('templates/footer');
    }

    public function tambah()
    {
        if ($this->input->post()) {

            // 🔢 KODE BARANG OTOMATIS
            $kode = $this->barang_model->kode_barang();

            // 📷 UPLOAD FOTO
            $config['upload_path'] = FCPATH.'assets/img/barang/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $this->upload->initialize($config);
            $this->upload->do_upload('foto');
            $foto = $this->upload->data('file_name');

            // 🔳 GENERATE QR CODE
            $qr = $kode . '.png';
            $params['data'] = $kode;
            $params['savename'] = FCPATH.'assets/img/qrcode/'.$qr;
            $this->ciqrcode->generate($params);

            $data = [
                'kode_barang'       => $kode,
                'nama_barang'       => $this->input->post('nama_barang'),
                'kategori'          => $this->input->post('kategori'),
                'spesifikasi'       => $this->input->post('spesifikasi'),
                'satuan'            => $this->input->post('satuan'),
                'harga_perolehan'   => $this->input->post('harga'),
                'tanggal_perolehan' => $this->input->post('tanggal'),
                'umur_ekonomis'     => $this->input->post('umur'),
                'foto'              => $foto,
                'qr_code'           => $qr
            ];

            $this->barang_model->insert($data);
            redirect('barang');
        }

        $this->load->view('templates/header');
        $this->load->view('barang/tambah');
        $this->load->view('templates/footer');
    }

    public function update()
{
    $id = $this->input->post('id_barang');

    $data = [
        'nama_barang'       => $this->input->post('nama_barang'),
        'kategori'          => $this->input->post('kategori'),
        'harga_perolehan'   => $this->input->post('harga'),
        'spesifikasi'       => $this->input->post('spesifikasi'),
        'satuan'            => $this->input->post('satuan'),
        'tanggal_perolehan' => $this->input->post('tanggal'),
        'umur_ekonomis'     => $this->input->post('umur'),
    ];

    $this->db->where('id_barang', $id);
    $this->db->update('databarang', $data);

    redirect('barang');
}


    public function hapus($id)
    {
        $this->barang_model->delete($id);
        redirect('barang');
    }

    public function penghapusan()
{
    $data = [
        'id_barang' => $this->input->post('id_barang'),
        'jenis_penghapusan' => $this->input->post('jenis_penghapusan'),
        'keterangan' => $this->input->post('keterangan'),
        'nomor_bap' => $this->input->post('nomor_bap'),
        'tanggal_penghapusan' => $this->input->post('tanggal_penghapusan'),
    ];

    // simpan ke tabel penghapusan
    $this->db->insert('penghapusan_barang', $data);

    // update status barang
    $this->db->where('id_barang', $data['id_barang']);
    $this->db->update('databarang', ['status' => 'Dihapus']);

    redirect('barang');
}

public function filter()
{
    $data['barang'] = [];

    // Ambil data untuk dropdown
    $data['kode_list'] = $this->db->select('kode_barang')
                                   ->distinct()
                                   ->get('barang')
                                   ->result();

    if ($this->input->get()) {

        $kode_barang   = $this->input->get('kode_barang');
        $nama_barang   = $this->input->get('nama_barang');
        $kategori      = $this->input->get('kategori');
        $lokasi        = $this->input->get('lokasi');
        $kondisi       = $this->input->get('kondisi');
        $tanggal_awal  = $this->input->get('tanggal_awal');
        $tanggal_akhir = $this->input->get('tanggal_akhir');

        $this->db->from('barang');

        if ($kode_barang) {
            $this->db->where('kode_barang', $kode_barang);
        }

        if ($nama_barang) {
            $this->db->like('nama_barang', $nama_barang);
        }

        if ($kategori) {
            $this->db->like('kategori', $kategori);
        }

        if ($lokasi) {
            $this->db->like('lokasi', $lokasi);
        }

        if ($kondisi) {
            $this->db->where('kondisi', $kondisi);
        }

        if ($tanggal_awal && $tanggal_akhir) {
            $this->db->where('tanggal >=', $tanggal_awal);
            $this->db->where('tanggal <=', $tanggal_akhir);
        }

        $data['barang'] = $this->db->get()->result();
    }

    $this->load->view('filter/index', $data);
}
}
