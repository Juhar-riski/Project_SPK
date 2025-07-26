<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$errors = array();
$sukses = false;

$ada_error = false;
$result = '';

$id_alternatif = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if(isset($_POST['submit'])):	
	
	$brand = $_POST['brand'];
	$tipe = $_POST['tipe'];
	
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
		
		$update = mysqli_query($koneksi,"UPDATE alternatif SET brand = '$brand', tipe = '$tipe' WHERE id_alternatif = '$id_alternatif'");
		if($update) {
			redirect_to('list-alternatif.php?status=sukses-edit');
		}else{
			$errors[] = 'Data gagal diupdate';
		}
	endif;

endif;
?>

<?php
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

<?php if($sukses): ?>
	<div class="alert alert-success">
		Data berhasil disimpan
	</div>	
<?php elseif($ada_error): ?>
	<div class="alert alert-info">
		<?php echo $ada_error; ?>
	</div>
<?php else: ?>		
			
<form action="edit-alternatif.php?id=<?php echo $id_alternatif; ?>" method="post">
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-edit"></i> Edit Data Alternatif</h6>
		</div>
		<?php
		if(!$id_alternatif) {
		?>
		<div class="card-body">
			<div class="alert alert-danger">Data tidak ada</div>
		</div>
		<?php
		}else{
		$data = mysqli_query($koneksi,"SELECT * FROM alternatif WHERE id_alternatif='$id_alternatif'");
		$cek = mysqli_num_rows($data);
		if($cek <= 0) {
		?>
		<div class="card-body">
			<div class="alert alert-danger">Data tidak ada</div>
		</div>
		<?php
		}else{
			while($d = mysqli_fetch_array($data)){
		?>
		<div class="card-body">
			<div class="row">
				<div class="form-group col-md-4">
					<label class="font-weight-bold">BRAND</label>
					<input autocomplete="off" type="text" name="brand" required value="<?php echo $d['brand']; ?>" class="form-control"/>
				</div>

				<div class="form-group col-md-4">
					<label class="font-weight-bold">TIPE</label>
					<input autocomplete="off" type="text" name="tipe" required value="<?php echo $d['tipe']; ?>" class="form-control"/>
				</div>

				<!-- <div class="form-group col-md-4">
					<label class="font-weight-bold">Program Studi</label>
					<select name="prodi" class="form-control" required>
						<option value="">--Pilih--</option>
						<option value="Teknik Informatika" <?php if($d['prodi'] == "Teknik Informatika"){echo "selected";}?>>Teknik Informatika</option>
						<option value="Teknik Elektro" <?php if($d['prodi'] == "Teknik Elektro"){echo "selected";}?>>Teknik Elektro</option>
						<option value="Teknik Kimia" <?php if($d['prodi'] == "Teknik Kimia"){echo "selected";}?>>Teknik Kimia</option>
						<option value="Teknik Sipil" <?php if($d['prodi'] == "Teknik Sipil"){echo "selected";}?>>Teknik Sipil</option>
						<option value="Teknik Industri" <?php if($d['prodi'] == "Teknik Industri"){echo "selected";}?>>Teknik Industri</option>
						<option value="Teknik Logistik" <?php if($d['prodi'] == "Teknik Logistik"){echo "selected";}?>>Teknik Logistik</option>
						<option value="Sistem Informasi" <?php if($d['prodi'] == "Sistem Informasi"){echo "selected";}?>>Sistem Informasi</option>
						<option value="Teknik Material" <?php if($d['prodi'] == "Teknik Material"){echo "selected";}?>>Teknik Material</option>
						<option value="Teknik Arsitektur" <?php if($d['prodi'] == "Teknik Arsitektur"){echo "selected";}?>>Teknik Arsitektur</option>
						<option value="Teknik Mesin" <?php if($d['prodi'] == "Teknik Mesin"){echo "selected";}?>>Teknik Mesin</option>
					</select>
				</div> -->
			</div>
		</div>
	
		<div class="card-footer text-right">
            <button name="submit" value="submit" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
            <button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
        </div>
		<?php
		}
		}
		}
		?>
	</div>
</form>

<?php
endif;
require_once('template/footer.php');
?>