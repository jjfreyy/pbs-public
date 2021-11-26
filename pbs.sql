-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versi server:                 10.4.10-MariaDB-log - mariadb.org binary distribution
-- OS Server:                    Win64
-- HeidiSQL Versi:               11.1.0.6116
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Membuang struktur basisdata untuk pbs
CREATE DATABASE IF NOT EXISTS `pbs` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `pbs`;

-- membuang struktur untuk table pbs.tbank
DROP TABLE IF EXISTS `tbank`;
CREATE TABLE IF NOT EXISTS `tbank` (
  `id_bank` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama_bank` varchar(50) NOT NULL,
  `an` varchar(50) NOT NULL,
  `no_rek` varchar(20) NOT NULL,
  PRIMARY KEY (`id_bank`),
  UNIQUE KEY `no_rek` (`no_rek`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tbank: ~5 rows (lebih kurang)
/*!40000 ALTER TABLE `tbank` DISABLE KEYS */;
INSERT INTO `tbank` (`id_bank`, `nama_bank`, `an`, `no_rek`) VALUES
	(1, 'BCA', 'Mr. J', '021 557 2021'),
	(2, 'BCA', 'Yayasan Maitreya Duta', '021 566 8888'),
	(3, 'BNI', 'Yayasan Maitreya Duta', '559 201 990 1'),
	(4, 'Mandiri', 'Mr. G', '1125 2111 2990 1'),
	(5, 'BCA', 'Mr. X', '022 890 2110');
/*!40000 ALTER TABLE `tbank` ENABLE KEYS */;

-- membuang struktur untuk table pbs.tbulan
DROP TABLE IF EXISTS `tbulan`;
CREATE TABLE IF NOT EXISTS `tbulan` (
  `kode` varchar(2) NOT NULL,
  `nama` varchar(20) NOT NULL,
  PRIMARY KEY (`kode`),
  UNIQUE KEY `nama` (`nama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tbulan: ~12 rows (lebih kurang)
/*!40000 ALTER TABLE `tbulan` DISABLE KEYS */;
INSERT INTO `tbulan` (`kode`, `nama`) VALUES
	('08', 'Agustus'),
	('04', 'April'),
	('12', 'Desember'),
	('02', 'Februari'),
	('01', 'Januari'),
	('07', 'Juli'),
	('06', 'Juni'),
	('03', 'Maret'),
	('05', 'Mei'),
	('11', 'November'),
	('10', 'Oktober'),
	('09', 'September');
/*!40000 ALTER TABLE `tbulan` ENABLE KEYS */;

-- membuang struktur untuk table pbs.tdonatur
DROP TABLE IF EXISTS `tdonatur`;
CREATE TABLE IF NOT EXISTS `tdonatur` (
  `id_donatur` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode_donatur` varchar(15) NOT NULL,
  `nama_id` varchar(100) NOT NULL,
  `nama_cn` varchar(100) DEFAULT NULL,
  `alamat` varchar(150) DEFAULT NULL,
  `kota_lahir` varchar(50) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `kota_domisili` varchar(50) DEFAULT NULL,
  `no_hp1` varchar(20) DEFAULT NULL,
  `no_hp2` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `ket` varchar(100) DEFAULT NULL,
  `tgl_gabung` date NOT NULL DEFAULT curdate(),
  PRIMARY KEY (`id_donatur`),
  KEY `kota_lahir#tdonatur-tkota` (`kota_lahir`) USING BTREE,
  KEY `kota_domisili#tdonatur-tkota` (`kota_domisili`) USING BTREE,
  CONSTRAINT `kota_domisili#tdonatur-tkota` FOREIGN KEY (`kota_domisili`) REFERENCES `tkota` (`nama`) ON UPDATE CASCADE,
  CONSTRAINT `kota_lahir#tdonatur-tkota` FOREIGN KEY (`kota_lahir`) REFERENCES `tkota` (`nama`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tdonatur: ~3 rows (lebih kurang)
/*!40000 ALTER TABLE `tdonatur` DISABLE KEYS */;
INSERT INTO `tdonatur` (`id_donatur`, `kode_donatur`, `nama_id`, `nama_cn`, `alamat`, `kota_lahir`, `tgl_lahir`, `kota_domisili`, `no_hp1`, `no_hp2`, `email`, `ket`, `tgl_gabung`) VALUES
	(1, 'D-000001', 'Mr. Y', NULL, 'Jl. Y', 'Jakarta', '1990-02-16', 'Jakarta', '0877-6901-2554', '0877290110', 'mry@gg.mail', 'Ket XY', '2020-04-03'),
	(2, 'D-000002', 'Mr. Z', NULL, 'Jl. Z', 'Ambon', '1977-10-10', 'Ambon', '0825 1108 2001', NULL, NULL, NULL, '2020-04-04'),
	(3, 'D-000003', 'Mr. X', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-06-04');
/*!40000 ALTER TABLE `tdonatur` ENABLE KEYS */;

-- membuang struktur untuk table pbs.tjenis_souvenir
DROP TABLE IF EXISTS `tjenis_souvenir`;
CREATE TABLE IF NOT EXISTS `tjenis_souvenir` (
  `nama` varchar(20) NOT NULL,
  PRIMARY KEY (`nama`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tjenis_souvenir: ~9 rows (lebih kurang)
/*!40000 ALTER TABLE `tjenis_souvenir` DISABLE KEYS */;
INSERT INTO `tjenis_souvenir` (`nama`) VALUES
	('Gula'),
	('Jam Tangan'),
	('Kalung'),
	('Lukisan'),
	('Minyak Kayu Putih'),
	('Pakaian'),
	('Pena'),
	('Sembako'),
	('Smartphone');
/*!40000 ALTER TABLE `tjenis_souvenir` ENABLE KEYS */;

-- membuang struktur untuk table pbs.tkolektor
DROP TABLE IF EXISTS `tkolektor`;
CREATE TABLE IF NOT EXISTS `tkolektor` (
  `id_kolektor` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode_kolektor` varchar(15) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `no_hp1` varchar(20) NOT NULL,
  `no_hp2` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `ket` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_kolektor`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tkolektor: ~4 rows (lebih kurang)
/*!40000 ALTER TABLE `tkolektor` DISABLE KEYS */;
INSERT INTO `tkolektor` (`id_kolektor`, `kode_kolektor`, `nama`, `no_hp1`, `no_hp2`, `email`, `ket`) VALUES
	(1, 'K-001', 'Kolektor X', '08872160099', NULL, 'kx@gg.com', 'Ket X'),
	(2, 'K-002', 'Kolektor Y', '0877 1055 209', NULL, 'ky@gg.com', NULL),
	(3, 'K-003', 'Kolektor Z', '0821 5100 2199', NULL, 'kz@gg.com', 'Ket Z'),
	(4, 'K-002', 'Kolektor Y', '0877 1055 209', NULL, 'ky@gg.com', NULL);
/*!40000 ALTER TABLE `tkolektor` ENABLE KEYS */;

-- membuang struktur untuk table pbs.tkota
DROP TABLE IF EXISTS `tkota`;
CREATE TABLE IF NOT EXISTS `tkota` (
  `nama` varchar(50) NOT NULL,
  PRIMARY KEY (`nama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tkota: ~13 rows (lebih kurang)
/*!40000 ALTER TABLE `tkota` DISABLE KEYS */;
INSERT INTO `tkota` (`nama`) VALUES
	('Ambon'),
	('Bandung'),
	('Banjarmansin'),
	('Jakarta'),
	('Jambi'),
	('Kalimantan'),
	('Lampung'),
	('Maluku'),
	('Nusa Tenggara Barat'),
	('Palembang'),
	('Papua'),
	('Pontianak'),
	('Surabaya');
/*!40000 ALTER TABLE `tkota` ENABLE KEYS */;

-- membuang struktur untuk table pbs.tmenu
DROP TABLE IF EXISTS `tmenu`;
CREATE TABLE IF NOT EXISTS `tmenu` (
  `kode` varchar(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `link` varchar(50) NOT NULL DEFAULT '#',
  `sta` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tmenu: ~36 rows (lebih kurang)
/*!40000 ALTER TABLE `tmenu` DISABLE KEYS */;
INSERT INTO `tmenu` (`kode`, `nama`, `link`, `sta`) VALUES
	('1', 'Master', '#', 1),
	('11', 'Bank', 'master/bank', 1),
	('12', 'Donatur', 'master/donatur', 1),
	('13', 'Kolektor', 'master/kolektor', 1),
	('14', 'Paket', 'master/paket', 1),
	('15', 'Souvenir', 'master/souvenir', 1),
	('2', 'Input', '#', 1),
	('21', 'Paket Sumbangan', 'input/paket_sumbangan', 1),
	('22', 'Sumbangan', 'input/sumbangan', 1),
	('23', 'Souvenir Masuk', 'input/souvenir_masuk', 1),
	('24', 'Souvenir Keluar', 'input/souvenir_keluar', 1),
	('3', 'Daftar', '#', 1),
	('31', 'Donatur', 'daftar/donatur', 1),
	('32', 'Kolektor', 'daftar/kolektor', 1),
	('33', 'Biaowen', 'daftar/biaowen', 1),
	('34', 'Paket', 'daftar/paket', 1),
	('35', 'Souvenir', 'daftar/souvenir', 1),
	('4', 'Tampil', '#', 1),
	('41', 'Paket Sumbangan', 'tampil/paket_sumbangan', 1),
	('42', 'Sumbangan', 'tampil/sumbangan', 1),
	('43', 'Souvenir Masuk', 'tampil/souvenir_masuk', 1),
	('44', 'Souvenir Keluar', 'tampil/souvenir_keluar', 1),
	('5', 'Laporan', '#', 1),
	('51', 'Rekap Donatur', '#', 1),
	('511', 'By Kolektor', 'laporan/rekap_donatur/by_kolektor', 1),
	('512', 'By Kota Domisili', 'laporan/rekap_donatur/by_kota_domisili', 1),
	('52', 'Penerimaan', '#', 1),
	('521', 'Harian', 'laporan/penerimaan/harian', 1),
	('522', 'Bulanan', 'laporan/penerimaan/bulanan', 1),
	('53', 'Paket', '#', 1),
	('531', 'Harian Detail', 'laporan/paket/harian_detail', 1),
	('532', 'Bulanan Detail', 'laporan/paket/bulanan_detail', 1),
	('533', 'Rekapan', 'laporan/paket/rekapan', 1),
	('6', 'Sistem', '#', 1),
	('61', 'Tambah User', 'sistem/tambah_user', 1),
	('62', 'Ubah Password', 'sistem/ubah_password', 1),
	('63', 'Backup', 'sistem/backup', 1);
/*!40000 ALTER TABLE `tmenu` ENABLE KEYS */;

-- membuang struktur untuk table pbs.tpaket
DROP TABLE IF EXISTS `tpaket`;
CREATE TABLE IF NOT EXISTS `tpaket` (
  `id_paket` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode_paket` varchar(15) NOT NULL,
  `nama_paket` varchar(50) NOT NULL,
  `nama_perusahaan` varchar(50) DEFAULT NULL,
  `nilai_paket` double(24,2) DEFAULT NULL,
  `periode` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id_paket`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tpaket: ~3 rows (lebih kurang)
/*!40000 ALTER TABLE `tpaket` DISABLE KEYS */;
INSERT INTO `tpaket` (`id_paket`, `kode_paket`, `nama_paket`, `nama_perusahaan`, `nilai_paket`, `periode`) VALUES
	(1, 'BBS-00001', 'Paket Bebas', NULL, NULL, NULL),
	(2, 'PBS-00002', 'Paket Berkah Sukacita', 'Yayasan Duta Bahagia Bersama', 3600000.00, '3 T'),
	(3, 'PC-00003', 'Paket Ceria', 'Yayasan Ceria', 2500000.00, '15 H');
/*!40000 ALTER TABLE `tpaket` ENABLE KEYS */;

-- membuang struktur untuk table pbs.tpaket1
DROP TABLE IF EXISTS `tpaket1`;
CREATE TABLE IF NOT EXISTS `tpaket1` (
  `id_paket` int(10) unsigned NOT NULL,
  `id_bank` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_paket`,`id_bank`),
  KEY `id_bank#tpaket-tbank` (`id_bank`) USING BTREE,
  CONSTRAINT `id_bank#tpaket-tbank` FOREIGN KEY (`id_bank`) REFERENCES `tbank` (`id_bank`) ON UPDATE CASCADE,
  CONSTRAINT `id_paket#tpaket-tpaket` FOREIGN KEY (`id_paket`) REFERENCES `tpaket` (`id_paket`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tpaket1: ~3 rows (lebih kurang)
/*!40000 ALTER TABLE `tpaket1` DISABLE KEYS */;
INSERT INTO `tpaket1` (`id_paket`, `id_bank`) VALUES
	(1, 4),
	(2, 2),
	(2, 3);
/*!40000 ALTER TABLE `tpaket1` ENABLE KEYS */;

-- membuang struktur untuk table pbs.tpaket_sumbangan
DROP TABLE IF EXISTS `tpaket_sumbangan`;
CREATE TABLE IF NOT EXISTS `tpaket_sumbangan` (
  `id_paket_sumbangan` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_donatur` int(10) unsigned NOT NULL,
  `id_kolektor` int(10) unsigned NOT NULL,
  `id_paket` int(10) unsigned NOT NULL,
  `jumlah_paket` int(10) unsigned NOT NULL,
  `ket` varchar(100) DEFAULT NULL,
  `total_donasi` double(24,2) NOT NULL DEFAULT 0.00,
  `tgl_jatuh_tempo` date DEFAULT NULL,
  `sta` tinyint(4) NOT NULL DEFAULT 0,
  `tgl_input` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_souvenir2` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_paket_sumbangan`) USING BTREE,
  KEY `id_kolektor#tpaket_sumbangan-tkolektor` (`id_kolektor`),
  KEY `id_paket#tpaket_sumbangan-tpaket` (`id_paket`),
  KEY `id_souvenir2#tpaket_sumbangan-tsouvenir2` (`id_souvenir2`),
  KEY `id_donatur#tpaket_sumbangan-tdonatur` (`id_donatur`) USING BTREE,
  CONSTRAINT `id_donatur#tpaket_sumbangan-tdonatur` FOREIGN KEY (`id_donatur`) REFERENCES `tdonatur` (`id_donatur`) ON UPDATE CASCADE,
  CONSTRAINT `id_kolektor#tpaket_sumbangan-tkolektor` FOREIGN KEY (`id_kolektor`) REFERENCES `tkolektor` (`id_kolektor`) ON UPDATE CASCADE,
  CONSTRAINT `id_paket#tpaket_sumbangan-tpaket` FOREIGN KEY (`id_paket`) REFERENCES `tpaket` (`id_paket`) ON UPDATE CASCADE,
  CONSTRAINT `id_souvenir2#tpaket_sumbangan-tsouvenir2` FOREIGN KEY (`id_souvenir2`) REFERENCES `tsouvenir2` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tpaket_sumbangan: ~7 rows (lebih kurang)
/*!40000 ALTER TABLE `tpaket_sumbangan` DISABLE KEYS */;
INSERT INTO `tpaket_sumbangan` (`id_paket_sumbangan`, `id_donatur`, `id_kolektor`, `id_paket`, `jumlah_paket`, `ket`, `total_donasi`, `tgl_jatuh_tempo`, `sta`, `tgl_input`, `id_souvenir2`) VALUES
	(1, 1, 3, 2, 1, NULL, 3600000.00, '2023-03-03', 0, '2020-04-03 00:35:33', NULL),
	(2, 2, 2, 2, 1, NULL, 1200000.00, '2023-03-04', 0, '2020-04-04 18:15:07', NULL),
	(3, 2, 2, 3, 1, NULL, 1500000.00, '2020-03-19', 0, '2020-04-04 18:30:37', NULL),
	(4, 3, 1, 2, 3, NULL, 10800000.00, '2023-05-04', 0, '2020-06-04 17:51:47', 1),
	(5, 3, 2, 3, 1, NULL, 2500000.00, '2020-05-20', 0, '2020-06-05 09:35:18', 2),
	(6, 3, 1, 1, 2, NULL, 3250000.00, NULL, 0, '2020-06-06 19:47:26', 3),
	(7, 2, 2, 3, 3, NULL, 2500000.00, '2020-05-28', 0, '2020-06-13 09:15:34', NULL);
/*!40000 ALTER TABLE `tpaket_sumbangan` ENABLE KEYS */;

-- membuang struktur untuk table pbs.tpaket_sumbangan1
DROP TABLE IF EXISTS `tpaket_sumbangan1`;
CREATE TABLE IF NOT EXISTS `tpaket_sumbangan1` (
  `id_paket_sumbangan` int(10) unsigned NOT NULL,
  `nmr` smallint(5) unsigned NOT NULL,
  `biaowen` varchar(100) NOT NULL,
  PRIMARY KEY (`id_paket_sumbangan`,`nmr`) USING BTREE,
  CONSTRAINT `id_paket_sumbangan#tpaket_sumbangan1-tpaket_sumbangan` FOREIGN KEY (`id_paket_sumbangan`) REFERENCES `tpaket_sumbangan` (`id_paket_sumbangan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tpaket_sumbangan1: ~20 rows (lebih kurang)
/*!40000 ALTER TABLE `tpaket_sumbangan1` DISABLE KEYS */;
INSERT INTO `tpaket_sumbangan1` (`id_paket_sumbangan`, `nmr`, `biaowen`) VALUES
	(1, 1, 'Biaowen D'),
	(1, 2, 'Biaowen E'),
	(1, 3, 'Biaowen F'),
	(1, 4, 'Biaowen G'),
	(1, 5, 'Biaowen H'),
	(1, 6, 'Biaowen J'),
	(2, 1, 'Biaowen X'),
	(2, 2, 'Biaowen Y'),
	(2, 3, 'Biaowen Z'),
	(3, 1, 'Biaowen O'),
	(3, 2, 'Biaowen P'),
	(3, 3, 'Biaowen Q'),
	(4, 1, 'Biaowen A'),
	(4, 2, 'Biaowen B'),
	(4, 3, 'Biaowen C'),
	(5, 1, 'Biaowen G'),
	(5, 2, 'Biaowen H'),
	(6, 1, 'BB'),
	(7, 1, 'Biaowen YI'),
	(7, 2, 'Biaowen HT');
/*!40000 ALTER TABLE `tpaket_sumbangan1` ENABLE KEYS */;

-- membuang struktur untuk table pbs.tsatuan
DROP TABLE IF EXISTS `tsatuan`;
CREATE TABLE IF NOT EXISTS `tsatuan` (
  `nama` varchar(10) NOT NULL,
  PRIMARY KEY (`nama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tsatuan: ~3 rows (lebih kurang)
/*!40000 ALTER TABLE `tsatuan` DISABLE KEYS */;
INSERT INTO `tsatuan` (`nama`) VALUES
	('Karung'),
	('Kg'),
	('Pcs');
/*!40000 ALTER TABLE `tsatuan` ENABLE KEYS */;

-- membuang struktur untuk table pbs.tsouvenir
DROP TABLE IF EXISTS `tsouvenir`;
CREATE TABLE IF NOT EXISTS `tsouvenir` (
  `id_souvenir` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode_souvenir` varchar(15) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `stok_awal` double(24,2) NOT NULL DEFAULT 0.00,
  `jenis` varchar(20) DEFAULT NULL,
  `satuan` varchar(10) NOT NULL,
  `ket` varchar(100) DEFAULT NULL,
  `sta` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_souvenir`) USING BTREE,
  KEY `jenis#tsouvenir-tjenis_barang` (`jenis`) USING BTREE,
  KEY `satuan#tsouvenir-tsatuan` (`satuan`) USING BTREE,
  CONSTRAINT `jenis#tsouvenir-tjenis_souvenir` FOREIGN KEY (`jenis`) REFERENCES `tjenis_souvenir` (`nama`) ON UPDATE CASCADE,
  CONSTRAINT `satuan#tsouvenir-tsatuan` FOREIGN KEY (`satuan`) REFERENCES `tsatuan` (`nama`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tsouvenir: ~2 rows (lebih kurang)
/*!40000 ALTER TABLE `tsouvenir` DISABLE KEYS */;
INSERT INTO `tsouvenir` (`id_souvenir`, `kode_souvenir`, `nama`, `stok_awal`, `jenis`, `satuan`, `ket`, `sta`) VALUES
	(1, 'MKP-00001', 'MKP Lang 210ml', 24.00, 'Minyak Kayu Putih', 'Pcs', NULL, 0),
	(2, 'KAL-00003', 'Kalung Muzi (PBS)', 10.00, 'Kalung', 'Pcs', NULL, 0);
/*!40000 ALTER TABLE `tsouvenir` ENABLE KEYS */;

-- membuang struktur untuk table pbs.tsouvenir1
DROP TABLE IF EXISTS `tsouvenir1`;
CREATE TABLE IF NOT EXISTS `tsouvenir1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_souvenir` int(10) unsigned NOT NULL,
  `stok_masuk` double(24,2) NOT NULL DEFAULT 0.00,
  `ket` varchar(100) DEFAULT NULL,
  `tgl_input` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_souvenir#tsouvenir1-tsouvenir` (`id_souvenir`) USING BTREE,
  CONSTRAINT `id_souvenir#tsouvenir1-tsouvenir` FOREIGN KEY (`id_souvenir`) REFERENCES `tsouvenir` (`id_souvenir`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tsouvenir1: ~0 rows (lebih kurang)
/*!40000 ALTER TABLE `tsouvenir1` DISABLE KEYS */;
INSERT INTO `tsouvenir1` (`id`, `id_souvenir`, `stok_masuk`, `ket`, `tgl_input`) VALUES
	(1, 1, 5.00, NULL, '2020-06-06 19:02:38');
/*!40000 ALTER TABLE `tsouvenir1` ENABLE KEYS */;

-- membuang struktur untuk table pbs.tsouvenir2
DROP TABLE IF EXISTS `tsouvenir2`;
CREATE TABLE IF NOT EXISTS `tsouvenir2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_paket_sumbangan` int(10) unsigned NOT NULL,
  `id_souvenir` int(10) unsigned NOT NULL,
  `penerima_souvenir` varchar(100) NOT NULL,
  `stok_keluar` int(11) NOT NULL,
  `tgl_serah` date NOT NULL,
  `ket` varchar(100) DEFAULT NULL,
  `tgl_input` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_souvenir#tsouvenir2-tsouvenir` (`id_souvenir`) USING BTREE,
  KEY `id_paket_sumbangan#tsouvenir2-tpaket_sumbangan` (`id_paket_sumbangan`),
  CONSTRAINT `id_paket_sumbangan#tsouvenir2-tpaket_sumbangan` FOREIGN KEY (`id_paket_sumbangan`) REFERENCES `tpaket_sumbangan` (`id_paket_sumbangan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_souvenir#tsouvenir2-tsouvenir` FOREIGN KEY (`id_souvenir`) REFERENCES `tsouvenir` (`id_souvenir`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tsouvenir2: ~3 rows (lebih kurang)
/*!40000 ALTER TABLE `tsouvenir2` DISABLE KEYS */;
INSERT INTO `tsouvenir2` (`id`, `id_paket_sumbangan`, `id_souvenir`, `penerima_souvenir`, `stok_keluar`, `tgl_serah`, `ket`, `tgl_input`) VALUES
	(1, 4, 2, 'Mr. X', 5, '2020-06-06', NULL, '2020-06-16 15:47:53'),
	(2, 5, 1, 'Mr. X', 9, '2020-06-06', NULL, '2020-06-06 19:37:42'),
	(3, 6, 1, 'Mr. X', 5, '2020-06-06', NULL, '2020-06-06 19:47:57');
/*!40000 ALTER TABLE `tsouvenir2` ENABLE KEYS */;

-- membuang struktur untuk table pbs.tsumbangan
DROP TABLE IF EXISTS `tsumbangan`;
CREATE TABLE IF NOT EXISTS `tsumbangan` (
  `id_sumbangan` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `no_kwitansi` varchar(50) NOT NULL,
  `id_paket_sumbangan` int(10) unsigned NOT NULL,
  `nama_penyumbang` varchar(100) NOT NULL,
  `tgl_donasi` date NOT NULL DEFAULT curdate(),
  `jumlah_donasi` double(24,2) NOT NULL,
  `metode_pembayaran` varchar(10) NOT NULL,
  `id_bank` int(10) unsigned DEFAULT NULL,
  `rek_pengirim` varchar(100) DEFAULT NULL,
  `ket` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_sumbangan`),
  KEY `id_paket_sumbangan#tsumbangan-tpaket_sumbangan` (`id_paket_sumbangan`) USING BTREE,
  KEY `id_bank#tsumbangan-tpaket1` (`id_bank`) USING BTREE,
  CONSTRAINT `id_bank#tsumbangan-tpaket1` FOREIGN KEY (`id_bank`) REFERENCES `tpaket1` (`id_bank`) ON UPDATE CASCADE,
  CONSTRAINT `id_paket_sumbangan#tsumbangan-tpaket_sumbangan` FOREIGN KEY (`id_paket_sumbangan`) REFERENCES `tpaket_sumbangan` (`id_paket_sumbangan`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tsumbangan: ~12 rows (lebih kurang)
/*!40000 ALTER TABLE `tsumbangan` DISABLE KEYS */;
INSERT INTO `tsumbangan` (`id_sumbangan`, `no_kwitansi`, `id_paket_sumbangan`, `nama_penyumbang`, `tgl_donasi`, `jumlah_donasi`, `metode_pembayaran`, `id_bank`, `rek_pengirim`, `ket`) VALUES
	(1, 'PBS-00001', 1, 'Mr. Y', '2020-04-07', 500000.00, 'Transfer', 2, NULL, 'Ket Y'),
	(2, 'PBS-00002', 1, 'Mr. Y', '2020-04-06', 1500000.00, 'Tunai', NULL, NULL, NULL),
	(3, 'PBS-000003', 4, 'Mr. X', '2020-06-04', 2500000.00, 'Transfer', NULL, NULL, NULL),
	(4, 'PBS-000004', 4, 'Mr. X', '2020-06-05', 8300000.00, 'Tunai', NULL, NULL, NULL),
	(5, 'PC-000005', 5, 'Mr. X', '2020-06-06', 2500000.00, 'Tunai', NULL, NULL, NULL),
	(6, 'BBS-000006', 6, 'Mr. X', '2020-06-06', 2500000.00, 'Tunai', NULL, NULL, NULL),
	(7, 'PBS-000009', 1, 'Mr. Y', '2020-04-06', 600000.00, 'Tunai', NULL, NULL, NULL),
	(8, 'PBS-000008', 1, 'Mr. Y', '2020-05-13', 1000000.00, 'Transfer', 2, NULL, NULL),
	(9, 'BBS-000009', 6, 'Mr. X', '2020-06-15', 750000.00, 'Transfer', 4, NULL, NULL),
	(10, 'PC-000010', 3, 'Mr. Z', '2020-06-13', 1500000.00, 'Tunai', NULL, NULL, NULL),
	(11, 'PC-000011', 7, 'Mr. Z', '2020-06-17', 2500000.00, 'Tunai', NULL, NULL, NULL),
	(12, 'PBS-000012', 2, 'Mr. Z', '2020-05-15', 1200000.00, 'Transfer', 3, NULL, NULL);
/*!40000 ALTER TABLE `tsumbangan` ENABLE KEYS */;

-- membuang struktur untuk table pbs.tsumbangan1
DROP TABLE IF EXISTS `tsumbangan1`;
CREATE TABLE IF NOT EXISTS `tsumbangan1` (
  `id_sumbangan` int(10) unsigned NOT NULL,
  `nmr` smallint(5) unsigned NOT NULL,
  `biaowen` varchar(100) NOT NULL,
  `lunas` tinyint(4) NOT NULL DEFAULT 0,
  `bakar` tinyint(4) NOT NULL DEFAULT 0,
  `tgl_bakar` datetime DEFAULT NULL,
  `ket` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_sumbangan`,`nmr`) USING BTREE,
  CONSTRAINT `id_sumbangan#tsumbangan1-tsumbangan` FOREIGN KEY (`id_sumbangan`) REFERENCES `tsumbangan` (`id_sumbangan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tsumbangan1: ~30 rows (lebih kurang)
/*!40000 ALTER TABLE `tsumbangan1` DISABLE KEYS */;
INSERT INTO `tsumbangan1` (`id_sumbangan`, `nmr`, `biaowen`, `lunas`, `bakar`, `tgl_bakar`, `ket`) VALUES
	(1, 1, 'Biaowen D', 0, 0, NULL, NULL),
	(1, 2, 'Biaowen I', 1, 0, NULL, NULL),
	(2, 1, 'Biaowen D', 1, 1, '2020-06-13 15:24:22', NULL),
	(2, 2, 'Biaowen E', 1, 1, '2020-06-13 15:24:22', NULL),
	(2, 3, 'Biaowen F', 1, 0, NULL, NULL),
	(2, 4, 'Biaowen G', 0, 0, NULL, NULL),
	(3, 1, 'Biaowen A', 0, 0, NULL, NULL),
	(3, 2, 'Biaowen B', 0, 0, NULL, NULL),
	(3, 3, 'Biaowen C', 0, 0, NULL, NULL),
	(3, 4, 'Biaowen D', 0, 0, NULL, NULL),
	(4, 1, 'Biaowen A', 1, 0, NULL, NULL),
	(4, 2, 'Biaowen B', 1, 0, NULL, NULL),
	(4, 3, 'Biaowen C', 1, 0, NULL, NULL),
	(4, 4, 'Biaowen D', 1, 0, NULL, NULL),
	(5, 1, 'Biaowen G', 1, 0, NULL, NULL),
	(5, 2, 'Biaowen H', 1, 0, NULL, NULL),
	(6, 1, 'BB', 0, 0, NULL, NULL),
	(7, 1, 'Biaowen G', 0, 0, NULL, NULL),
	(7, 2, 'Biaowen H', 0, 0, NULL, NULL),
	(8, 1, 'Biaowen G', 1, 0, NULL, NULL),
	(8, 2, 'Biaowen H', 1, 0, NULL, NULL),
	(9, 1, 'BB', 0, 0, NULL, NULL),
	(10, 1, 'Biaowen O', 1, 0, NULL, NULL),
	(10, 2, 'Biaowen P', 0, 0, NULL, NULL),
	(10, 3, 'Biaowen Q', 0, 0, NULL, NULL),
	(11, 1, 'Biaowen YI', 0, 0, NULL, NULL),
	(11, 2, 'Biaowen HT', 0, 0, NULL, NULL),
	(12, 1, 'Biaowen X', 0, 0, NULL, NULL),
	(12, 2, 'Biaowen Y', 0, 0, NULL, NULL),
	(12, 3, 'Biaowen Z', 0, 0, NULL, NULL);
/*!40000 ALTER TABLE `tsumbangan1` ENABLE KEYS */;

-- membuang struktur untuk table pbs.tuser
DROP TABLE IF EXISTS `tuser`;
CREATE TABLE IF NOT EXISTS `tuser` (
  `username` varchar(20) NOT NULL,
  `pass` varchar(30) NOT NULL,
  `lev` tinyint(4) NOT NULL DEFAULT 0,
  `sta` tinyint(4) NOT NULL DEFAULT 0,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Membuang data untuk tabel pbs.tuser: ~2 rows (lebih kurang)
/*!40000 ALTER TABLE `tuser` DISABLE KEYS */;
INSERT INTO `tuser` (`username`, `pass`, `lev`, `sta`, `no_hp`, `email`) VALUES
	('admin', '123', 1, 0, NULL, NULL),
	('jack', '123', 0, 0, NULL, NULL);
/*!40000 ALTER TABLE `tuser` ENABLE KEYS */;

-- membuang struktur untuk procedure pbs.is_exist_jenis_souvenir
DROP PROCEDURE IF EXISTS `is_exist_jenis_souvenir`;
DELIMITER //
CREATE PROCEDURE `is_exist_jenis_souvenir`(
	IN `nama1` VARCHAR(20)
)
BEGIN
IF nama1 IS NOT NULL AND NOT EXISTS(SELECT nama FROM tjenis_souvenir WHERE nama = nama1) THEN
	INSERT INTO tjenis_souvenir(nama) VALUES(nama1);
END IF;
END//
DELIMITER ;

-- membuang struktur untuk procedure pbs.is_exist_kota
DROP PROCEDURE IF EXISTS `is_exist_kota`;
DELIMITER //
CREATE PROCEDURE `is_exist_kota`(
	IN `nama1` VARCHAR(50)
)
BEGIN
IF nama1 IS NOT NULL AND NOT EXISTS (SELECT nama FROM tkota WHERE nama = nama1) THEN
	INSERT INTO tkota(nama) VALUES(nama1);
END IF;
END//
DELIMITER ;

-- membuang struktur untuk procedure pbs.is_exist_satuan
DROP PROCEDURE IF EXISTS `is_exist_satuan`;
DELIMITER //
CREATE PROCEDURE `is_exist_satuan`(
	IN `nama1` VARCHAR(10)
)
BEGIN
IF NOT EXISTS(SELECT nama FROM tsatuan WHERE nama = nama1) THEN
	INSERT INTO tsatuan(nama) VALUES(nama1);
END IF;
END//
DELIMITER ;

-- membuang struktur untuk procedure pbs.save_bank
DROP PROCEDURE IF EXISTS `save_bank`;
DELIMITER //
CREATE PROCEDURE `save_bank`(
	IN `id_bank1` INT,
	IN `nama_bank1` VARCHAR(50),
	IN `an1` VARCHAR(50),
	IN `no_rek1` VARCHAR(20)
)
BEGIN
IF NOT EXISTS(SELECT id_bank FROM tbank WHERE id_bank = id_bank1) THEN
	INSERT INTO tbank(id_bank, nama_bank, an, no_rek)
	VALUES(id_bank1, nama_bank1, an1, no_rek1);
ELSE 
	UPDATE tbank SET 
	nama_bank = nama_bank1, 
	an = an1, 
	no_rek = no_rek1
	WHERE id_bank = id_bank1;
END IF;
END//
DELIMITER ;

-- membuang struktur untuk procedure pbs.save_donatur
DROP PROCEDURE IF EXISTS `save_donatur`;
DELIMITER //
CREATE PROCEDURE `save_donatur`(
	IN `id_donatur1` INT,
	IN `kode_donatur1` VARCHAR(15),
	IN `nama_id1` VARCHAR(100),
	IN `nama_cn1` VARCHAR(100),
	IN `alamat1` VARCHAR(150),
	IN `kota_lahir1` VARCHAR(50),
	IN `tgl_lahir1` DATE,
	IN `kota_domisili1` VARCHAR(50),
	IN `no_hp11` VARCHAR(20),
	IN `no_hp21` VARCHAR(20),
	IN `email1` VARCHAR(50),
	IN `ket1` VARCHAR(100),
	IN `tgl_gabung1` DATE
)
    NO SQL
BEGIN
CALL is_exist_kota(kota_lahir1);
CALL is_exist_kota(kota_domisili1);

IF id_donatur1 IS NULL OR NOT EXISTS(SELECT id_donatur FROM tdonatur WHERE id_donatur = id_donatur1) THEN
	IF kode_donatur1 IS NULL THEN	
		SET kode_donatur1 = IFNULL((SELECT MAX(SUBSTRING_INDEX(kode_donatur, '-', -1)) + 1 FROM tdonatur), 1);
		SET kode_donatur1 = CONCAT('D', '-', kode_donatur1);
	END IF;

	INSERT INTO tdonatur(id_donatur, kode_donatur, nama_id, nama_cn, ket, kota_lahir, tgl_lahir, alamat, kota_domisili, no_hp1, no_hp2, email, tgl_gabung)
	VALUES(id_donatur1, format_kode(kode_donatur1, 6), nama_id1, nama_cn1, ket1, kota_lahir1, tgl_lahir1, alamat1, kota_domisili1, no_hp11, no_hp21, email1, tgl_gabung1);
ELSE 
	UPDATE tdonatur SET 
	kode_donatur = kode_donatur1,
	nama_id = nama_id1,
	nama_cn = nama_cn1, 
	alamat = alamat1,
	kota_lahir = kota_lahir1,
	tgl_lahir = tgl_lahir1,
	kota_domisili = kota_domisili1,
	no_hp1 = no_hp11,
	no_hp2 = no_hp21,
	email = email1,
	ket = ket1, 
	tgl_gabung = tgl_gabung1
	WHERE id_donatur = id_donatur1;
END IF;
END//
DELIMITER ;

-- membuang struktur untuk procedure pbs.save_kolektor
DROP PROCEDURE IF EXISTS `save_kolektor`;
DELIMITER //
CREATE PROCEDURE `save_kolektor`(
	IN `id_kolektor1` INT,
	IN `kode_kolektor1` VARCHAR(15),
	IN `nama1` VARCHAR(50),
	IN `no_hp11` VARCHAR(20),
	IN `no_hp21` VARCHAR(20),
	IN `email1` VARCHAR(50),
	IN `ket1` VARCHAR(100)
)
BEGIN

IF id_kolektor1 IS NULL OR NOT EXISTS(SELECT id_kolektor FROM tkolektor WHERE id_kolektor = id_kolektor1) THEN
	IF kode_kolektor1 IS NULL THEN	
		SET kode_kolektor1 = IFNULL((SELECT MAX(SUBSTRING_INDEX(kode_kolektor, '-', -1)) + 1 FROM tkolektor), 1);
		SET kode_kolektor1 = CONCAT('K', '-', kode_kolektor1);
	END IF;
	
	INSERT INTO tkolektor(id_kolektor, kode_kolektor, nama, no_hp1, no_hp2, email, ket)
	VALUES(id_kolektor1, format_kode(kode_kolektor1, 3), nama1, no_hp11, no_hp21, email1, ket1);
ELSE 
	UPDATE tkolektor SET 
	kode_kolektor = kode_kolektor1,
	nama = nama1,
	no_hp1 = no_hp11,
	no_hp2 = no_hp21,
	email = email1,
	ket = ket1
	WHERE id_kolektor = id_kolektor1;
END IF;

END//
DELIMITER ;

-- membuang struktur untuk procedure pbs.save_paket
DROP PROCEDURE IF EXISTS `save_paket`;
DELIMITER //
CREATE PROCEDURE `save_paket`(
	INOUT `id_paket1` INT,
	IN `nama_perusahaan1` VARCHAR(50),
	IN `kode_paket1` VARCHAR(15),
	IN `nama_paket1` VARCHAR(50),
	IN `nilai_paket1` DOUBLE(24,2),
	IN `periode1` VARCHAR(5)
)
BEGIN
IF id_paket1 IS NULL OR NOT EXISTS(SELECT id_paket FROM tpaket WHERE id_paket = id_paket1) THEN
	IF kode_paket1 IS NULL THEN
		SET kode_paket1 = IFNULL((SELECT MAX(SUBSTRING_INDEX(kode_paket, '-', -1)) + 1 FROM tpaket), 1);
		SET kode_paket1 = CONCAT(get_abbreviation(nama_paket1), '-', kode_paket1);
	END IF;
	
	INSERT INTO tpaket(id_paket, nama_perusahaan, kode_paket, nama_paket, nilai_paket, periode) 
	VALUES(id_paket1, nama_perusahaan1, format_kode(kode_paket1, 5), nama_paket1, nilai_paket1, periode1);
	
	IF id_paket1 IS NULL THEN
		SET id_paket1 = (SELECT MAX(id_paket) FROM tpaket);	
	END IF;
ELSE
	UPDATE tpaket SET
	nama_perusahaan = nama_perusahaan1,
	kode_paket = kode_paket1,
	nama_paket = nama_paket1,
	nilai_paket = nilai_paket1,
	periode = periode1
	WHERE id_paket = id_paket1;
END IF;	
END//
DELIMITER ;

-- membuang struktur untuk procedure pbs.save_paket_sumbangan
DROP PROCEDURE IF EXISTS `save_paket_sumbangan`;
DELIMITER //
CREATE PROCEDURE `save_paket_sumbangan`(
	INOUT `id_paket_sumbangan1` INT,
	IN `id_donatur1` INT,
	IN `id_kolektor1` INT,
	IN `id_paket1` INT,
	IN `jumlah_paket1` INT,
	IN `ket1` VARCHAR(100),
	IN `tgl_jatuh_tempo1` DATE
)
BEGIN
IF id_paket_sumbangan1 IS NULL OR NOT EXISTS(SELECT id_paket_sumbangan FROM tpaket_sumbangan WHERE id_paket_sumbangan = id_paket_sumbangan1) THEN
	INSERT INTO tpaket_sumbangan(id_paket_sumbangan, id_donatur, id_kolektor, id_paket, jumlah_paket, ket, tgl_jatuh_tempo)
	VALUES(id_paket_sumbangan1, id_donatur1, id_kolektor1, id_paket1, jumlah_paket1, ket1, tgl_jatuh_tempo1);
	
	IF id_paket_sumbangan1 IS NULL THEN
		SET id_paket_sumbangan1 = (SELECT MAX(id_paket_sumbangan) FROM tpaket_sumbangan);
	END IF;
ELSE
	UPDATE tpaket_sumbangan SET
	id_donatur = id_donatur1,
	id_kolektor = id_kolektor1,
	id_paket = id_paket1,
	jumlah_paket = jumlah_paket1,
	ket = ket1,
	tgl_jatuh_tempo = tgl_jatuh_tempo1
	WHERE id_paket_sumbangan = id_paket_sumbangan1;
END IF;
END//
DELIMITER ;

-- membuang struktur untuk procedure pbs.save_paket_sumbangan1
DROP PROCEDURE IF EXISTS `save_paket_sumbangan1`;
DELIMITER //
CREATE PROCEDURE `save_paket_sumbangan1`(
	IN `id_paket_sumbangan1` INT,
	IN `nmr1` TINYINT,
	IN `biaowen1` VARCHAR(50)
)
BEGIN
IF NOT EXISTS(SELECT biaowen FROM tpaket_sumbangan1 WHERE id_paket_sumbangan = id_paket_sumbangan1 AND nmr = nmr1) THEN
	INSERT INTO tpaket_sumbangan1(id_paket_sumbangan, nmr, biaowen) VALUES(id_paket_sumbangan1, nmr1, biaowen1);
ELSE 
	UPDATE tpaket_sumbangan1 SET biaowen = biaowen1 WHERE id_paket_sumbangan = id_paket_sumbangan1 AND nmr = nmr1;
END IF;
END//
DELIMITER ;

-- membuang struktur untuk procedure pbs.save_souvenir
DROP PROCEDURE IF EXISTS `save_souvenir`;
DELIMITER //
CREATE PROCEDURE `save_souvenir`(
	IN `id_souvenir1` INT,
	IN `kode_souvenir1` VARCHAR(15),
	IN `nama1` VARCHAR(50),
	IN `stok_awal1` DOUBLE(24,2),
	IN `jenis1` VARCHAR(20),
	IN `satuan1` VARCHAR(10),
	IN `ket1` VARCHAR(100)
)
BEGIN
CALL is_exist_jenis_souvenir(jenis1);
CALL is_exist_satuan(satuan1);

IF id_souvenir1 IS NULL OR NOT EXISTS(SELECT id_souvenir FROM tsouvenir WHERE id_souvenir = id_souvenir1) THEN
	IF kode_souvenir1 IS NULL THEN	
		SET kode_souvenir1 = IFNULL((SELECT MAX(SUBSTRING_INDEX(kode_souvenir, '-', -1)) + 1 FROM tsouvenir), 1);
		SET kode_souvenir1 = CONCAT(get_abbreviation(jenis1), '-', kode_souvenir1);
	END IF;
	
	INSERT INTO tsouvenir(id_souvenir, kode_souvenir, nama, stok_awal, jenis, satuan, ket)
	VALUES(id_souvenir1, format_kode(kode_souvenir1, 5), nama1, stok_awal1, jenis1, satuan1, ket1);
ELSE 
	UPDATE tsouvenir SET
	kode_souvenir = kode_souvenir1,
	nama = nama1,
	stok_awal = stok_awal1,
	jenis = jenis1,
	satuan = satuan1,
	ket = ket1
	WHERE id_souvenir = id_souvenir1;
END IF;
END//
DELIMITER ;

-- membuang struktur untuk procedure pbs.save_souvenir1
DROP PROCEDURE IF EXISTS `save_souvenir1`;
DELIMITER //
CREATE PROCEDURE `save_souvenir1`(
	IN `id1` INT,
	IN `id_souvenir1` INT,
	IN `stok_masuk1` DOUBLE(24,2),
	IN `ket1` VARCHAR(100)
)
BEGIN
IF NOT EXISTS(SELECT id FROM tsouvenir1 WHERE id = id1) THEN
	INSERT INTO tsouvenir1(id_souvenir, stok_masuk, ket) VALUES(id_souvenir1, stok_masuk1, ket1);
ELSE 
	UPDATE tsouvenir1 SET
	id_souvenir = id_souvenir1,
	stok_masuk = stok_masuk1,
	ket = ket1
	WHERE id = id1;
END IF;
END//
DELIMITER ;

-- membuang struktur untuk procedure pbs.save_souvenir2
DROP PROCEDURE IF EXISTS `save_souvenir2`;
DELIMITER //
CREATE PROCEDURE `save_souvenir2`(
	IN `id1` INT,
	IN `id_paket_sumbangan1` INT,
	IN `id_souvenir1` INT,
	IN `penerima_souvenir1` VARCHAR(50),
	IN `stok_keluar1` INT,
	IN `tgl_serah1` DATE,
	IN `ket1` VARCHAR(100)
)
BEGIN
IF NOT EXISTS(SELECT id FROM tsouvenir2 WHERE id = id1) THEN
	START TRANSACTION;
	INSERT INTO tsouvenir2(id, id_paket_sumbangan, id_souvenir, penerima_souvenir, stok_keluar, tgl_serah, ket)
	VALUES(id1, id_paket_sumbangan1, id_souvenir1, penerima_souvenir1, stok_keluar1, tgl_serah1, ket1);
	
	UPDATE tpaket_sumbangan SET id_souvenir2 = (SELECT MAX(id) FROM tsouvenir2) WHERE id_paket_sumbangan = id_paket_sumbangan1;
	COMMIT;
ELSE
	UPDATE tsouvenir2 SET
	id_paket_sumbangan = id_paket_sumbangan1,
	id_souvenir = id_souvenir1,
	penerima_souvenir = penerima_souvenir1,
	stok_keluar = stok_keluar1,
	tgl_serah = tgl_serah1,
	ket = ket1
	WHERE id = id1;
END IF;
END//
DELIMITER ;

-- membuang struktur untuk procedure pbs.save_sumbangan
DROP PROCEDURE IF EXISTS `save_sumbangan`;
DELIMITER //
CREATE PROCEDURE `save_sumbangan`(
	INOUT `id_sumbangan1` INT,
	IN `no_kwitansi1` VARCHAR(15),
	IN `id_paket_sumbangan1` INT,
	IN `nama_penyumbang1` VARCHAR(100),
	IN `tgl_donasi1` DATE,
	IN `jumlah_donasi1` DOUBLE(24,2),
	IN `metode_pembayaran1` VARCHAR(10),
	IN `id_bank1` INT,
	IN `rek_pengirim1` VARCHAR(100),
	IN `ket1` VARCHAR(100)
)
BEGIN
IF id_sumbangan1 IS NULL OR NOT EXISTS(SELECT id_sumbangan FROM tsumbangan WHERE id_sumbangan = id_sumbangan1) THEN
	IF no_kwitansi1 IS NULL THEN
		SET no_kwitansi1 = (
			SELECT SUBSTRING_INDEX(tp.kode_paket, '-', 1) 
			FROM tpaket_sumbangan tps
			JOIN tpaket tp ON tps.id_paket = tp.id_paket
			WHERE tps.id_paket_sumbangan = id_paket_sumbangan1
		);
		SET no_kwitansi1 = format_kode(CONCAT(no_kwitansi1, '-', (SELECT IFNULL(MAX(id_sumbangan) + 1, 1) FROM tsumbangan)), 6);
	END IF;

	INSERT INTO tsumbangan(id_sumbangan, no_kwitansi, id_paket_sumbangan, nama_penyumbang, tgl_donasi, jumlah_donasi, metode_pembayaran, id_bank, rek_pengirim, ket)
	VALUES(id_sumbangan1, no_kwitansi1, id_paket_sumbangan1, nama_penyumbang1, tgl_donasi1, jumlah_donasi1, metode_pembayaran1, id_bank1, rek_pengirim1, ket1);
	
	SELECT MAX(id_sumbangan) INTO id_sumbangan1 FROM tsumbangan;
ELSE
	IF no_kwitansi1 IS NULL THEN
		SET no_kwitansi1 = (
			SELECT SUBSTRING_INDEX(tp.kode_paket, '-', 1) 
			FROM tpaket_sumbangan tps
			JOIN tpaket tp ON tps.id_paket = tp.id_paket
			WHERE tps.id_paket_sumbangan = id_paket_sumbangan1
		); 
		SET no_kwitansi1 = format_kode(CONCAT(no_kwitansi1, '-', id_sumbangan1), 6);
	END IF;

	UPDATE tsumbangan SET 
	no_kwitansi = no_kwitansi1,
	id_paket_sumbangan = id_paket_sumbangan1,
	nama_penyumbang = nama_penyumbang1,
	tgl_donasi = tgl_donasi1,
	jumlah_donasi = jumlah_donasi1,
	metode_pembayaran = metode_pembayaran1,
	id_bank = id_bank1,
	rek_pengirim = rek_pengirim1,
	ket = ket1
	WHERE id_sumbangan = id_sumbangan1;
END IF;
END//
DELIMITER ;

-- membuang struktur untuk procedure pbs.save_sumbangan1
DROP PROCEDURE IF EXISTS `save_sumbangan1`;
DELIMITER //
CREATE PROCEDURE `save_sumbangan1`(
	IN `id_sumbangan1` INT,
	IN `id_paket_sumbangan1` INT,
	IN `nmr1` TINYINT,
	IN `biaowen1` VARCHAR(100),
	IN `lunas1` TINYINT
)
BEGIN
IF NOT EXISTS(SELECT biaowen FROM tsumbangan1 WHERE id_sumbangan = id_sumbangan1 AND id_paket_sumbangan = id_paket_sumbangan1 AND nmr = nmr1) THEN
	INSERT INTO tsumbangan1 (id_sumbangan, id_paket_sumbangan, nmr, biaowen, lunas) VALUES(id_sumbangan1, id_paket_sumbangan1, nmr1, biaowen1, lunas1);
ELSE 
	UPDATE tsumbangan1 SET biaowen = biaowen1, lunas = lunas1 WHERE id_sumbangan = id_sumbangan1 AND id_paket_sumbangan = id_paket_sumbangan1 AND nmr = nmr1;
END IF;
END//
DELIMITER ;

-- membuang struktur untuk function pbs.format_kode
DROP FUNCTION IF EXISTS `format_kode`;
DELIMITER //
CREATE FUNCTION `format_kode`(`kode` VARCHAR(15),
	`len` INT
) RETURNS varchar(15) CHARSET utf8
    DETERMINISTIC
BEGIN
DECLARE kode1 VARCHAR(3);
DECLARE kode2 VARCHAR(11);

SET kode1 = UPPER(SUBSTRING_INDEX(kode, '-', 1));
SET kode2 = SUBSTRING_INDEX(kode, '-', -1);
SET kode2 = IF(LENGTH(kode2) > len, kode2, RIGHT(CONCAT(REPEAT('0', len), kode2), len)); 

RETURN CONCAT(kode1, '-', kode2);
END//
DELIMITER ;

-- membuang struktur untuk function pbs.format_tanggal
DROP FUNCTION IF EXISTS `format_tanggal`;
DELIMITER //
CREATE FUNCTION `format_tanggal`(`tanggal1` VARCHAR(10)
) RETURNS date
    DETERMINISTIC
BEGIN
SET @tgl = SUBSTRING_INDEX(tanggal1, '/', 1);
SET @bln = SUBSTRING_INDEX(SUBSTRING_INDEX(tanggal1, '/', 2), '/', -1);
SET @thn = SUBSTRING_INDEX(tanggal1, '/', -1);
RETURN CONCAT_WS('-', @thn, @bln, @tgl);
END//
DELIMITER ;

-- membuang struktur untuk function pbs.get_abbreviation
DROP FUNCTION IF EXISTS `get_abbreviation`;
DELIMITER //
CREATE FUNCTION `get_abbreviation`(`nama` VARCHAR(50)
) RETURNS varchar(3) CHARSET utf8
    DETERMINISTIC
BEGIN
DECLARE abbrev VARCHAR(3);
DECLARE len INT;
SET len = LENGTH(nama) - LENGTH(REPLACE(nama, ' ', ''));
IF len > 1 THEN
	SET abbrev = LEFT(SUBSTRING_INDEX(nama, ' ', 1), 1);
	SET abbrev = CONCAT(abbrev, LEFT(SUBSTRING_INDEX(SUBSTRING_INDEX(nama, ' ', 2), ' ', -1), 1));
	SET abbrev = CONCAT(abbrev, LEFT(SUBSTRING_INDEX(SUBSTRING_INDEX(nama, ' ', 3), ' ', -1), 1));
ELSEIF len = 1 THEN
	SET abbrev = LEFT(SUBSTRING_INDEX(nama, ' ', 1), 1);
	SET abbrev = CONCAT(abbrev, LEFT(SUBSTRING_INDEX(SUBSTRING_INDEX(nama, ' ', 2), ' ', -1), 1));
ELSE
	SET abbrev = LEFT(nama, 3);
END IF;
RETURN UPPER(abbrev);
END//
DELIMITER ;

-- membuang struktur untuk trigger pbs.tsumbangan_after_delete
DROP TRIGGER IF EXISTS `tsumbangan_after_delete`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `tsumbangan_after_delete` AFTER DELETE ON `tsumbangan` FOR EACH ROW BEGIN

UPDATE tpaket_sumbangan
SET total_donasi = total_donasi - OLD.jumlah_donasi
WHERE id_paket_sumbangan = OLD.id_paket_sumbangan;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- membuang struktur untuk trigger pbs.tsumbangan_after_insert
DROP TRIGGER IF EXISTS `tsumbangan_after_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `tsumbangan_after_insert` AFTER INSERT ON `tsumbangan` FOR EACH ROW BEGIN

UPDATE tpaket_sumbangan
SET total_donasi = total_donasi + NEW.jumlah_donasi
WHERE id_paket_sumbangan = NEW.id_paket_sumbangan;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- membuang struktur untuk trigger pbs.tsumbangan_after_update
DROP TRIGGER IF EXISTS `tsumbangan_after_update`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `tsumbangan_after_update` AFTER UPDATE ON `tsumbangan` FOR EACH ROW BEGIN

UPDATE tpaket_sumbangan tps 
SET tps.total_donasi = tps.total_donasi - OLD.jumlah_donasi 
WHERE tps.id_paket_sumbangan = OLD.id_paket_sumbangan;

UPDATE tpaket_sumbangan tps
SET tps.total_donasi = tps.total_donasi + NEW.jumlah_donasi
WHERE tps.id_paket_sumbangan = NEW.id_paket_sumbangan;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
