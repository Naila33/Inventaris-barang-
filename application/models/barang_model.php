<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang_model extends CI_Model {

    public function get_all()
{
    $table = 'databarang';

    if ($this->db->field_exists('kategori', $table)) {
        $this->db->select('databarang.*');
    } elseif ($this->db->field_exists('id_kategori', $table) && $this->db->table_exists('kategoribarang')) {
        $this->db
            ->select('databarang.*, kategoribarang.nama_kategori as kategori')
            ->join('kategoribarang', 'kategoribarang.id_kategori = databarang.id_kategori', 'left');
    } else {
        $this->db->select('databarang.*');
    }

    if ($this->db->field_exists('status', $table)) {
        $this->db->where('status', 'Aktif');
    }

    return $this->db
        ->from($table)
        ->get()
        ->result();
}

    public function insert($data)
    {
        return $this->db->insert('databarang', $data);
    }

    public function delete($id)
    {
        return $this->db->delete('databarang', ['id_barang' => $id]);
    }

    // 🔢 KODE BARANG OTOMATIS
    public function kode_barang()
    {
        $year = date('Y');
        $last = $this->db->order_by('id_barang','DESC')->get('databarang')->row();

        $urut = $last ? intval(substr($last->kode_barang, -4)) + 1 : 1;
        return "AST-$year-" . str_pad($urut, 4, '0', STR_PAD_LEFT);
    }
}
