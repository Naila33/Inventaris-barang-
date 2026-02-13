<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang_model extends CI_Model {

    public function get_all()
{
    return $this->db
        ->where('status', 'Aktif')
        ->get('databarang')
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
