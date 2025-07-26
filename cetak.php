<?php
require_once('includes/init.php');

$user_role = get_role();
if($user_role == 'admin' || $user_role == 'user') {
?>	

<html>
	<head>
		<title>Sistem Pendukung Keputusan Metode WP PM</title>
	</head>
<body onload="window.print();">

<div style="width:100%;margin:0 auto;text-align:center;">
	
	<h4>Hasil Akhir Perankingan Wp</h4>
	<br/>
	<table width="100%" cellspacing="0" cellpadding="5" border="1">
		<thead>
			<tr align="center">
				<th>Brand</th>
				<th>Tipe</th>
				<th>Total Nilai</th>
				<th width="15%">Rank</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$no=0;
				$prev_rank = 0;
				$prev_value = null;
				$query = mysqli_query($koneksi,"SELECT * FROM hasil_wp JOIN alternatif ON hasil_wp.id_alternatif=alternatif.id_alternatif ORDER BY hasil_wp.nilai DESC");
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
				<td><?= number_format ($data['nilai'], 3) ?></td>
				<td><?= $rank ?></td>
			</tr>
			<?php
				}
			?>
		</tbody>
	</table>
	<br/><br/>
	<h4>Hasil Akhir Perankingan PM</h4>
	<br/>
	<table width="100%" cellspacing="0" cellpadding="5" border="1">
		<thead>
			<tr align="center">
				<th>Brand</th>
				<th>Tipe</th>
				<th>Total Nilai</th>
				<th width="15%">Rank</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$no=0;
				$prev_rank = 0;
				$prev_value = null;
				$query = mysqli_query($koneksi,"SELECT * FROM hasil_pm JOIN alternatif ON hasil_pm.id_alternatif=alternatif.id_alternatif ORDER BY hasil_pm.nilai DESC");
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

</body>
</html>

<?php
}
else {
	header('Location: login.php');
}
?>