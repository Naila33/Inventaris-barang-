<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kategori_model extends CI_Model {

    public function get_all()
    {
        return $this->db->get('kategori_barang')->result();
    }

    public function insert($data)
    {
        return $this->db->insert('kategori_barang',$data);
    }
}
