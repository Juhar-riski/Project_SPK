<?php
require_once('includes/init.php');

$user_role = get_role();
if($user_role == 'admin' || $user_role == 'user') {

$page = "Hasil";
require_once('template/header.php');
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-chart-area"></i> Data Hasil Akhir</h1>
	
	<a href="cetak.php" target="_blank" class="btn btn-primary"> <i class="fa fa-print"></i> Cetak Data </a>
</div>

<div class="row">
	<div class="col-6">
		<div class="card shadow mb-4">
			<!-- /.card-header -->
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Hasil Akhir Perankingan WP</h6>
			</div>

			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered" width="100%" cellspacing="0">
						<thead class="bg-primary text-white">
							<tr align="center">
								<th>Brand</th>
								<th>Tipe</th>
								<th>Total Nilai</th>
								<th width="15%">Rank</th>
						</thead>
						<tbody>
							<?php 
								$no=0;
								$prev_rank = 0;
								$prev_value = null;
								$query = mysqli_query($koneksi,"SELECT * FROM hasil_wp JOIN alternatif ON hasil_wp.id_alternatif=alternatif.id_alternatif ORDER BY hasil_wp.nilai DESC");
								while($data = mysqli_fetch_array($query)){
									$no++;
									if ($data['normalisasi'] === $prev_value) {
										$rank = $prev_rank;
									} else {
										$rank = $no;
										$prev_rank = $rank;
										$prev_value = $data['normalisasi'];
									}
							?>
							<tr align="center">
								<td align="left"><?= $data['brand'] ?></td>
								<td align="left"><?= $data['tipe'] ?></td>
								<td><?= number_format ($data['normalisasi'], 5) ?></td>
								<td><?= $rank ?></td>
							</tr>
							<?php
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-6">
	<div class="card shadow mb-4">
			<!-- /.card-header -->
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Hasil Akhir Perankingan PM</h6>
			</div>

			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered" width="100%" cellspacing="0">
						<thead class="bg-primary text-white">
							<tr align="center">
								<th>Brand</th>
								<th>Tipe</th>
								<th>Total Nilai</th>
								<th width="15%">Rank</th>
						</thead>
						<tbody>
							<?php 
								$no=0;
								$prev_rank = 0;
								$prev_value = null;
								$query = mysqli_query($koneksi, "SELECT * FROM hasil_pm JOIN alternatif ON hasil_pm.id_alternatif=alternatif.id_alternatif ORDER BY hasil_pm.nilai DESC");
								while($data = mysqli_fetch_array($query)){
									$no++;
									if ($data['nilai'] === $prev_value) {
										$rank = $prev_rank;
									} else {
										$rank = $no;
										$prev_rank = $rank;
										$prev_value = $data['nilai'];
									}
							?>
							<tr align="center">
								<td align="left"><?= $data['brand'] ?></td>
								<td align="left"><?= $data['tipe'] ?></td>
								<td><?= number_format($data['nilai'], 3) ?></td>
								<td><?= $rank ?></td>
							</tr>
							<?php
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
require_once('template/footer.php');
}
else {
	header('Location: login.php');
}
?>