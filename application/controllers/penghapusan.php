<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Penghapusan extends CI_Controller {

        public function __construct()
{
    parent::__construct();

    if (!$this->session->userdata('email')) {
        redirect('auth');
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
    $this->load->view('penghapusan/penghapusan_list', $data);
    $this->load->view('templates/footer');
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
