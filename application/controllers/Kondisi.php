<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kondisi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
    }

    public function index()
    {
        $data['title'] = 'Kondisi Barang';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('user')->row_array();
        $data['barang'] = $this->db->get('databarang')->result();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('kondisi/index', $data);
        $this->load->view('templates/footer');
    }

    public function update()
    {
        $id_barang = $this->input->post('id_barang');
        $kondisi   = $this->input->post('kondisi');

        $this->db->where('id_barang', $id_barang);
        $this->db->update('databarang', [
            'kondisi' => $kondisi
        ]);

        $this->session->set_flashdata('success', 'Kondisi barang berhasil diperbarui');
        redirect('kondisi');
    }

    public function hapus($id)
    {
        $this->db->where('id_barang', $id);
        $this->db->delete('databarang');

        redirect('kondisi');
    }

    public function edit($id_barang)
    {
        $data['title'] = 'Edit Kondisi Barang';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('user')->row_array();
        $data['barang'] = $this->db->where('id_barang', $id_barang)->get('databarang')->row();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('kondisi/edit', $data);
        $this->load->view('templates/footer');
    }
}
