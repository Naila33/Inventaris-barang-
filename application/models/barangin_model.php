<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barangin_model extends CI_Model
{
    protected $table = 'barangin';
    protected $column_order = [null, 'tgl_masuk', 'sumberbarang', 'jumlah', 'dokumen_pendukung', null, null];
    protected $column_search = ['id_barangin', 'tgl_masuk', 'sumberbarang', 'jumlah', 'dokumen_pendukung'];

    protected $order = ['tgl_masuk' => 'asc'];

    private function _get_datatables_query()
    {
        $this->db->select('id_barangin, tgl_masuk, sumberbarang, jumlah, dokumen_pendukung');
        $this->db->from('barangin');

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
        try {
            $this->_get_datatables_query();
            if (isset($_POST['length']) && $_POST['length'] != -1) {
                $this->db->limit((int) $_POST['length'], (int) $_POST['start']);
            }
            return $this->db->get()->result();
        } catch (Exception $e) {
            log_message('error', 'Barang_model get_datatables: ' . $e->getMessage());
            return [];
        }
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