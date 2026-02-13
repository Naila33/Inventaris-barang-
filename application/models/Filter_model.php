<?php
class Filter_model extends CI_Model {

    public function getKategori()
    {
        return $this->db->select('kategori')
                        ->distinct()
                        ->get('databarang')
                        ->result();
    }

    public function getLokasi()
    {
        return $this->db->select('lokasi')
                        ->distinct()
                        ->get('databarang')
                        ->result();
    }

    public function getKodeBarang()
    {
        return $this->db->select('kode_barang')
                        ->distinct()
                        ->get('databarang')
                        ->result();
    }

    public function getFilteredData()
    {
        $this->db->from('databarang');

        if ($this->input->post('kode_barang')) {
            $this->db->where('kode_barang', $this->input->post('kode_barang'));
        }

        if ($this->input->post('nama_barang')) {
            $this->db->like('nama_barang', $this->input->post('nama_barang'));
        }

        if ($this->input->post('kategori')) {
            $this->db->where('kategori', $this->input->post('kategori'));
        }

        if ($this->input->post('lokasi')) {
            $this->db->where('lokasi', $this->input->post('lokasi'));
        }

        if ($this->input->post('kondisi')) {
            $this->db->where('kondisi', $this->input->post('kondisi'));
        }

        if ($this->input->post('tanggal_awal') && $this->input->post('tanggal_akhir')) {
            $this->db->where('tanggal_perolehan >=', $this->input->post('tanggal_awal'));
            $this->db->where('tanggal_perolehan <=', $this->input->post('tanggal_akhir'));
        }

        return $this->db->get()->result();
    }
}