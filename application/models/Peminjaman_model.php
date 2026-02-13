<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Peminjaman_model extends CI_Model
{
    private $table = 'peminjaman';
    
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        $this->db->select('
            p.id_peminjaman,
            p.tanggal_pinjam,
            p.batas_waktu,
            p.tanggal_kembali,
            p.status,
            p.keterangan,
            b.kode_barang,
            b.nama_barang,
            COALESCE(p.nama_peminjam_text, u.name) AS nama_peminjam,
            p.id_barang,
            p.id_peminjam
        ');
        $this->db->from('peminjaman p');
        $this->db->join('databarang b', 'b.id_barang = p.id_barang');
        $this->db->join('user u', 'u.id = p.id_peminjam', 'left');
        $this->db->order_by('p.tanggal_pinjam', 'DESC');
        
        return $this->db->get()->result();
    }

    public function get_by_id($id)
{
    $this->db->select('
        p.id_peminjaman,
        p.tanggal_pinjam,
        p.batas_waktu,
        p.tanggal_kembali,
        p.status,
        p.keterangan,
        b.kode_barang,
        b.nama_barang,
        COALESCE(p.nama_peminjam_text, u.name) AS nama_peminjam,
        p.id_barang,
        p.id_peminjam
    ');
    $this->db->from('peminjaman p');
    $this->db->join('databarang b', 'b.id_barang = p.id_barang');
    $this->db->join('user u', 'u.id = p.id_peminjam', 'left');
    $this->db->where('p.id_peminjaman', $id);
    
    return $this->db->get()->row();
}

    public function get_active_borrowings()
{
    $this->db->select('
        p.id_peminjaman,
        p.tanggal_pinjam,
        p.batas_waktu,
        p.tanggal_kembali,
        p.status,
        p.keterangan,
        b.kode_barang,
        b.nama_barang,
        COALESCE(p.nama_peminjam_text, u.name) AS nama_peminjam,
        p.id_barang,
        p.id_peminjam
    ');
    $this->db->from('peminjaman p');
    $this->db->join('databarang b', 'b.id_barang = p.id_barang');
    $this->db->join('user u', 'u.id = p.id_peminjam', 'left');
    // TANPA FILTER - SEMUA DATA
    $this->db->order_by('p.tanggal_pinjam', 'DESC');
    
    return $this->db->get()->result();
}

    public function get_overdue_borrowings()
{
    $this->db->select('
        p.id_peminjaman,
        p.tanggal_pinjam,
        p.batas_waktu,
        p.tanggal_kembali,
        p.status,
        p.keterangan,
        b.kode_barang,
        b.nama_barang,
        COALESCE(p.nama_peminjam_text, u.name) AS nama_peminjam,
        p.id_barang,
        p.id_peminjam
    ');
    $this->db->from('peminjaman p');
    $this->db->join('databarang b', 'b.id_barang = p.id_barang');
    $this->db->join('user u', 'u.id = p.id_peminjam', 'left');
    $this->db->order_by('p.tanggal_pinjam', 'DESC');
    
    return $this->db->get()->result();
}

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id_peminjaman', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id_peminjaman', $id);
        return $this->db->delete($this->table);
    }

    public function is_item_borrowed($id_barang)
    {
        $this->db->where('id_barang', $id_barang);
        $this->db->where('status', 'Dipinjam');
        return $this->db->get($this->table)->num_rows() > 0;
    }

    public function update_overdue_status()
    {
        $today = date('Y-m-d');
        
        // Debug: Lihat data yang akan diupdate
        $this->db->where('status', 'Dipinjam');
        $this->db->where('batas_waktu <', $today);
        $query = $this->db->get($this->table);
        error_log('Overdue records found: ' . $query->num_rows());
        error_log('SQL check: ' . $this->db->last_query());
        
        // Update status
        $this->db->where('status', 'Dipinjam');
        $this->db->where('batas_waktu <', $today);
        $this->db->update($this->table, ['status' => 'Terlambat']);
        
        error_log('Update SQL: ' . $this->db->last_query());
        error_log('Affected rows: ' . $this->db->affected_rows());
    }

    public function get_borrowing_history($id_barang)
    {
        $this->db->select('p.*, u.name as nama_peminjam');
        $this->db->from($this->table . ' p');
        $this->db->join('user u', 'p.id_peminjam = u.id');
        $this->db->where('p.id_barang', $id_barang);
        $this->db->order_by('p.tanggal_pinjam', 'DESC');
        return $this->db->get()->result();
    }

    public function count_by_status($status)
    {
        $this->db->where('status', $status);
        return $this->db->count_all_results($this->table);
    }

    public function get_datatables()
{
    error_log('get_datatables called'); // Debug
    
    $this->db->select('
        p.id_peminjaman,
        p.tanggal_pinjam,
        p.batas_waktu,
        p.tanggal_kembali,
        p.status,
        p.keterangan,
        b.kode_barang,
        b.nama_barang,
        COALESCE(p.nama_peminjam_text, u.name) AS nama_peminjam,
        p.id_barang,
        p.id_peminjam
    ');
    $this->db->from('peminjaman p');
    $this->db->join('databarang b', 'b.id_barang = p.id_barang');
    $this->db->join('user u', 'u.id = p.id_peminjam', 'left');

    if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search = $_POST['search']['value'];
        $this->db->like('b.nama_barang', $search);
        $this->db->or_like('b.kode_barang', $search);
        $this->db->or_like('p.nama_peminjam_text', $search);
        $this->db->or_like('u.name', $search);
        $this->db->or_like('p.status', $search);
    }

    if (isset($_POST['order'])) {
        $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else {
        $this->db->order_by('p.tanggal_pinjam', 'DESC');
    }

    $result = $this->db->get()->result();
    error_log('SQL: ' . $this->db->last_query()); // Debug
    error_log('Result count: ' . count($result)); // Debug
    
    return $result;
}

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function count_filtered()
{
    $this->db->select('
        p.id_peminjaman,
        p.tanggal_pinjam,
        p.batas_waktu,
        p.tanggal_kembali,
        p.status,
        p.keterangan,
        b.kode_barang,
        b.nama_barang,
        u.name AS nama_peminjam,
        p.id_barang,
        p.id_peminjam
    ');
    $this->db->from('peminjaman p');
    $this->db->join('databarang b', 'b.id_barang = p.id_barang');
    $this->db->join('user u', 'u.id = p.id_peminjam');

    if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
        $search = $_POST['search']['value'];
        $this->db->like('b.nama_barang', $search);
        $this->db->or_like('b.kode_barang', $search);
        $this->db->or_like('u.name', $search);
        $this->db->or_like('p.status', $search);
    }

    return $this->db->count_all_results();
}

    private $order_column = ['p.tanggal_pinjam', 'b.kode_barang', 'b.nama_barang', 'u.name', 'p.batas_waktu', 'p.status'];
}
