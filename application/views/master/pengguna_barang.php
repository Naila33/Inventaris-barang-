<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

<!-- Prodi: Alerts & Actions -->
<?= $this->session->flashdata('message'); ?>



<!-- Prodi: Table List -->
<div class="table-responsive">
  <table id="datatable-pengguna" class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nama Pengguna</th>
        <th scope="col">Jenis Pengguna</th>
        <th scope="col">No Identitas</th>
        <th scope="col">Divisi</th>
        <th scope="col">Unit</th>
        <th scope="col">Nomor Telepon</th>
        <th scope="col">Status</th>
        <th scope="col">Keterangan</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
  </div>

