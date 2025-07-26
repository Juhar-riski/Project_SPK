-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2025 at 12:20 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_pm_wp`
--

-- --------------------------------------------------------

--
-- Table structure for table `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int(11) NOT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `tipe` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `brand`, `tipe`) VALUES
(9, 'Honda', 'Beat CBS'),
(10, 'Honda', 'Beat Deluxe'),
(11, 'Honda', 'Beat Street'),
(12, 'Honda', 'Genio CBS'),
(13, 'Honda', 'Genio CBS-ISS'),
(14, 'Honda', 'Scoopy Fashion'),
(15, 'Honda', 'Scoopy Prestige & Stylish'),
(16, 'Honda', 'Vario 125 CBS'),
(17, 'Honda', 'Vario 125 CBS-ISS'),
(18, 'Honda', 'Vario 160 CBS'),
(19, 'Honda', 'Vario 160 ABS'),
(20, 'Honda', 'PCX 160 CBS'),
(21, 'Honda', 'PCX 160 ABS'),
(22, 'Honda', 'ADV 160 CBS'),
(23, 'Honda', 'ADV 160 ABS'),
(24, 'Yamaha', 'NMAX Neo'),
(25, 'Yamaha', 'NMAX Neo S'),
(26, 'Yamaha', 'NMAX Turbo'),
(27, 'Yamaha', 'NMAX Turbo Tech Max'),
(28, 'Yamaha', 'Fazzio Hybrid Neo'),
(29, 'Yamaha', 'Fazzio Hybrid Lux'),
(30, 'Yamaha', 'Fazzio Hybrid'),
(31, 'Yamaha', 'Grand Filano Hybrid Lux'),
(32, 'Yamaha', 'Grand Filano Hybrid Neo'),
(33, 'Yamaha', 'Lexi LX 155 Max'),
(34, 'Yamaha', 'Lexi LX 155 S Max'),
(35, 'Yamaha', 'Aerox 155'),
(36, 'Yamaha', 'Aerox ABS Connected 155'),
(37, 'Yamaha', 'Aerox 155 Cybercity'),
(38, 'Yamaha', 'Aerox Alpha'),
(39, 'Yamaha', 'Aerox Alpha Cybercity'),
(40, 'Yamaha', 'Aerox Alpha Turbo'),
(41, 'Suzuki', 'Nex II'),
(42, 'Suzuki', 'Nex Crossover'),
(43, 'Suzuki', 'Addres F1'),
(47, 'dsss', 'sd');

-- --------------------------------------------------------

--
-- Table structure for table `cf_sf`
--

CREATE TABLE `cf_sf` (
  `id_cf_sf` int(11) NOT NULL,
  `bobot_cf` float NOT NULL,
  `bobot_sf` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cf_sf`
--

INSERT INTO `cf_sf` (`id_cf_sf`, `bobot_cf`, `bobot_sf`) VALUES
(1, 80, 20);

-- --------------------------------------------------------

--
-- Table structure for table `hasil_pm`
--

CREATE TABLE `hasil_pm` (
  `id_hasil_pm` int(11) NOT NULL,
  `id_alternatif` int(11) NOT NULL,
  `nilai` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hasil_pm`
--

INSERT INTO `hasil_pm` (`id_hasil_pm`, `id_alternatif`, `nilai`) VALUES
(1, 19, 4),
(2, 24, 4),
(3, 18, 3.8),
(4, 22, 3.8),
(5, 23, 3.8),
(6, 25, 3.8),
(7, 20, 3.6),
(8, 21, 3.6),
(9, 16, 3.6),
(10, 28, 3.4),
(11, 30, 3.4),
(12, 33, 3.4),
(13, 34, 3.4),
(14, 38, 3.4),
(15, 26, 3.4),
(16, 36, 3.4),
(17, 12, 3.2),
(18, 13, 3.2),
(19, 17, 3.2),
(20, 27, 3.2),
(21, 32, 3.2),
(22, 35, 3.2),
(23, 9, 3),
(24, 14, 3),
(25, 15, 3),
(26, 10, 2.8),
(27, 11, 2.8),
(28, 29, 2.8),
(29, 37, 2.8),
(30, 39, 2.8),
(31, 41, 2.8),
(32, 40, 2.6),
(33, 31, 2.6),
(34, 42, 2.6),
(35, 43, 2.6);

-- --------------------------------------------------------

--
-- Table structure for table `hasil_wp`
--

CREATE TABLE `hasil_wp` (
  `id_hasil` int(11) NOT NULL,
  `id_alternatif` int(11) DEFAULT NULL,
  `nilai` double DEFAULT NULL,
  `normalisasi` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hasil_wp`
--

INSERT INTO `hasil_wp` (`id_hasil`, `id_alternatif`, `nilai`, `normalisasi`) VALUES
(1, 9, 1.6099782259245, 0.027870116807973),
(2, 10, 1.5720331253996, 0.027213254269779),
(3, 11, 1.5720331253996, 0.027213254269779),
(4, 12, 1.6938229573777, 0.02932154169168),
(5, 13, 1.6938229573777, 0.02932154169168),
(6, 14, 1.6821280114152, 0.029119092053053),
(7, 15, 1.6821280114152, 0.029119092053053),
(8, 16, 1.9938780943442, 0.034515755862674),
(9, 17, 1.7280199253006, 0.029913520809872),
(10, 18, 1.9957528633937, 0.034548209737861),
(11, 19, 2.1033382370893, 0.036410605690455),
(12, 20, 1.772806759286, 0.030688819676982),
(13, 21, 1.7496887921847, 0.030288627653822),
(14, 22, 1.8651313039645, 0.032287037468378),
(15, 23, 1.8408093952743, 0.031866004174093),
(16, 24, 1.9656752400444, 0.034027540040251),
(17, 25, 1.8408093952743, 0.031866004174093),
(18, 26, 1.7672629610642, 0.030592851730639),
(19, 27, 1.6966549505709, 0.02937056594544),
(20, 28, 1.5492674428984, 0.026819160598009),
(21, 29, 1.4679733071861, 0.025411888734563),
(22, 30, 1.7227305378236, 0.029821956933756),
(23, 31, 1.3488742729012, 0.023350181350086),
(24, 32, 1.5290644999727, 0.026469430166792),
(25, 33, 1.6954155957967, 0.02934911164142),
(26, 34, 1.6954155957967, 0.02934911164142),
(27, 35, 1.5060202508823, 0.026070514266217),
(28, 36, 1.7937260199913, 0.031050950189063),
(29, 37, 1.4603721105907, 0.025280305441334),
(30, 38, 1.5844510404825, 0.027428219129736),
(31, 39, 1.4245189543116, 0.024659656268978),
(32, 40, 1.3676047024487, 0.02367442130001),
(33, 41, 1.3079892967469, 0.022642427019771),
(34, 42, 1.2432434442647, 0.021521620264465),
(35, 43, 1.2432434442647, 0.021521620264465),
(36, 45, 0.0015013107289082, 0.000025988988363931);

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `kode_kriteria` varchar(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `bobot` float NOT NULL,
  `bobot_standar` float NOT NULL,
  `ada_pilihan` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `kode_kriteria`, `nama`, `type`, `jenis`, `bobot`, `bobot_standar`, `ada_pilihan`) VALUES
(7, 'C1', 'Harga', 'Cost', 'Core Factor', 5, 5, 0),
(8, 'C2', 'Kapasitas Mesin', 'Benefit', 'Core Factor', 4, 5, 1),
(9, 'C3', 'Tingkat Keamanan', 'Benefit', 'Core Factor', 4, 5, 1),
(10, 'C4', 'Tahun Perilisan', 'Benefit', 'Core Factor', 3, 5, 0),
(11, 'C5', 'Jumlah Warna Yang Di Rilis', 'Benefit', 'Secondary Factor', 1, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `penilaian`
--

CREATE TABLE `penilaian` (
  `id_penilaian` int(11) NOT NULL,
  `id_alternatif` int(10) NOT NULL,
  `id_kriteria` int(10) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `penilaian`
--

INSERT INTO `penilaian` (`id_penilaian`, `id_alternatif`, `id_kriteria`, `nilai`) VALUES
(316, 9, 7, 19193000),
(317, 9, 8, 30),
(318, 9, 9, 34),
(319, 9, 10, 2020),
(320, 9, 11, 44),
(321, 10, 7, 19965000),
(322, 10, 8, 30),
(323, 10, 9, 34),
(324, 10, 10, 2020),
(325, 10, 11, 45),
(331, 11, 7, 19874000),
(332, 11, 8, 30),
(333, 11, 9, 34),
(334, 11, 10, 2020),
(335, 11, 11, 45),
(336, 12, 7, 20272000),
(337, 12, 8, 30),
(338, 12, 9, 34),
(339, 12, 10, 2022),
(340, 12, 11, 44),
(341, 13, 7, 20817000),
(342, 13, 8, 30),
(343, 13, 9, 34),
(344, 13, 10, 2022),
(345, 13, 11, 44),
(351, 15, 7, 23911000),
(352, 15, 8, 30),
(353, 15, 9, 33),
(354, 15, 10, 2020),
(355, 15, 11, 45),
(356, 14, 7, 22794000),
(357, 14, 8, 30),
(358, 14, 9, 33),
(359, 14, 10, 2020),
(360, 14, 11, 45),
(361, 16, 7, 23522000),
(362, 16, 8, 29),
(363, 16, 9, 33),
(364, 16, 10, 2022),
(365, 16, 11, 44),
(371, 17, 7, 25303000),
(372, 17, 8, 29),
(373, 17, 9, 33),
(374, 17, 10, 2022),
(375, 17, 11, 45),
(376, 18, 7, 27557000),
(377, 18, 8, 27),
(378, 18, 9, 33),
(379, 18, 10, 2022),
(380, 18, 11, 44),
(381, 19, 7, 30281000),
(382, 19, 8, 27),
(383, 19, 9, 32),
(384, 19, 10, 2022),
(385, 19, 11, 44),
(386, 20, 7, 33411000),
(387, 20, 8, 27),
(388, 20, 9, 33),
(389, 20, 10, 2021),
(390, 20, 11, 43),
(391, 21, 7, 37043000),
(392, 21, 8, 27),
(393, 21, 9, 32),
(394, 21, 10, 2021),
(395, 21, 11, 43),
(401, 22, 7, 36784000),
(402, 22, 8, 27),
(403, 22, 9, 33),
(404, 22, 10, 2022),
(405, 22, 11, 43),
(406, 23, 7, 39995000),
(407, 23, 8, 27),
(408, 23, 9, 32),
(409, 23, 10, 2022),
(410, 23, 11, 43),
(411, 24, 7, 36385000),
(412, 24, 8, 27),
(413, 24, 9, 32),
(414, 24, 10, 2023),
(415, 24, 11, 43),
(416, 25, 7, 37485000),
(417, 25, 8, 27),
(418, 25, 9, 32),
(419, 25, 10, 2023),
(420, 25, 11, 43),
(421, 26, 7, 41745000),
(422, 26, 8, 27),
(423, 26, 9, 32),
(424, 26, 10, 2023),
(425, 26, 11, 45),
(426, 27, 7, 46345000),
(427, 27, 8, 27),
(428, 27, 9, 32),
(429, 27, 10, 2023),
(430, 27, 11, 46),
(431, 28, 7, 25198000),
(432, 28, 8, 29),
(433, 28, 9, 35),
(434, 28, 10, 2022),
(435, 28, 11, 42),
(436, 29, 7, 25899000),
(437, 29, 8, 29),
(438, 29, 9, 35),
(439, 29, 10, 2022),
(440, 29, 11, 45),
(441, 30, 7, 23444000),
(442, 30, 8, 29),
(443, 30, 9, 35),
(444, 30, 10, 2022),
(445, 30, 11, 43),
(446, 31, 7, 31133000),
(447, 31, 8, 29),
(448, 31, 9, 35),
(449, 31, 10, 2023),
(450, 31, 11, 45),
(451, 32, 7, 30383000),
(452, 32, 8, 29),
(453, 32, 9, 35),
(454, 32, 10, 2023),
(455, 32, 11, 43),
(456, 33, 7, 28458000),
(457, 33, 8, 27),
(458, 33, 9, 35),
(459, 33, 10, 2023),
(460, 33, 11, 44),
(461, 34, 7, 29957000),
(462, 34, 8, 27),
(463, 34, 9, 35),
(464, 34, 10, 2023),
(465, 34, 11, 44),
(466, 35, 7, 31484000),
(467, 35, 8, 27),
(468, 35, 9, 35),
(469, 35, 10, 2020),
(470, 35, 11, 43),
(471, 36, 7, 35259000),
(472, 36, 8, 27),
(473, 36, 9, 32),
(474, 36, 10, 2020),
(475, 36, 11, 45),
(476, 37, 7, 31684000),
(477, 37, 8, 27),
(478, 37, 9, 35),
(479, 37, 10, 2023),
(480, 37, 11, 46),
(481, 38, 7, 34145000),
(482, 38, 8, 27),
(483, 38, 9, 35),
(484, 38, 10, 2023),
(485, 38, 11, 43),
(486, 39, 7, 38265000),
(487, 39, 8, 27),
(488, 39, 9, 35),
(489, 39, 10, 2023),
(490, 39, 11, 45),
(491, 40, 7, 44720000),
(492, 40, 8, 27),
(493, 40, 9, 35),
(494, 40, 10, 2023),
(495, 40, 11, 46),
(496, 41, 7, 20180000),
(497, 41, 8, 30),
(498, 41, 9, 36),
(499, 41, 10, 2023),
(500, 41, 11, 44),
(501, 42, 7, 21265000),
(502, 42, 8, 30),
(503, 42, 9, 36),
(504, 42, 10, 2020),
(505, 42, 11, 44),
(506, 43, 7, 21435000),
(507, 43, 8, 30),
(508, 43, 9, 36),
(509, 43, 10, 2020),
(510, 43, 11, 44);

-- --------------------------------------------------------

--
-- Table structure for table `sub_kriteria`
--

CREATE TABLE `sub_kriteria` (
  `id_sub_kriteria` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sub_kriteria`
--

INSERT INTO `sub_kriteria` (`id_sub_kriteria`, `id_kriteria`, `nama`, `nilai`) VALUES
(21, 7, 'Harga >= 37.000.000', 5),
(22, 7, '31.000.000 <= Harga <= 36.999.999', 4),
(23, 7, '25.000.000 <= Harga <= 30.999.999', 3),
(24, 7, '19.000.000 <= Harga <= 24.999.999', 2),
(25, 7, 'Harga <= 18.999.999', 1),
(27, 8, 'Kapasitas Mesin >= 150', 5),
(28, 8, '130 <= Kapasitas Mesin <= 149 ', 4),
(29, 8, '120 <= Kapasitas Mesin <= 129', 3),
(30, 8, '110 <= Kapasitas Mesin <= 119', 2),
(31, 8, 'Kapasitas mesin <= 109', 1),
(32, 9, 'ABS, Smart Key System', 5),
(33, 9, 'CBS, Smart Key System atau CBS, Secure Key Shutter', 4),
(34, 9, 'CBS, Secure Key Shutter', 3),
(35, 9, 'Cakram depan, tromol belakang, Smart Key System', 2),
(36, 9, 'Cakram depan, tromol belakang, Kunci standar', 1),
(37, 10, 'Tahun >=2024', 5),
(38, 10, '2022 <= Tahun <= 2023', 4),
(39, 10, '2019 <= Tahun <= 2021', 3),
(40, 10, '2016 <= Tahun <= 2018', 2),
(41, 10, 'Tahun <= 2015', 1),
(42, 11, '5', 5),
(43, 11, '4', 4),
(44, 11, '3', 3),
(45, 11, '2', 2),
(46, 11, '1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(5) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama` varchar(70) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `role` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `email`, `role`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Admin', 'admin@gmail.com', '1'),
(8, 'user', '12dea96fec20593566ab75692c9949596833adc9', 'User', 'user@gmail.com', '2'),
(11, 'Juhar', '88738b130c5d92147f081277c0c90bfe2726c915', 'Juhar Riski Ahmadi', 'juhar@mail.com', '2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indexes for table `cf_sf`
--
ALTER TABLE `cf_sf`
  ADD PRIMARY KEY (`id_cf_sf`);

--
-- Indexes for table `hasil_pm`
--
ALTER TABLE `hasil_pm`
  ADD PRIMARY KEY (`id_hasil_pm`);

--
-- Indexes for table `hasil_wp`
--
ALTER TABLE `hasil_wp`
  ADD PRIMARY KEY (`id_hasil`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id_penilaian`);

--
-- Indexes for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD PRIMARY KEY (`id_sub_kriteria`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `cf_sf`
--
ALTER TABLE `cf_sf`
  MODIFY `id_cf_sf` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hasil_pm`
--
ALTER TABLE `hasil_pm`
  MODIFY `id_hasil_pm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `hasil_wp`
--
ALTER TABLE `hasil_wp`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=511;

--
-- AUTO_INCREMENT for table `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  MODIFY `id_sub_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
