-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2021 at 05:48 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpusto_db_perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` int(100) NOT NULL,
  `nama_anggota` varchar(200) NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `tmp_lahir` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `id_level` int(20) NOT NULL,
  `no_handphone` varchar(24) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`id_anggota`, `nama_anggota`, `alamat`, `tgl_lahir`, `tmp_lahir`, `username`, `password`, `id_level`, `no_handphone`, `email`) VALUES
(1, 'Tomi Irvan', 'Cilacap', '2000-02-16', 'Kebumen', 'irvan', 'admin', 1, '082329809323', 'tomiirvan@email.co');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(100) NOT NULL,
  `nama_buku` varchar(200) NOT NULL,
  `pengarang` int(100) NOT NULL,
  `penerbit` int(100) NOT NULL,
  `kode_isbn` varchar(200) NOT NULL,
  `rangkuman` text NOT NULL,
  `cover` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `nama_buku`, `pengarang`, `penerbit`, `kode_isbn`, `rangkuman`, `cover`) VALUES
(1, 'Fokus UN SMP 2020', 1, 1, '8420-4214-1412-85493', '<p>cek 123</p>', 'ebh-erlangga-fokus-un-2020-smp-mts.jpg'),
(2, 'PEMROGRAMAN BERBASIS FRAMEWORK', 2, 2, '8482-32482-34294-23484', '<p>DARI UMP</p>', 'modul framework.png');

-- --------------------------------------------------------

--
-- Stand-in structure for view `data-pengemalian-buku`
-- (See below for the actual view)
--
CREATE TABLE `data-pengemalian-buku` (
`berita_peminjaman` varchar(200)
,`id_buku` int(100)
,`id_anggota` int(100)
,`tgl_peminjaman` date
,`rencana_tgl_kembali` date
,`kondisi_buku_peminjaman` varchar(200)
,`id_peminjaman` int(100)
,`tgl_kembali` date
,`kondisi_buku_kembali` varchar(200)
,`Lama_Kembali` bigint(10)
,`Lama_Pinjam` bigint(10)
,`Terlambat` bigint(11)
,`id_kembali` int(100)
);

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `ID_Level` int(10) NOT NULL,
  `Level_Name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`ID_Level`, `Level_Name`) VALUES
(-2, 'Anonymous'),
(-1, 'Administrator'),
(0, 'Default'),
(1, 'Member');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(100) NOT NULL,
  `berita_peminjaman` varchar(200) NOT NULL,
  `id_buku` int(100) NOT NULL,
  `id_anggota` int(100) NOT NULL,
  `tgl_peminjaman` date NOT NULL,
  `rencana_tgl_kembali` date NOT NULL,
  `kondisi_buku_peminjaman` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `berita_peminjaman`, `id_buku`, `id_anggota`, `tgl_peminjaman`, `rencana_tgl_kembali`, `kondisi_buku_peminjaman`) VALUES
(1, 'Keperluan Ujian Nasional', 1, -1, '2021-03-10', '2021-03-11', 'Baru'),
(2, 'Matkul Framework', 2, 1, '2021-03-06', '2021-03-10', 'Baru');

-- --------------------------------------------------------

--
-- Table structure for table `penerbit`
--

CREATE TABLE `penerbit` (
  `id_penerbit` int(100) NOT NULL,
  `nama_penerbit` varchar(200) NOT NULL,
  `alamat_penerbit` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `penerbit`
--

INSERT INTO `penerbit` (`id_penerbit`, `nama_penerbit`, `alamat_penerbit`) VALUES
(1, 'Erlangga', NULL),
(2, 'Universitas Muhammadiyah Purwokerto', 'Purwokerto');

-- --------------------------------------------------------

--
-- Table structure for table `pengarang`
--

CREATE TABLE `pengarang` (
  `id_pengarang` int(100) NOT NULL,
  `nama_pengarang` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengarang`
--

INSERT INTO `pengarang` (`id_pengarang`, `nama_pengarang`) VALUES
(1, 'Sukismo, dkk'),
(2, 'Achmad Fauzan, S.Kom., M.Cs.');

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id_kembali` int(100) NOT NULL,
  `id_peminjaman` int(100) NOT NULL,
  `tgl_kembali` date NOT NULL,
  `kondisi_buku_kembali` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengembalian`
--

INSERT INTO `pengembalian` (`id_kembali`, `id_peminjaman`, `tgl_kembali`, `kondisi_buku_kembali`) VALUES
(1, 1, '2021-03-13', 'Baru'),
(2, 2, '2021-03-14', 'Baru');

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `Table_Name` varchar(200) NOT NULL,
  `ID_Level` int(20) NOT NULL,
  `Permission` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`Table_Name`, `ID_Level`, `Permission`) VALUES
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}anggota', -2, 104),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}buku', -2, 104),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}peminjaman', -2, 32),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}penerbit', -2, 32),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}pengarang', -2, 32),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}pengembalian', -2, 0),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}data-pengemalian-buku', -2, 0),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}level', -2, 0),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}permission', -2, 0),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}anggota', 0, 0),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}buku', 0, 0),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}peminjaman', 0, 0),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}penerbit', 0, 0),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}pengarang', 0, 0),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}pengembalian', 0, 0),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}data-pengemalian-buku', 0, 0),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}level', 0, 0),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}permission', 0, 0),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}anggota', 1, 108),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}buku', 1, 104),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}peminjaman', 1, 104),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}penerbit', 1, 104),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}pengarang', 1, 104),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}pengembalian', 1, 104),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}data-pengemalian-buku', 1, 40),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}level', 1, 0),
('{69EED69A-4609-4945-8B51-0CAFD5F5996C}permission', 1, 0);

-- --------------------------------------------------------

--
-- Structure for view `data-pengemalian-buku`
--
DROP TABLE IF EXISTS `data-pengemalian-buku`;

CREATE VIEW `data-pengemalian-buku`  AS  select `peminjaman`.`berita_peminjaman` AS `berita_peminjaman`,`peminjaman`.`id_buku` AS `id_buku`,`peminjaman`.`id_anggota` AS `id_anggota`,`peminjaman`.`tgl_peminjaman` AS `tgl_peminjaman`,`peminjaman`.`rencana_tgl_kembali` AS `rencana_tgl_kembali`,`peminjaman`.`kondisi_buku_peminjaman` AS `kondisi_buku_peminjaman`,`pengembalian`.`id_peminjaman` AS `id_peminjaman`,`pengembalian`.`tgl_kembali` AS `tgl_kembali`,`pengembalian`.`kondisi_buku_kembali` AS `kondisi_buku_kembali`,`pengembalian`.`tgl_kembali` - `peminjaman`.`tgl_peminjaman` AS `Lama_Kembali`,`peminjaman`.`rencana_tgl_kembali` - `peminjaman`.`tgl_peminjaman` AS `Lama_Pinjam`,`pengembalian`.`tgl_kembali` - `peminjaman`.`tgl_peminjaman` - (`peminjaman`.`rencana_tgl_kembali` - `peminjaman`.`tgl_peminjaman`) AS `Terlambat`,`pengembalian`.`id_kembali` AS `id_kembali` from (`peminjaman` join `pengembalian` on(`peminjaman`.`id_peminjaman` = `pengembalian`.`id_peminjaman`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`);

--
-- Indexes for table `penerbit`
--
ALTER TABLE `penerbit`
  ADD PRIMARY KEY (`id_penerbit`);

--
-- Indexes for table `pengarang`
--
ALTER TABLE `pengarang`
  ADD PRIMARY KEY (`id_pengarang`);

--
-- Indexes for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`id_kembali`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id_anggota` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `penerbit`
--
ALTER TABLE `penerbit`
  MODIFY `id_penerbit` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pengarang`
--
ALTER TABLE `pengarang`
  MODIFY `id_pengarang` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id_kembali` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
