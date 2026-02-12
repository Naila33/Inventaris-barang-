<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_DB $db
 * @property Peminjaman_model $Peminjaman_model
 */

class Peminjaman extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('Peminjaman_model');
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
    }

    public function index()
    {
        $data['title'] = 'Peminjaman Barang';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('user')->row_array();

        $data['peminjaman'] = $this->Peminjaman_model->get_all();
        $data['barang'] = $this->db->get('databarang')->result_array();
        $data['users'] = $this->db->where('is_active', 1)->get('user')->result_array();
        $data['open_modal'] = false;

        $this->form_validation->set_rules('id_barang', 'Barang', 'required|trim');
        $this->form_validation->set_rules('id_peminjam', 'Peminjam', 'required|trim');
        $this->form_validation->set_rules('tanggal_pinjam', 'Tanggal Pinjam', 'required|trim');
        $this->form_validation->set_rules('batas_waktu', 'Batas Waktu', 'required|trim');

        if ($this->form_validation->run() == false) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data['open_modal'] = true;
            }
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('peminjaman/index', $data);
            $this->load->view('templates/footer');
        } else {
            $id_barang = $this->input->post('id_barang');
            
            if ($this->Peminjaman_model->is_item_borrowed($id_barang)) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Barang sedang dipinjam!</div>');
                redirect('peminjaman');
            }

            $data = [
                'id_barang' => $id_barang,
                'id_peminjam' => NULL, // NULL karena pakai text
                'nama_peminjam_text' => $this->input->post('id_peminjam'), // Simpan nama di text field
                'tanggal_pinjam' => $this->input->post('tanggal_pinjam'),
                'batas_waktu' => $this->input->post('batas_waktu'),
                'status' => 'Dipinjam',
                'keterangan' => $this->input->post('keterangan')
            ];

            $this->Peminjaman_model->insert($data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Peminjaman berhasil dicatat!</div>');
            redirect('peminjaman');
        }
    }

    public function getpeminjamanrow()
    {
        $id = $this->input->post('id_peminjaman');
        error_log('getpeminjamanrow called with ID: ' . $id); // Debug
        
        $row = $this->Peminjaman_model->get_by_id($id);
        error_log('Row data: ' . print_r($row, true)); // Debug
        
        header('Content-Type: application/json');
        echo json_encode($row ?: []);
    }

    public function updatepeminjaman()
    {
        $this->form_validation->set_rules('id_peminjaman', 'ID', 'required|integer');
        $this->form_validation->set_rules('id_barang', 'Barang', 'required|trim');
        $this->form_validation->set_rules('id_peminjam', 'Peminjam', 'required|trim');
        $this->form_validation->set_rules('tanggal_pinjam', 'Tanggal Pinjam', 'required|trim');
        $this->form_validation->set_rules('batas_waktu', 'Batas Waktu', 'required|trim');

        if ($this->form_validation->run() == false) {
            echo json_encode([
                'status' => false,
                'errors' => [
                    'id_barang' => form_error('id_barang'),
                    'id_peminjam' => form_error('id_peminjam'),
                    'tanggal_pinjam' => form_error('tanggal_pinjam'),
                    'batas_waktu' => form_error('batas_waktu'),
                    'keterangan' => form_error('keterangan')
                ]
            ]);
            return;
        }

        $id = (int)$this->input->post('id_peminjaman');
        $data = [
            'id_barang' => $this->input->post('id_barang'),
            'id_peminjam' => NULL, // NULL karena pakai text
            'nama_peminjam_text' => $this->input->post('id_peminjam'), // Simpan nama di text field
            'tanggal_pinjam' => $this->input->post('tanggal_pinjam'),
            'batas_waktu' => $this->input->post('batas_waktu'),
            'keterangan' => $this->input->post('keterangan')
        ];

        $this->Peminjaman_model->update($id, $data);
        echo json_encode(['status' => true, 'message' => 'Data peminjaman berhasil diupdate']);
    }

    public function delete()
    {
        $id = $this->input->post('id_peminjaman');
        
        $this->Peminjaman_model->delete($id);
        
        echo json_encode(['status' => true, 'message' => 'Data peminjaman berhasil dihapus']);
    }

    public function hapus($id)
    {
        $this->Peminjaman_model->delete($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data peminjaman berhasil dihapus!</div>');
        redirect('peminjaman');
    }

    public function deletepeminjaman()
    {
        $id = (int)$this->input->post('id_peminjaman');
        if ($id) {
            $this->Peminjaman_model->delete($id);
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false, 'message' => 'ID tidak valid']);
        }
    }

    public function kembalikan($id)
    {
        $peminjaman = $this->Peminjaman_model->get_by_id($id);
        
        if (!$peminjaman || $peminjaman->status == 'Dikembalikan') {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data peminjaman tidak ditemukan atau sudah dikembalikan!</div>');
            redirect('peminjaman');
        }

        $data = [
            'tanggal_kembali' => date('Y-m-d'),
            'status' => 'Dikembalikan'
        ];

        $this->Peminjaman_model->update($id, $data);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Barang berhasil dikembalikan!</div>');
        redirect('peminjaman');
    }

   public function aktif()
{
    $data['title'] = 'Peminjaman Aktif';
    $data['user'] = $this->db->where(
        'email',
        $this->session->userdata('email')
    )->get('user')->row_array();

    $this->Peminjaman_model->update_overdue_status();
    $data['peminjaman'] = $this->Peminjaman_model->get_all(); // PAKAI get_all() TANPA FILTER
    $data['barang'] = $this->db->get('databarang')->result_array();
    $data['users'] = $this->db->where('is_active', 1)->get('user')->result_array();

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $data);
    $this->load->view('templates/topbar', $data);
    $this->load->view('peminjaman/aktif', $data);
    $this->load->view('templates/footer');
}

    public function terlambat()
    {
        $data['title'] = 'Peminjaman Terlambat';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('user')->row_array();

        $data['peminjaman'] = $this->Peminjaman_model->get_all(); // PAKAI get_all() TANPA FILTER
        $data['barang'] = $this->db->get('databarang')->result_array();
        $data['users'] = $this->db->where('is_active', 1)->get('user')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('peminjaman/terlambat', $data);
        $this->load->view('templates/footer');
    }

    public function getpeminjaman()
    {
        error_log('getpeminjaman called'); // Debug
        
        $list = $this->Peminjaman_model->get_datatables();
        error_log('Data count: ' . count($list)); // Debug
        
        $data = [];
        $no = isset($_POST['start']) ? (int)$_POST['start'] : 0;

        foreach ($list as $row) {
            $no++;
            $status_badge = '';
            if ($row->status == 'Dipinjam') {
                $status_badge = '<span class="badge badge-primary">Dipinjam</span>';
            } elseif ($row->status == 'Dikembalikan') {
                $status_badge = '<span class="badge badge-success">Dikembalikan</span>';
            } elseif ($row->status == 'Terlambat') {
                $status_badge = '<span class="badge badge-danger">Terlambat</span>';
            }

            $aksi = '';
            $aksi .= '<a href="' . base_url('peminjaman/edit/' . $row->id_peminjaman) . '" 
                       class="btn btn-warning btn-sm mr-1" 
                       data-id="' . $row->id_peminjaman . '">Edit</a> '
                   . '<a href="' . base_url('peminjaman/hapus/' . $row->id_peminjaman) . '" 
                      class="btn btn-danger btn-sm"
                      onclick="return confirm(\'Yakin mau hapus data ini?\')"
                      data-id="' . $row->id_peminjaman . '">Hapus</a>';

            $data[] = [
                'no' => $no,
                'tanggal_pinjam' => date('d F Y', strtotime($row->tanggal_pinjam)),
                'kode_barang' => $row->kode_barang,
                'nama_barang' => $row->nama_barang,
                'nama_peminjam' => $row->nama_peminjam,
                'batas_waktu' => date('d F Y', strtotime($row->batas_waktu)),
                'tanggal_kembali' => $row->tanggal_kembali ? date('d F Y', strtotime($row->tanggal_kembali)) : '-',
                'status' => $status_badge,
                'aksi' => $aksi
            ];
        }

        $result = [
            "draw" => isset($_POST['draw']) ? (int)$_POST['draw'] : 0,  
            "recordsTotal" => $this->Peminjaman_model->count_all(),
            "recordsFiltered" => $this->Peminjaman_model->count_filtered(),
            "data" => $data
        ];
        
        error_log('JSON result: ' . json_encode($result)); // Debug
        echo json_encode($result);
    }
}
