<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$errors = array();
$sukses = false;

$brand = (isset($_POST['brand'])) ? trim($_POST['brand']) : '';
$tipe = (isset($_POST['tipe'])) ? trim($_POST['tipe']) : '';
// $prodi = (isset($_POST['prodi'])) ? trim($_POST['prodi']) : '';

if(isset($_POST['submit'])):	
	
	if(!$brand) {
		$errors[] = 'Brand tidak boleh kosong';
	}

	if(!$tipe) {
		$errors[] = 'Tipe tidak boleh kosong';
	}

	// if(!$prodi) {
	// 	$errors[] = 'Prodi tidak boleh kosong';
	// }
	
	// Jika lolos validasi lakukan hal di bawah ini
	if(empty($errors)):
		$simpan = mysqli_query($koneksi,"INSERT INTO alternatif (id_alternatif, brand, tipe) VALUES ('', '$brand', '$tipe')");
		if($simpan) {
			redirect_to('list-alternatif.php?status=sukses-baru');
		}else{
			$errors[] = 'Data gagal disimpan';
		}
	endif;

endif;

$page = "Alternatif";
require_once('template/header.php');
?>


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users"></i> Data Alternatif</h1>

	<a href="list-alternatif.php" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
		<span class="text">Kembali</span>
	</a>
</div>
			
<?php if(!empty($errors)): ?>
	<div class="alert alert-info">
		<?php foreach($errors as $error): ?>
			<?php echo $error; ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>			
			
<form action="tambah-alternatif.php" method="post">
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-plus"></i> Tambah Data Alternatif</h6>
		</div>
		<div class="card-body">
			<div class="row">				
				<div class="form-group col-md-4">
					<label class="font-weight-bold">BRAND</label>
					<input autocomplete="off" type="text" name="brand" required value="<?php echo $brand; ?>" class="form-control"/>
				</div>

				<div class="form-group col-md-4">
					<label class="font-weight-bold">TIPE</label>
					<input autocomplete="off" type="text" name="tipe" required value="<?php echo $tipe; ?>" class="form-control"/>
				</div>

				<!-- <div class="form-group col-md-4">
					<label class="font-weight-bold">Program Studi</label>
					<select name="prodi" class="form-control" required>
						<option value="">--Pilih--</option>
						<option value="Teknik Informatika">Teknik Informatika</option>
						<option value="Teknik Elektro">Teknik Elektro</option>
						<option value="Teknik Kimia">Teknik Kimia</option>
						<option value="Teknik Informatika">Teknik Sipil</option>
						<option value="Teknik Elektro">Teknik Industri</option>
						<option value="Teknik Kimia">Teknik Logistik</option>
						<option value="Teknik Informatika">Sistem Informasi</option>
						<option value="Teknik Elektro">Teknik Material</option>
						<option value="Teknik Kimia">Teknik Arsitektur</option>
						<option value="Teknik Kimia">Teknik Mesin</option>
					</select>
				</div> -->
			</div>
		</div>
		<div class="card-footer text-right">
            <button name="submit" value="submit" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
            <button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
        </div>
	</div>
</form>

<?php
require_once('template/footer.php');
?>