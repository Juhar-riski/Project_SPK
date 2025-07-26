<?php
require_once('includes/init.php');

$user_role = get_role();
if($user_role == 'admin') {
	$page = "Perhitungan";
require_once('template/header.php');
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-calculator"></i> Data Perhitungan 
	<?php if (isset($_POST['hitung'])) {if($_POST['metode'] == "wp") {echo "WP";}}?>
	<?php if (isset($_POST['hitung'])) {if($_POST['metode'] == "pm") {echo "PM";}}?>
	</h1>
</div>

<div class="card shadow mb-4">
	<!-- /.card-header -->
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Hitung Berdasarkan Metode</h6>
	</div>

	<div class="card-body">
		<form action="" method="POST">
			<div class="row">
				<div class="input-group mb-3 col-10">
					<div class="input-group-prepend-responsive">
						<label class="input-group-text" for="inputGroupSelect01">Pilih Metode</label>
					</div>
					<select name="metode" class="custom-select" required>
						<option value="">--Pilih Metode Perhitungan--</option>
						<option value="wp" <?php if (isset($_POST['hitung'])) {if($_POST['metode'] == "wp") {echo "selected";}}?>>Perhitungan Metode WP</option>
						<option value="pm" <?php if (isset($_POST['hitung'])) {if($_POST['metode'] == "pm") {echo "selected";}}?>>Perhitungan Metode PM</option>
					</select>
				</div>

				<div class="col-2-responsive">
					<button name="hitung" type="submit" class="btn btn-success w-100"><i class="fa fa-search"></i> Hitung</button>
				</div>
			</div>
		</form>
	</div>
</div>

<?php
if (isset($_POST['hitung'])) {
	if ($_POST['metode'] == "wp") {
		mysqli_query($koneksi, "TRUNCATE TABLE hasil_wp;");

		$kriterias = mysqli_query($koneksi, "SELECT * FROM kriteria");
		$alternatifs = mysqli_query($koneksi, "SELECT * FROM alternatif");

		// 1. Normalisasi Bobot (supaya totalnya = 1)
		$total_bobot = 0;
		$arr_bobot = array();
		foreach ($kriterias as $kriteria) {
			$arr_bobot[$kriteria['id_kriteria']] = $kriteria['bobot'];
			$total_bobot += $kriteria['bobot'];
		}
		foreach ($arr_bobot as $id_kriteria => $bobot) {
			$arr_bobot[$id_kriteria] = $bobot / $total_bobot;
		}

		// 2. Ambil Nilai Penilaian
		$matriks_x = array();
		foreach ($kriterias as $kriteria):
			foreach ($alternatifs as $alternatif):
				$id_alternatif = $alternatif['id_alternatif'];
				$id_kriteria = $kriteria['id_kriteria'];
				
				if ($kriteria['ada_pilihan'] == 1) {
					$q4 = mysqli_query($koneksi, "SELECT sub_kriteria.nilai
                		FROM penilaian  
                		JOIN sub_kriteria ON penilaian.nilai=sub_kriteria.id_sub_kriteria 
                		WHERE penilaian.id_alternatif='$id_alternatif' 
                		AND penilaian.id_kriteria='$id_kriteria'");
					$data = mysqli_fetch_array($q4);
					$nilai = !empty($data['nilai']) ? $data['nilai'] : 0.0001;
				} else {
					$q4 = mysqli_query($koneksi, "SELECT nilai 
    		            FROM penilaian 
    		            WHERE id_alternatif='$id_alternatif' 
    		            AND id_kriteria='$id_kriteria'");
					$data = mysqli_fetch_array($q4);
						
					// === KONDISI KHUSUS: HARGA ===
					if ($id_kriteria == '7') { // ID untuk Harga
						$harga = !empty($data['nilai']) ? $data['nilai'] : 0;
						
						if ($harga >= 37000000) {
							$nilai = 5;
						} elseif ($harga >= 31000000 && $harga <= 36999999) {
							$nilai = 4;
						} elseif ($harga >= 25000000 && $harga <= 30999999) {
							$nilai = 3;
						} elseif ($harga >= 19000000 && $harga <= 24999999) {
							$nilai = 2;
						} elseif ($harga <= 18999999) {
							$nilai = 1;
						} else {
							$nilai = 0.0001;
						}
						
					// === KONDISI KHUSUS: TAHUN PERILISAN ===
					} elseif ($id_kriteria == '10') { // ID untuk Tahun Perilisan
						$tahun = !empty($data['nilai']) ? $data['nilai'] : 0;
						
						if ($tahun >= 2024) {
							$nilai = 5;
						} elseif ($tahun >= 2022 && $tahun <= 2023) {
							$nilai = 4;
						} elseif ($tahun >= 2019 && $tahun <= 2021) {
							$nilai = 3;
						} elseif ($tahun >= 2016 && $tahun <= 2018) {
							$nilai = 2;
						} elseif ($tahun <= 2015 && $tahun > 0) {
							
							$nilai = 1;
						} else {
							$nilai = 0.0001;
						}
						
						// === KONDISI BIASA ===
					} else {
						$nilai = !empty($data['nilai']) ? $data['nilai'] : 0.0001;
					}
				}
				
				$matriks_x[$id_alternatif][$id_kriteria] = $nilai;
			endforeach;
		endforeach;
		
			
		// 3. Hitung WP (S_i)
		$total_nilai = array();
		foreach ($alternatifs as $alternatif):
			$id_alternatif = $alternatif['id_alternatif'];
			$t = 1;

			foreach ($kriterias as $kriteria):
				$id_kriteria = $kriteria['id_kriteria'];
				$type = strtolower($kriteria['type']);
				$nilai = $matriks_x[$id_alternatif][$id_kriteria];
				$bobot = $arr_bobot[$id_kriteria];

				// Jika Cost, bobot jadi negatif
				if ($type == 'cost') {
					$bobot = -$bobot;
				}

				$t *= pow($nilai, $bobot);
			endforeach;

			$total_nilai[$id_alternatif] = $t;

			// Simpan ke tabel hasil_wp
			mysqli_query($koneksi, "INSERT INTO hasil_wp (id_alternatif, nilai) VALUES ('$id_alternatif', '$t')");
		endforeach;

		// 4. Normalisasi S_i menjadi V_i (opsional ranking)
		$sum_total = array_sum($total_nilai);
		foreach ($total_nilai as $id_alternatif => $si){
			$vi = $si / $sum_total;
			mysqli_query($koneksi, "UPDATE hasil_wp SET normalisasi='$vi' WHERE id_alternatif='$id_alternatif'");
		}
		// Ambil semua hasil dan gabungkan dengan data alternatif
		$ranking_data = [];
		foreach ($alternatifs as $alternatif) {
			$id_alternatif = $alternatif['id_alternatif'];
			$q = mysqli_query($koneksi, "SELECT * FROM hasil_wp WHERE id_alternatif='$id_alternatif'");
			$row = mysqli_fetch_assoc($q);
			$nilai_si = isset($row['nilai']) ? $row['nilai'] : 0;
			$nilai_vi = isset($row['normalisasi']) ? $row['normalisasi'] : 0;
			
			$ranking_data[] = [
				'tipe' => $alternatif['tipe'],
				'si' => $nilai_si,
				'vi' => $nilai_vi
			];
		}
		
		// Urutkan berdasarkan nilai Vᵢ descending
		usort($ranking_data, function($a, $b) {
			return $b['vi'] <=> $a['vi'];
		});
		?>
		
<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Bobot Kriteria (W)</h6>
    </div>

    <div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-primary text-white">
					<tr align="center">
						<?php foreach ($kriterias as $kriteria): ?>
						<th><?= $kriteria['kode_kriteria'] ?><br/><?= $kriteria['nama'] ?><br/>(<?= $kriteria['type'] ?>)</th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<tr align="center">
						<?php 
						
						foreach ($kriterias as $kriteria): ?>
						<td>
						<?php 
						echo $kriteria['bobot'];
						?>
						</td>
						<?php endforeach ?>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Normalisasi Bobot Kriteria (w<sub>j</sub>)</h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr align="center">
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Kriteria</th>
                        <th>Tipe</th>
                        <th>Bobot Ternormalisasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($kriterias as $kriteria):
                        $id_kriteria = $kriteria['id_kriteria'];
                        $bobot_norm = isset($arr_bobot[$id_kriteria]) ? $arr_bobot[$id_kriteria] : 0;
                    ?>
                    <tr align="center">
                        <td><?= $no++ ?></td>
                        <td><?= $kriteria['kode_kriteria'] ?></td>
                        <td align="left"><?= $kriteria['nama'] ?></td>
                        <td><?= $kriteria['type'] ?></td>
                        <td><?= number_format($bobot_norm, 3) ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Hasil Akhir WP (S<sub>i</sub> dan V<sub>i</sub>)</h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr align="center">
                        <th width="5%">No</th>
                        <th>Nama Alternatif</th>
                        <th>S<sub>i</sub></th>
                        <th>V<sub>i</sub> (Normalisasi)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($ranking_data as $i => $data): 
                        $id_alternatif = $alternatif['id_alternatif'];

                        // Ambil nilai dari hasil_wp jika kamu menyimpan ke DB
                        $q = mysqli_query($koneksi, "SELECT * FROM hasil_wp WHERE id_alternatif='$id_alternatif'");
                        $row = mysqli_fetch_assoc($q);
                        $nilai_si = isset($row['nilai']) ? $row['nilai'] : 0;
                        $nilai_vi = isset($row['normalisasi']) ? $row['normalisasi'] : 0;
                    ?>
                    <tr align="center">
						<td><?= $i + 1 ?></td>
						<td align="left"><?= $data['tipe'] ?></td>
						<td><?= number_format($data['si'], 3) ?></td>
						<td><?= number_format($data['vi'], 5) ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
}elseif($_POST['metode'] == "pm") {
	mysqli_query($koneksi,"TRUNCATE TABLE hasil_pm;");
	
	$kriterias = mysqli_query($koneksi,"SELECT * FROM kriteria");
	$alternatifs = mysqli_query($koneksi,"SELECT * FROM alternatif");
	
	//Matrix Keputusan (X)
	$matriks_x = array();
	foreach($kriterias as $kriteria):
		foreach($alternatifs as $alternatif):
			
			$id_alternatif = $alternatif['id_alternatif'];
			$id_kriteria = $kriteria['id_kriteria'];
			
			if($kriteria['ada_pilihan']==1){
				$q4 = mysqli_query($koneksi,"SELECT sub_kriteria.nilai FROM penilaian JOIN sub_kriteria WHERE penilaian.nilai=sub_kriteria.id_sub_kriteria AND penilaian.id_alternatif='$id_alternatif' AND penilaian.id_kriteria='$id_kriteria'");
				$data = mysqli_fetch_array($q4);
				if(!empty($data['nilai'])){
					$nilai = $data['nilai'];
				}else{
					$nilai = 0;
				}
			} else {
				$q4 = mysqli_query($koneksi, "SELECT nilai 
					FROM penilaian 
					WHERE id_alternatif='$id_alternatif' 
					AND id_kriteria='$id_kriteria'");
				$data = mysqli_fetch_array($q4);
					
				// === KONDISI KHUSUS: HARGA ===
				if ($id_kriteria == '7') { // ID untuk Harga
					$harga = !empty($data['nilai']) ? $data['nilai'] : 0;
					
					if ($harga >= 37000000) {
						$nilai = 5;
					} elseif ($harga >= 31000000 && $harga <= 36999999) {
						$nilai = 4;
					} elseif ($harga >= 25000000 && $harga <= 30999999) {
						$nilai = 3;
					} elseif ($harga >= 19000000 && $harga <= 24999999) {
						$nilai = 2;
					} elseif ($harga <= 18999999) {
						$nilai = 1;
					} else {
						$nilai = 0.0001;
					}
					
				// === KONDISI KHUSUS: TAHUN PERILISAN ===
				} elseif ($id_kriteria == '10') { // ID untuk Tahun Perilisan
					$tahun = !empty($data['nilai']) ? $data['nilai'] : 0;
					
					if ($tahun >= 2024) {
						$nilai = 5;
					} elseif ($tahun >= 2022 && $tahun <= 2023) {
						$nilai = 4;
					} elseif ($tahun >= 2019 && $tahun <= 2021) {
						$nilai = 3;
					} elseif ($tahun >= 2016 && $tahun <= 2018) {
						$nilai = 2;
					} elseif ($tahun <= 2015 && $tahun > 0) {
						
						$nilai = 1;
					} else {
						$nilai = 0.0001;
					}
					
					// === KONDISI BIASA ===
				} else {
					$nilai = !empty($data['nilai']) ? $data['nilai'] : 0.0001;
				}
			}
			
			$matriks_x[$id_kriteria][$id_alternatif] = $nilai;
		endforeach;
	endforeach;

		$jcf = 0;
		$jsf = 0;
		foreach($kriterias as $kriteria):
			if($kriteria['jenis'] == "Core Factor"){
				$jcf += 1;
			}else{
				$jsf += 1;
			}
		endforeach;

		$menghitung_gap = array();
		$konversi_gap = array();
		$mtariks_r = array();
		foreach($kriterias as $kriteria):
			foreach($alternatifs as $alternatif):
				$id_alternatif = $alternatif['id_alternatif'];
				$id_kriteria = $kriteria['id_kriteria'];
				$nilai = $matriks_x[$id_kriteria][$id_alternatif];
				$bobot_standar = $kriteria['bobot_standar'];
				if ($kriteria['type'] == 'Cost') {
					if ($nilai == 5) {
						$nilai = 1;
					} elseif ($nilai == 4) {
						$nilai = 2;
					} elseif ($nilai == 3) {
						$nilai = 3;
					} elseif ($nilai == 2) {
						$nilai = 4;
					} elseif ($nilai == 1) {
						$nilai = 5;
					}
				}
				$selisih = $nilai-$bobot_standar;
				  
				if($selisih == "0"){
					$nilai_bobot = 5;
				}elseif($selisih == "1"){
					$nilai_bobot = 4.5;
				}elseif($selisih == "-1"){
					$nilai_bobot = 4;
				}elseif($selisih == "2"){
					$nilai_bobot = 3.5;
				}elseif($selisih == "-2"){
					$nilai_bobot = 3;
				}elseif($selisih == "3"){
					$nilai_bobot = 2.5;
				}elseif($selisih == "-3"){
					$nilai_bobot = 2;
				}elseif($selisih == "4"){
					$nilai_bobot = 1.5;
				}elseif($selisih == "-4"){
					$nilai_bobot = 1;
				}
				$menghitung_gap[$id_kriteria][$id_alternatif] = $selisih;
				$konversi_gap[$id_kriteria][$id_alternatif] = $nilai_bobot;
			endforeach;
		endforeach;

		$result = mysqli_query($koneksi,"SELECT * FROM cf_sf WHERE id_cf_sf = '1';");
		if ($result && mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$d_cf = $row['bobot_cf'];
			$d_sf = $row['bobot_sf'];
		} else {
			$d_cf = 0;
			$d_sf = 0;
		}

		// NCF NSF
		$ncf = array();
		$nsf = array();
		$t_perh = array();
		foreach($alternatifs as $alternatif):
			$id_alternatif = $alternatif['id_alternatif'];
			$nocf = 0;
			$nosf = 0;
			$totalncf = 0;
			$totalnsf = 0;
			foreach($kriterias as $kriteria){
				$jenis_kriteria = $kriteria['jenis'];
				if($jenis_kriteria == 'Core Factor'){
					$id_kriteria = $kriteria['id_kriteria'];
					$nilaicf = $konversi_gap[$id_kriteria][$id_alternatif];
					$nocf += 1;
					$totalncf += $nilaicf;
				}
				
				if($jenis_kriteria == 'Secondary Factor'){
					$id_kriteria = $kriteria['id_kriteria'];
					$nilaisf = $konversi_gap[$id_kriteria][$id_alternatif];
					$nosf += 1;
					$totalnsf += $nilaisf;
				}
			}
			$bobot_cf = $d_cf/100;
			$bobot_sf = $d_sf/100;
			
			$t_cf = $totalncf/$nocf;
			$t_sf = $totalnsf/$nosf;

			$tn = ($t_cf*$bobot_cf)+($t_sf*$bobot_sf);
			
			$ncf[$id_alternatif] = $t_cf;
			$nsf[$id_alternatif] = $t_sf;
			$t_perh[$id_alternatif] = $tn;
		endforeach;
?>


<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Bobot Kriteria</h6>
    </div>

    <div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-primary text-white">
					<tr align="center">
						<?php foreach ($kriterias as $kriteria): ?>
						<th><?= $kriteria['kode_kriteria'] ?><br/><?= $kriteria['nama'] ?><br/>(<?= $kriteria['jenis'] ?>)</th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<tr align="center">
						<?php 
						foreach ($kriterias as $kriteria): ?>
						<td>
						<?php 
						echo $kriteria['bobot_standar'];
						?>
						</td>
						<?php endforeach ?>
					</tr>
					<tr align="center" class="bg-light">
						<td colspan="<?= $jcf ?>">Bobot Core Fator <?= $d_cf ?>%</td>
						<td colspan="<?= $jsf ?>">Bobot Secondary Factor <?= $d_sf ?>%</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Nilai Setiap Alternatif</h6>
    </div>

    <div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-primary text-white">
					<tr align="center">
						<th width="5%" rowspan="2">No</th>
						<th>Nama Alternatif</th>
						<?php foreach ($kriterias as $kriteria): ?>
							<th><?= $kriteria['kode_kriteria'] ?></th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<?php 
						$no=1;
						foreach ($alternatifs as $alternatif): ?>
					<tr align="center">
						<td><?= $no; ?></td>
						<td align="left"><?= $alternatif['tipe'] ?></td>
						<?php
						foreach ($kriterias as $kriteria):
							$id_alternatif = $alternatif['id_alternatif'];
							$id_kriteria = $kriteria['id_kriteria'];
							echo '<td>';
							echo $matriks_x[$id_kriteria][$id_alternatif];
							echo '</td>';
						endforeach
						?>
					</tr>
					<?php
						$no++;
						endforeach
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Menghitung GAP</h6>
    </div>

    <div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-primary text-white">
					<tr align="center">
						<th width="5%" rowspan="2">No</th>
						<th>Nama Alternatif</th>
						<?php foreach ($kriterias as $kriteria): ?>
							<th><?= $kriteria['kode_kriteria'] ?></th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<?php 
						$no=1;
						foreach ($alternatifs as $alternatif): ?>
					<tr align="center">
						<td><?= $no; ?></td>
						<td align="left"><?= $alternatif['tipe'] ?></td>
						<?php
						foreach ($kriterias as $kriteria):
							$id_alternatif = $alternatif['id_alternatif'];
							$id_kriteria = $kriteria['id_kriteria'];
							echo '<td>';
							echo $menghitung_gap[$id_kriteria][$id_alternatif];
							echo '</td>';
						endforeach
						?>
					</tr>
					<?php
						$no++;
						endforeach
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Pemetaan GAP</h6>
    </div>

    <div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-primary text-white">
					<tr align="center">
						<th>Selisih</th>
						<th>Nilai Bobot</th>
						<th>Keterangan</th>
					</tr>
				</thead>
				<tbody>
					<tr align="center">
						<td>0</td>
						<td>5</td>
						<td>Spesifikasi sesuai kebutuhan</td>
					</tr>
					<tr align="center">
						<td>1</td>
						<td>4.5</td>
						<td>Spesifikasi kelebihan 1 tingkat</td>
					</tr>
					<tr align="center">
						<td>-1</td>
						<td>4</td>
						<td>Spesifikasi kekurangan 1 tingkat</td>
					</tr>
					<tr align="center">
						<td>2</td>
						<td>3.5</td>
						<td>Spesifikasi kelebihan 2 tingkat</td>
					</tr>
					<tr align="center">
						<td>-2</td>
						<td>3</td>
						<td>Spesifikasi kekurangan 2 tingkat</td>
					</tr>
					<tr align="center">
						<td>3</td>
						<td>2.5</td>
						<td>Spesifikasi kelebihan 3 tingkat</td>
					</tr>
					<tr align="center">
						<td>-3</td>
						<td>2</td>
						<td>Spesifikasi kekurangan 3 tingkat</td>
					</tr>
					<tr align="center">
						<td>4</td>
						<td>1.5</td>
						<td>Spesifikasi kelebihan 4 tingkat</td>
					</tr>
					<tr align="center">
						<td>-4</td>
						<td>1</td>
						<td>Spesifikasi kekurangan 4 tingkat</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Konversi Nilai GAP</h6>
    </div>

    <div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-primary text-white">
					<tr align="center">
						<th width="5%" rowspan="2">No</th>
						<th>Nama Alternatif</th>
						<?php foreach ($kriterias as $kriteria): ?>
							<th><?= $kriteria['kode_kriteria'] ?></th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<?php 
						$no=1;
						foreach ($alternatifs as $alternatif): ?>
					<tr align="center">
						<td><?= $no; ?></td>
						<td align="left"><?= $alternatif['tipe'] ?></td>
						<?php
						foreach ($kriterias as $kriteria):
							$id_alternatif = $alternatif['id_alternatif'];
							$id_kriteria = $kriteria['id_kriteria'];
							echo '<td>';
							echo $konversi_gap[$id_kriteria][$id_alternatif];
							echo '</td>';
						endforeach
						?>
					</tr>
					<?php
						$no++;
						endforeach
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-table"></i> Perhitungan Nilai CF, SF dan Total Nilai</h6>
    </div>

    <div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-primary text-white">
					<tr align="center">
						<th width="5%">No</th>
						<th>Nama Lengkap</th>
						<th width="15%">NCF</th>
						<th width="15%">NSF</th>
						<th width="15%">Total Nilai</th>
					</tr>
				</thead>
				<tbody>
				<?php 
				// Urutkan total nilai dari terbesar ke terkecil
				arsort($t_perh);
				$no = 1;
				foreach ($t_perh as $id_alternatif => $nilai_total):
					// Cari nama alternatif berdasarkan id_alternatif
					$nama_alternatif = '';
					foreach ($alternatifs as $alt) {
						if ($alt['id_alternatif'] == $id_alternatif) {
							$nama_alternatif = $alt['tipe'];
							break;
						}
					}
				?>
				<tr align="center">
					<td><?= $no; ?></td>
					<td align="left"><?= $nama_alternatif ?></td>
					<td><?= number_format($ncf[$id_alternatif], 3) ?></td>
					<td><?= number_format($nsf[$id_alternatif], 3) ?></td>
					<td><?= number_format($nilai_total, 3) ?></td>
				</tr>
				<?php
				mysqli_query($koneksi,"INSERT INTO hasil_pm (id_hasil_pm, id_alternatif, nilai) VALUES ('', '$id_alternatif', '$nilai_total')");
				$no++;
				endforeach;
				?>			
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php
	}
}
require_once('template/footer.php');
}
else {
	header('Location: login.php');
}
?>
