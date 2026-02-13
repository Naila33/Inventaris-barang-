<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penghapusan_model extends CI_Model
{
    private $table = 'penghapusan_barang';

    /* =======================
       GET DATA
    ======================== */

    // Ambil semua data penghapusan + data barang
    public function getAll()
    {
        $this->db->select('
            penghapusan_barang.*,
            databarang.kode_barang,
            databarang.nama_barang,
            databarang.kategori
        ');
        $this->db->from($this->table);
        $this->db->join('databarang', 'databarang.id_barang = penghapusan_barang.id_barang', 'left');
        $this->db->order_by('penghapusan_barang.tanggal_penghapusan', 'DESC');
        return $this->db->get()->result();
    }

    // Ambil 1 data penghapusan berdasarkan ID
    public function getById($id)
    {
        return $this->db->get_where($this->table, [
            'id_penghapusan_barang' => $id
        ])->row();
    }

    /* =======================
       INSERT
    ======================== */

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /* =======================
       DELETE
    ======================== */

    public function delete($id)
    {
        return $this->db->delete($this->table, [
            'id_penghapusan_barang' => $id
        ]);
    }

    /* =======================
       OPTIONAL
    ======================== */

    // Cek apakah barang sudah dihapus
    public function isBarangSudahDihapus($id_barang)
    {
        return $this->db->get_where($this->table, [
            'id_barang' => $id_barang
        ])->num_rows();
    }

    public function getByIdWithBarang($id)
{
    return $this->db
        ->select('penghapusan_barang.*, databarang.nama_barang, databarang.kode_barang')
        ->from('penghapusan_barang')
        ->join('databarang', 'databarang.id_barang = penghapusan_barang.id_barang')
        ->where('penghapusan_barang.id_penghapusan', $id) // SESUAIKAN NAMA ID
        ->get()
        ->row();
}
}
