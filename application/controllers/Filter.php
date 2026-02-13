<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filter extends CI_Controller {

    public function __construct()
{
    parent::__construct();

    if (!$this->session->userdata('email')) {
        if ($this->input->is_ajax_request()) {
            echo json_encode(["data" => []]);
            exit;
        } else {
            redirect('auth');
        }
    }

    $this->load->model('Filter_model');
}

    public function index()
    {
        $data['title'] = 'Filter Data Barang';

        $data['kategori'] = $this->Filter_model->getKategori();
        $data['lokasi']   = $this->Filter_model->getLokasi();
        $data['kode']     = $this->Filter_model->getKodeBarang();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('filter/index', $data);
        $this->load->view('templates/footer');
    }

    public function ajax_list()
{
    header('Content-Type: application/json');

    $list = $this->Filter_model->getFilteredData();

    $data = [];

    foreach ($list as $row) {
        $data[] = [
            $row->kode_barang,
            $row->nama_barang,
            $row->kategori,
            $row->lokasi,
            $row->kondisi,
            $row->tanggal_perolehan
        ];
    }

    echo json_encode([
        "data" => $data
    ]);
    exit; // WAJIB supaya tidak ada output lain
}
}