<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Databarang_model extends CI_Model
{
    protected $table = 'databarang';
   protected $column_order = [null, 'kode_barang', 'nama_barang', 'nama_kategori', 'spesifikasi', 'satuan', 'harga_perolehan', 'tanggal_perolehan', 'umur_ekonomis', null];
protected $column_search = ['kode_barang', 'nama_barang', 'nama_kategori', 'spesifikasi', 'satuan', 'harga_perolehan', 'tanggal_perolehan', 'umur_ekonomis'];

    protected $order = ['nama_barang' => 'asc'];

    protected $column_order_kondisi = [null, 'nama_barang', 'kode_barang', 'kondisi', null];
    protected $column_search_kondisi = ['nama_barang', 'kode_barang', 'kondisi'];
    protected $order_kondisi = ['nama_barang' => 'asc'];

    public function get_all()
    {
        return $this->db->order_by('nama_barang', 'asc')->get($this->table)->result();
    }

    public function get_by_id($id_barang)
    {
        return $this->db->where('id_barang', $id_barang)->get($this->table)->row();
    }

    public function get_barang_options()
    {
        return $this->db
            ->select('id_barang, nama_barang, kode_barang')
            ->from($this->table)
            ->order_by('nama_barang', 'asc')
            ->get()
            ->result();
    }

    public function update_kondisi($id_barang, $kondisi)
    {
        return $this->db->where('id_barang', $id_barang)->update($this->table, [
            'kondisi' => $kondisi
        ]);
    }

    public function delete_by_id($id_barang)
    {
        return $this->db->where('id_barang', $id_barang)->delete($this->table);
    }

    public function get_kondisi_options()
    {
        $this->db->distinct();
        $this->db->select('kondisi');
        $this->db->from($this->table);
        $this->db->where('kondisi IS NOT NULL', null, false);
        $this->db->where('kondisi !=', '');
        $this->db->order_by('kondisi', 'asc');
        $rows = $this->db->get()->result();

        $options = [];
        foreach ($rows as $r) {
            $options[] = $r->kondisi;
        }
        return $options;
    }

    private function _get_datatables_query()
    {
        $this->db
            ->select('databarang.id_barang, databarang.kode_barang, databarang.nama_barang, databarang.spesifikasi, databarang.satuan, databarang.harga_perolehan, databarang.tanggal_perolehan, databarang.umur_ekonomis, kategoribarang.nama_kategori, lokasi.nama_lokasi')
            ->from($this->table)
            ->join('kategoribarang', 'kategoribarang.id_kategori = databarang.id_kategori', 'left')
            ->join('lokasi', 'lokasi.id_lokasi = databarang.id_lokasi', 'left');

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

    private function _get_datatables_query_kondisi()
    {
        $this->db->select('id_barang, nama_barang, kode_barang, kondisi');
        $this->db->from($this->table);

        if (isset($_POST['search']['value']) && $_POST['search']['value'] !== '') {
            $this->db->group_start();
            foreach ($this->column_search_kondisi as $item) {
                $this->db->or_like($item, $_POST['search']['value']);
            }
            $this->db->group_end();
        }

        if (isset($_POST['order'][0]['column'])) {
            $colIdx = (int) $_POST['order'][0]['column'];
            $dir = (isset($_POST['order'][0]['dir']) && $_POST['order'][0]['dir'] === 'asc') ? 'asc' : 'desc';
            $column = $this->column_order_kondisi[$colIdx] ?? key($this->order_kondisi);
            if ($column) {
                $this->db->order_by($column, $dir);
            }
        } else {
            $this->db->order_by(key($this->order_kondisi), $this->order_kondisi[key($this->order_kondisi)]);
        }
    }

    public function get_datatables_kondisi()
    {
        $this->_get_datatables_query_kondisi();
        if (isset($_POST['length']) && $_POST['length'] != -1) {
            $this->db->limit((int) $_POST['length'], (int) $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function count_filtered_kondisi()
    {
        $this->_get_datatables_query_kondisi();
        return $this->db->get()->num_rows();
    }

    public function count_all_kondisi()
    {
        return $this->db->count_all($this->table);
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
