<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kondisi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->model('Databarang_model');
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
        $data['barang'] = $this->Databarang_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('kondisi/index', $data);
        $this->load->view('templates/footer');
    }

    public function update()
    {
        $this->form_validation->set_rules('id_barang', 'ID Barang', 'required|trim');
        $this->form_validation->set_rules('kondisi', 'Kondisi', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data update kondisi tidak valid.</div>');
            redirect('kondisi');
            return;
        }

        $id_barang = $this->input->post('id_barang', true);
        $kondisi   = $this->input->post('kondisi', true);

        $updated = $this->Databarang_model->update_kondisi($id_barang, $kondisi);
        if ($updated) {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Kondisi barang berhasil diperbarui.</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Kondisi barang gagal diperbarui.</div>');
        }

        redirect('kondisi');
    }

    public function hapus($id)
    {
        $deleted = $this->Databarang_model->delete_by_id($id);

        if ($deleted) {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data barang berhasil dihapus.</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data barang gagal dihapus.</div>');
        }

        redirect('kondisi');
    }

    public function edit($id_barang)
    {
        $data['title'] = 'Edit Kondisi Barang';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('user')->row_array();
        $data['barang'] = $this->Databarang_model->get_by_id($id_barang);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('kondisi/edit', $data);
        $this->load->view('templates/footer');
    }

    public function getdatakondisi()
    {
        try {
            $list = $this->Databarang_model->get_datatables_kondisi();
            $data = [];
            $no = isset($_POST['start']) ? (int)$_POST['start'] : 0;

            foreach ($list as $row) {
                $no++;
                $data[] = [
                    'no' => $no,
                    'nama_barang' => $row->nama_barang,
                    'kode_barang' => $row->kode_barang,
                    'kondisi' => $row->kondisi,
                    'aksi' => '<button type="button" class="btn btn-sm btn-primary btn-edit-kondisi" data-id_barang="' . $row->id_barang . '">Edit</button> '
                        . '<button type="button" class="btn btn-sm btn-danger btn-delete-kondisi" data-id_barang="' . $row->id_barang . '">Hapus</button>'
                ];
            }

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'draw' => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,
                    'recordsTotal' => $this->Databarang_model->count_all_kondisi(),
                    'recordsFiltered' => $this->Databarang_model->count_filtered_kondisi(),
                    'data' => $data
                ]));
            return;
        } catch (Exception $e) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'draw' => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => true,
                    'message' => $e->getMessage()
                ]));
            return;
        }
    }

    public function getkondisirow()
    {
        $id_barang = $this->input->post('id_barang', true);
        $row = $this->Databarang_model->get_by_id($id_barang);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($row));
    }

    public function getbarangoptions()
    {
        $options = $this->Databarang_model->get_barang_options();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($options));
    }

    public function updatekondisi_ajax()
    {
        $this->form_validation->set_rules('id_barang', 'ID Barang', 'required|trim');
        $this->form_validation->set_rules('kondisi', 'Kondisi', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'errors' => [
                        'id_barang' => form_error('id_barang'),
                        'kondisi' => form_error('kondisi')
                    ]
                ]));
            return;
        }

        $id_barang = $this->input->post('id_barang', true);
        $kondisi   = $this->input->post('kondisi', true);

        $updated = $this->Databarang_model->update_kondisi($id_barang, $kondisi);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => (bool)$updated
            ]));
    }

    public function deletekondisi_ajax()
    {
        $id_barang = $this->input->post('id_barang', true);
        if (!$id_barang) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'ID barang tidak valid'
                ]));
            return;
        }

        $deleted = $this->Databarang_model->delete_by_id($id_barang);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => (bool)$deleted
            ]));
    }
}
