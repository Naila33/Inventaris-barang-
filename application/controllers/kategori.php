<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kategori_model');
        $this->load->helper('pengelolaan');
        is_logged_in();
    }

    public function index()
    {
        if ($this->input->post()) {
            $this->Kategori_model->insert([
                'nama_kategori' => $this->input->post('nama')
            ]);
            redirect('kategori');
        }

        $data['kategori'] = $this->Kategori_model->get_all();
        $this->load->view('templates/header');
        $this->load->view('kategori/index', $data);
        $this->load->view('templates/footer');
    }
}
