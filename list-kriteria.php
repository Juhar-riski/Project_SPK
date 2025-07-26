<?php
require_once('includes/init.php');
cek_login($role = array(1));
$page = "Kriteria";
require_once('template/header.php');
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-cube"></i> Data Kriteria</h1>

	<div>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-check"></i> Bobot CF & SF</button>
		<a href="tambah-kriteria.php" class="btn btn-success"> <i class="fa fa-plus"></i> Tambah Data </a>
	</div>
</div>

<?php
$result = mysqli_query($koneksi,"SELECT * FROM cf_sf WHERE id_cf_sf = '1';");
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $d_cf = $row['bobot_cf'];
    $d_sf = $row['bobot_sf'];
} else {
    $d_cf = 0;
    $d_sf = 0;
}

if(isset($_POST['update'])){
	$cf = $_POST['cf'];
	$sf = $_POST['sf'];
	
	if(!$cf) {
		$errors[] = 'CF tidak boleh kosong';
	}
	
	if(!$sf) {
		$errors[] = 'SF tidak boleh kosong';
	}
	
	if(empty($errors)){
		
		$update = mysqli_query($koneksi,"UPDATE cf_sf SET bobot_cf = '$cf', bobot_sf = '$sf' WHERE id_cf_sf = '1';");
		
		if($update) {
			$sts[] = 'Data berhasil diupdate';
		}else{
			$errors[] = 'Data gagal diupdate';
		}
	}
}
?>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Bobot CF & SF</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	  <form action="list-kriteria.php" method="POST">
		<div class="modal-body">
			<div class="form-group">
				<label class="font-weight-bold">Bobot Core Factor (%)</label>
				<input autocomplete="off" type="number" name="cf" value="<?= $d_cf ?>" required step="0.01" class="form-control"/>
			</div>

			<div class="form-group">
				<label class="font-weight-bold">Bobot Secondary Factor (%)</label>
				<input autocomplete="off" type="number" name="sf" value="<?= $d_sf ?>" required step="0.01" class="form-control"/>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
			<button type="submit" name="update" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
		</div>
	  </form>
    </div>
  </div>
</div>
<?php
$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = '';
switch($status):
	case 'sukses-baru':
		$msg = 'Data berhasil disimpan';
		break;
	case 'sukses-hapus':
		$msg = 'Data behasil dihapus';
		break;
	case 'sukses-edit':
		$msg = 'Data behasil diupdate';
		break;
endswitch;

if($msg):
	echo '<div class="alert alert-info">'.$msg.'</div>';
endif;
?>

<?php if(!empty($errors)): ?>
	<div class="alert alert-danger">
		<?php foreach($errors as $error): ?>
			<?php echo $error; ?>
		<?php endforeach; ?>
	</div>	
<?php endif; ?>

<?php if(!empty($sts)): ?>
	<div class="alert alert-info">
		<?php foreach($sts as $st): ?>
			<?php echo $st; ?>
		<?php endforeach; ?>
	</div>
<?php
endif;
?>

<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Daftar Data Kriteria</h6>
    </div>

    <div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead class="bg-primary text-white">
					<tr align="center">
						<th>No</th>
						<th>Kode Kriteria</th>
						<th>Nama Kriteria</th>
						<th>Atribut</th>
						<th>Jenis (PM)</th>
						<th>Bobot (WP)</th>
						<th>Bobot Standar (PM)</th>
						<th>Cara Penilaian</th>
						<th width="15%">Aksi</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$no = 1;
				$query = mysqli_query($koneksi,"SELECT * FROM kriteria ORDER BY kode_kriteria ASC");			
				while($data = mysqli_fetch_array($query)):
				?>
					<tr align="center">
						<td><?php echo $no; ?></td>
						<td><?php echo $data['kode_kriteria']; ?></td>
						<td align="left"><?php echo $data['nama']; ?></td>
						<td><?php echo $data['type']; ?></td>
						<td><?php echo $data['jenis']; ?></td>
						<td><?php echo $data['bobot']; ?></td>
						<td><?php echo $data['bobot_standar']; ?></td>
						<td><?php echo ($data['ada_pilihan']) ? 'Pilihan Sub Kriteria': 'Input Langsung'; ?></td>							
						<td>
							<div class="btn-group" role="group">
								<a data-toggle="tooltip" data-placement="bottom" title="Edit Data" href="edit-kriteria.php?id=<?php echo $data['id_kriteria']; ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
								<a  data-toggle="tooltip" data-placement="bottom" title="Hapus Data" href="hapus-kriteria.php?id=<?php echo $data['id_kriteria']; ?>" onclick="return confirm ('Apakah anda yakin untuk meghapus data ini')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
							</div>
						</td>
					</tr>
					<?php 
					$no++;
					endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php
require_once('template/footer.php');
?>