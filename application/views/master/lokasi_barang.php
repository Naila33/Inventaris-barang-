<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<!-- Prodi: Alerts & Actions -->
<?= $this->session->flashdata('message'); ?>



<!-- Prodi: Table List -->
<div class="table-responsive">
  <table id="datatable-lokasi" class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Kode Lokasi</th>
        <th scope="col">Nama Lokasi</th>
        <th scope="col">Gedung</th>
        <th scope="col">Lantai</th>
        <th scope="col">Keterangan</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
  </div>

