<?php
class Filter_model extends CI_Model {

    public function getKategori()
    {
        return $this->db
            ->select('id_kategori, nama_kategori')
            ->from('kategoribarang')
            ->order_by('nama_kategori', 'asc')
            ->get()
            ->result();
    }

    public function getLokasi()
    {
        return $this->db
            ->select('id_lokasi, nama_lokasi')
            ->from('lokasi')
            ->order_by('nama_lokasi', 'asc')
            ->get()
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
        $this->db
            ->select('databarang.kode_barang, databarang.nama_barang, lokasi.nama_lokasi, databarang.kondisi, databarang.tanggal_perolehan, kategoribarang.nama_kategori')
            ->from('databarang')
            ->join('kategoribarang', 'kategoribarang.id_kategori = databarang.id_kategori', 'left');

        $this->db->join('lokasi', 'lokasi.id_lokasi = databarang.id_lokasi', 'left');

        if ($this->input->post('kode_barang')) {
            $this->db->where('kode_barang', $this->input->post('kode_barang'));
        }

        if ($this->input->post('nama_barang')) {
            $this->db->like('nama_barang', $this->input->post('nama_barang'));
        }

        if ($this->input->post('id_kategori')) {
            $this->db->where('databarang.id_kategori', (int)$this->input->post('id_kategori'));
        }

        if ($this->input->post('id_lokasi')) {
            $this->db->where('databarang.id_lokasi', (int)$this->input->post('id_lokasi'));
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