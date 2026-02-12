<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mutasi_model extends CI_Model
{
    protected $table = 'mutasi_barang';
    protected $column_order = [null, 'id_barang', 'tanggal_mutasi', 'lokasi_asal', 'lokasi_tujuan', 'unit_asal', 'unit_tujuan', 'jumlah', 'penanggung_jawab', null];
    protected $column_search = ['id_mutasi', 'id_barang', 'tanggal_mutasi', 'lokasi_asal', 'lokasi_tujuan', 'unit_asal', 'unit_tujuan', 'jumlah', 'penanggung_jawab'];

    protected $order = ['tanggal_mutasi' => 'asc'];

    private function _get_datatables_query()
    {
        $this->db->select('id_mutasi, id_barang, tanggal_mutasi, lokasi_asal, lokasi_tujuan, unit_asal, unit_tujuan, jumlah, penanggung_jawab');
        $this->db->from($this->table);

        if (!empty($_POST['search']['value'])) {
            $this->db->group_start();
            foreach ($this->column_search as $item) {
                $this->db->or_like($item, $_POST['search']['value']);
            }
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $colIdx = (int) $_POST['order'][0]['column'];
            $dir = $_POST['order'][0]['dir'] === 'asc' ? 'asc' : 'desc';
            $column = $this->column_order[$colIdx] ?? key($this->order);
            if ($column) {
                $this->db->order_by($column, $dir);
            }
        } else {
            $this->db->order_by(key($this->order), $this->order[key($this->order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if (isset($_POST['length']) && $_POST['length'] != -1) {
            $this->db->limit((int) $_POST['length'], (int) $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->get()->num_rows();
    }

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }
}
