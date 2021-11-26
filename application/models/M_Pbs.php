<?php defined('BASEPATH') OR exit('No direct script access allowed');
class M_Pbs extends CI_Model {
    private $pbs;

    function __construct() {
        parent::__construct();
        $this->pbs = $this->load->database("pbs", TRUE);
    }

    /** laporan harian bulanan */
    function lhb_get() {
        switch (func_get_arg(0)) {
            case 1: // laporan/penerimaan/harian, laporan/penerimaan/bulanan, laporan/paket/harian_detail, laporan/paket/harian_bulanan
                return $this->pbs->query("
                SELECT YEAR(ts.tgl_donasi) AS thn, tb.kode AS bln, tb.nama AS nm_bln
                FROM tsumbangan ts
                JOIN tbulan tb ON MONTH(ts.tgl_donasi) = tb.kode
                GROUP BY YEAR(ts.tgl_donasi), MONTH(ts.tgl_donasi)
                ORDER BY ts.tgl_donasi DESC");
            case 2: // laporan/penerimaan/harian/print_laporan
                $tgl1 = "'" .func_get_arg(1). "'";
                $tgl2 = "'" .func_get_arg(2). "'";
                $id_bank = func_get_arg(3);
                return $this->pbs->query("
                SELECT IFNULL(t0.kode_bank, 'Transfer') AS kode_bank, IFNULL(t1.tgl_terakhir_donatur, '-') AS tgl_terakhir_donatur, 
                IFNULL(t2.tgl_terakhir_paket, '-') AS tgl_terakhir_paket, IFNULL(t3.tgl_terakhir_tunai, '-') AS tgl_terakhir_tunai, 
                IFNULL(t4.tgl_terakhir_transfer, '-') AS tgl_terakhir_transfer, 
                IFNULL(IF(t3.tgl_terakhir_tunai > t4.tgl_terakhir_transfer, t3.tgl_terakhir_tunai, t4.tgl_terakhir_transfer), 
                IFNULL(t3.tgl_terakhir_tunai, IFNULL(t4.tgl_terakhir_transfer, '-'))) AS tgl_terakhir, NULL AS tgl,
                NULL AS jumlah_donatur_daftar, NULL AS jumlah_paket_daftar, NULL AS jumlah_donatur_bayar, NULL AS jumlah_paket_bayar, NULL AS donasi_tunai, 
                NULL AS donasi_transfer
                FROM (
                    SELECT MAX(tgl_gabung) AS tgl_terakhir_donatur
                    FROM tdonatur 
                    WHERE tgl_gabung between $tgl1 AND $tgl2
                ) t1
                LEFT JOIN (
                    SELECT DATE(MAX(tps.tgl_input)) AS tgl_terakhir_paket
                    FROM tpaket_sumbangan tps
                    WHERE DATE(tps.tgl_input) BETWEEN $tgl1 AND $tgl2
                ) t2 ON TRUE
                LEFT JOIN (
                    SELECT MAX(tgl_donasi) AS tgl_terakhir_tunai
                    FROM tsumbangan
                    WHERE tgl_donasi BETWEEN $tgl1 AND $tgl2 AND metode_pembayaran = 'Tunai'
                ) t3 ON TRUE
                LEFT JOIN (
                    SELECT MAX(tgl_donasi) AS tgl_terakhir_transfer
                    FROM tsumbangan
                    WHERE tgl_donasi BETWEEN $tgl1 AND $tgl2 AND metode_pembayaran = 'Transfer' " .(is_empty($id_bank) ? "" : "AND id_bank = '$id_bank'"). "
                ) t4 ON TRUE
                LEFT JOIN (
                    SELECT format_kode(CONCAT(get_abbreviation(nama_bank), '-', id_bank), 3) AS kode_bank
                    FROM tbank
                    WHERE id_bank = '$id_bank'
                ) t0 ON TRUE

                UNION ALL
                SELECT NULL AS kode_bank, NULL AS tgl_terakhir_donatur, NULL AS tgl_terakhir_paket, NULL AS tgl_terakhir_tunai, NULL AS tgl_terakhir_transfer, 
                NULL AS tgl_terakhir, tgl, SUM(jumlah_donatur_daftar) AS jumlah_donatur_daftar, SUM(jumlah_paket_daftar) AS jumlah_paket_daftar, 
                SUM(jumlah_donatur_bayar) AS jumlah_donatur_bayar, SUM(jumlah_paket_bayar) AS jumlah_paket_bayar, SUM(donasi_tunai) AS donasi_tunai, 
                SUM(donasi_transfer) AS donasi_transfer
                FROM (
                    SELECT ts.tgl_donasi AS tgl, 0 AS jumlah_donatur_daftar, 0 AS jumlah_paket_daftar, 
                    COUNT(DISTINCT tps.id_donatur) AS jumlah_donatur_bayar, IFNULL(SUM(tps.jumlah_paket), 0) AS jumlah_paket_bayar, 
                    IFNULL(t1.donasi_tunai, 0) AS donasi_tunai, IFNULL(t2.donasi_transfer, 0) AS donasi_transfer
                    FROM tsumbangan ts
                    JOIN tpaket_sumbangan tps ON ts.id_paket_sumbangan = tps.id_paket_sumbangan
                    LEFT JOIN (
                        SELECT ts.tgl_donasi, SUM(ts.jumlah_donasi) AS donasi_tunai
                        FROM tsumbangan ts
                        WHERE ts.metode_pembayaran = 'Tunai'
                        GROUP BY ts.tgl_donasi
                    ) t1 ON ts.tgl_donasi = t1.tgl_donasi
                    LEFT JOIN (
                        SELECT ts.tgl_donasi, SUM(ts.jumlah_donasi) AS donasi_transfer
                        FROM tsumbangan ts
                        WHERE ts.metode_pembayaran = 'Transfer' " .(is_empty($id_bank) ? "" : "AND ts.id_bank = '$id_bank'"). "
                        GROUP BY ts.tgl_donasi
                    ) t2 ON ts.tgl_donasi = t2.tgl_donasi
                    WHERE ts.tgl_donasi BETWEEN $tgl1 AND $tgl2
                    GROUP BY ts.tgl_donasi
                    
                    UNION ALL
                    SELECT tgl, SUM(jumlah_donatur) AS jumlah_donatur_daftar, SUM(jumlah_paket) AS jumlah_paket_daftar, 0 AS jumlah_donatur_bayar, 
                    0 AS jumlah_paket_bayar, 0 AS donasi_tunai, 0 AS donasi_transfer
                    FROM (
                        SELECT tgl_gabung AS tgl, COUNT(*) AS jumlah_donatur, 0 AS jumlah_paket
                        FROM tdonatur
                        WHERE tgl_gabung BETWEEN $tgl1 AND $tgl2
                        GROUP BY tgl_gabung
                        
                        UNION ALL 
                        SELECT DATE(tgl_input) AS tgl, 0 AS jumlah_donatur, SUM(jumlah_paket) AS jumlah_paket
                        FROM tpaket_sumbangan
                        WHERE DATE(tgl_input) BETWEEN $tgl1 AND $tgl2
                        GROUP BY DATE(tgl_input)
                    ) t1
                    GROUP BY tgl
                ) t1
                GROUP BY tgl");
            case 3: // laporan/penerimaan/bulanan/print_laporan
                $tgl1 = "'" .func_get_arg(1). "'";
                $tgl2 = "'" .func_get_arg(2). "'";
                $id_bank = func_get_arg(3);
                return $this->pbs->query("
                SELECT IFNULL(t0.kode_bank, 'Transfer') AS kode_bank, IFNULL(t1.tgl_terakhir_donatur, '-') AS tgl_terakhir_donatur, 
                IFNULL(t2.tgl_terakhir_paket, '-') AS tgl_terakhir_paket, IFNULL(t3.tgl_terakhir_tunai, '-') AS tgl_terakhir_tunai, 
                IFNULL(t4.tgl_terakhir_transfer, '-') AS tgl_terakhir_transfer, 
                IFNULL(IF(t3.tgl_terakhir_tunai > t4.tgl_terakhir_transfer, t3.tgl_terakhir_tunai, t4.tgl_terakhir_transfer), 
                IFNULL(t3.tgl_terakhir_tunai, IFNULL(t4.tgl_terakhir_transfer, '-'))) AS tgl_terakhir, NULL AS tgl,
                NULL AS jumlah_donatur_daftar, NULL AS jumlah_paket_daftar, NULL AS jumlah_donatur_bayar, NULL AS jumlah_paket_bayar, NULL AS donasi_tunai, 
                NULL AS donasi_transfer
                FROM (
                    SELECT MAX(tgl_gabung) AS tgl_terakhir_donatur
                    FROM tdonatur 
                    WHERE tgl_gabung BETWEEN $tgl1 AND $tgl2
                ) t1
                LEFT JOIN (
                    SELECT DATE(MAX(tps.tgl_input)) AS tgl_terakhir_paket
                    FROM tpaket_sumbangan tps
                    WHERE DATE(tps.tgl_input) BETWEEN $tgl1 AND $tgl2
                ) t2 ON TRUE
                LEFT JOIN (
                    SELECT MAX(tgl_donasi) AS tgl_terakhir_tunai
                    FROM tsumbangan
                    WHERE tgl_donasi BETWEEN $tgl1 AND $tgl2 AND metode_pembayaran = 'Tunai'
                ) t3 ON TRUE
                LEFT JOIN (
                    SELECT MAX(tgl_donasi) AS tgl_terakhir_transfer
                    FROM tsumbangan
                    WHERE tgl_donasi BETWEEN $tgl1 AND $tgl2 AND metode_pembayaran = 'Transfer' " .(is_empty($id_bank) ? "" : "AND id_bank = '$id_bank'"). " 
                ) t4 ON TRUE
                LEFT JOIN (
                    SELECT format_kode(CONCAT(get_abbreviation(nama_bank), '-', id_bank), 3) AS kode_bank
                    FROM tbank
                    WHERE id_bank = '$id_bank'
                ) t0 ON TRUE

                UNION ALL
                SELECT NULL AS kode_bank, NULL AS tgl_terakhir_donatur, NULL AS tgl_terakhir_paket, NULL AS tgl_terakhir_tunai, NULL AS tgl_terakhir_transfer, 
                NULL AS tgl_terakhir, CONCAT(YEAR(tgl), '-', RIGHT(CONCAT('0', MONTH(tgl)), 2)) AS tgl, SUM(jumlah_donatur_daftar) AS jumlah_donatur_daftar, SUM(jumlah_paket_daftar) AS jumlah_paket_daftar, 
                SUM(jumlah_donatur_bayar) AS jumlah_donatur_bayar, SUM(jumlah_paket_bayar) AS jumlah_paket_bayar, SUM(donasi_tunai) AS donasi_tunai, 
                SUM(donasi_transfer) AS donasi_transfer
                FROM (
                    SELECT ts.tgl_donasi AS tgl, 0 AS jumlah_donatur_daftar, 0 AS jumlah_paket_daftar, 
                    COUNT(DISTINCT tps.id_donatur) AS jumlah_donatur_bayar, IFNULL(SUM(tps.jumlah_paket), 0) AS jumlah_paket_bayar, 
                    IFNULL(t1.donasi_tunai, 0) AS donasi_tunai, IFNULL(t2.donasi_transfer, 0) AS donasi_transfer
                    FROM tsumbangan ts
                    JOIN tpaket_sumbangan tps ON ts.id_paket_sumbangan = tps.id_paket_sumbangan
                    LEFT JOIN (
                        SELECT ts.tgl_donasi, SUM(ts.jumlah_donasi) AS donasi_tunai
                        FROM tsumbangan ts
                        WHERE ts.metode_pembayaran = 'Tunai'
                        GROUP BY ts.tgl_donasi
                    ) t1 ON ts.tgl_donasi = t1.tgl_donasi
                    LEFT JOIN (
                        SELECT ts.tgl_donasi, SUM(ts.jumlah_donasi) AS donasi_transfer
                        FROM tsumbangan ts
                        WHERE ts.metode_pembayaran = 'Transfer' " .(is_empty($id_bank) ? "" : "AND ts.id_bank = '$id_bank'"). "
                        GROUP BY ts.tgl_donasi
                    ) t2 ON ts.tgl_donasi = t2.tgl_donasi
                        WHERE ts.tgl_donasi BETWEEN $tgl1 AND $tgl2
                        GROUP BY ts.tgl_donasi
                    
                    UNION ALL
                    SELECT tgl, SUM(jumlah_donatur) AS jumlah_donatur_daftar, SUM(jumlah_paket) AS jumlah_paket_daftar, 0 AS jumlah_donatur_bayar, 
                    0 AS jumlah_paket_bayar, 0 AS donasi_tunai, 0 AS donasi_transfer
                    FROM (
                        SELECT tgl_gabung AS tgl, COUNT(*) AS jumlah_donatur, 0 AS jumlah_paket
                        FROM tdonatur
                        WHERE tgl_gabung BETWEEN $tgl1 AND $tgl2
                        GROUP BY tgl_gabung
                        
                        UNION ALL 
                        SELECT DATE(tgl_input) AS tgl, 0 AS jumlah_donatur, SUM(jumlah_paket) AS jumlah_paket
                        FROM tpaket_sumbangan
                        WHERE DATE(tgl_input) BETWEEN $tgl1 AND $tgl2
                        GROUP BY DATE(tgl_input)
                    ) t1
                    GROUP BY tgl
                ) t1
                GROUP BY year(tgl), MONTH(tgl)");
            case 4: // laporan/paket/harian_detail/print_laporan
                $tgl1 = func_get_arg(1);
                $tgl2 = func_get_arg(2);
                $metode_pembayaran = func_get_arg(3);
                if ($metode_pembayaran === "Tunai" || $metode_pembayaran === "Transfer") {
                    $metode_pembayaran = " AND (ts.metode_pembayaran = '$metode_pembayaran')";
                }
                if (is_numeric($metode_pembayaran)) {
                    $metode_pembayaran = " AND (ts.id_bank = '$metode_pembayaran')";
                }
                $filter = func_get_arg(4);
                $this->pbs->query("SET @tgl1 = '$tgl1', @tgl2 = '$tgl2', @filter = '%$filter%'");
                return $this->pbs->query("
                SELECT ts.id_sumbangan, ts.no_kwitansi, td.kode_donatur, td.nama_id AS nama_donatur, ts.tgl_donasi, tp.nama_paket, ts.jumlah_donasi, 
                ts.metode_pembayaran, IFNULL(CONCAT(tb.nama_bank, ' / ', tb.an), '-') AS bank, ts.ket
                FROM tsumbangan ts 
                JOIN tpaket_sumbangan tps ON ts.id_paket_sumbangan = tps.id_paket_sumbangan
                JOIN tpaket tp ON tps.id_paket = tp.id_paket
                JOIN tdonatur td ON tps.id_donatur = td.id_donatur
                LEFT JOIN tbank tb ON ts.id_bank = tb.id_bank
                WHERE (ts.no_kwitansi LIKE @filter OR td.kode_donatur LIKE @filter OR td.nama_id LIKE @filter OR 
                tp.kode_paket LIKE @filter OR tp.nama_paket LIKE @filter) AND 
                (ts.tgl_donasi BETWEEN @tgl1 AND @tgl2) $metode_pembayaran ORDER BY ts.tgl_donasi");
            case 5: // laporan/paket/bulanan_detail/print_laporan
                $tgl1 = func_get_arg(1); 
                $tgl2 = func_get_arg(2);
                $metode_pembayaran = func_get_arg(3);
                $filter = func_get_arg(4);
                $this->pbs->query("SET @tgl1 = '$tgl1', @tgl2 = '$tgl2', @metode_pembayaran = '$metode_pembayaran', @filter = '%$filter%'");
                return $this->pbs->query("
                SELECT id, id_paket, kode_paket, nama_paket, nilai_paket, periode, SUM(total_donasi_tunai) + SUM(total_donasi_transfer) AS total_donasi, 
                SUM(total_donasi_tunai) AS total_donasi_tunai, SUM(total_donasi_transfer) AS total_donasi_transfer, 
                SUM(total_tunai) + SUM(total_transfer) AS total, SUM(total_tunai) AS total_tunai, SUM(total_transfer) AS total_transfer,
                MAX(pembayaran_terakhir) AS pembayaran_terakhir, SUM(jumlah_donasi_tunai) AS jumlah_donasi_tunai, 
                SUM(jumlah_donasi_transfer) AS jumlah_donasi_transfer, tgl_donasi
                FROM (
                    SELECT 0 AS id, tps.id_paket, tp.kode_paket, tp.nama_paket, IFNULL(tp.nilai_paket, '-') AS nilai_paket, 
                    CASE
                        WHEN SUBSTRING_INDEX(tp.periode, ' ', -1) = 'H' THEN CONCAT(SUBSTRING_INDEX(tp.periode, ' ', 1), ' Hari')
                        WHEN SUBSTRING_INDEX(tp.periode, ' ', -1) = 'B' THEN CONCAT(SUBSTRING_INDEX(tp.periode, ' ', 1), ' Bulan')
                        WHEN SUBSTRING_INDEX(tp.periode, ' ', -1) = 'T' THEN CONCAT(SUBSTRING_INDEX(tp.periode, ' ', 1), ' Tahun')
                        ELSE '-'
                    END AS periode, 
                    IF(ts.metode_pembayaran = 'Tunai', SUM(ts.jumlah_donasi), 0) AS total_donasi_tunai,
                    IF(ts.metode_pembayaran = 'Transfer', SUM(ts.jumlah_donasi), 0) AS total_donasi_transfer, 
                    IF(ts.metode_pembayaran = 'Tunai', COUNT(*), 0) AS total_tunai,
                    IF(ts.metode_pembayaran = 'Transfer', COUNT(*), 0) AS total_transfer, MAX(ts.tgl_donasi) AS pembayaran_terakhir,
                    ts.metode_pembayaran,
                    NULL AS jumlah_donasi_tunai, NULL AS jumlah_donasi_transfer, NULL AS tgl_donasi
                    FROM tpaket_sumbangan tps
                    JOIN tpaket tp ON tps.id_paket = tp.id_paket
                    JOIN tsumbangan ts ON tps.id_paket_sumbangan = ts.id_paket_sumbangan
                    WHERE (ts.tgl_donasi BETWEEN @tgl1 AND @tgl2) AND (tp.kode_paket LIKE @filter OR tp.nama_paket LIKE @filter) AND 
                    (IF(@metode_pembayaran = '', TRUE, ts.metode_pembayaran = @metode_pembayaran))
                    GROUP BY tps.id_paket, ts.metode_pembayaran DESC
                    
                    UNION ALL
                    SELECT 1 AS id, tps.id_paket, NULL AS kode_paket, NULL AS nama_paket, NULL AS nilai_paket, NULL AS periode, 
                    NULL AS total_donasi_tunai, NULL AS total_donasi_transfer, NULL AS total_tunai, NULL AS total_transfer, 
                    NULL AS pembayaran_terakhir, ts.metode_pembayaran,
                    IF(ts.metode_pembayaran = 'Tunai', SUM(ts.jumlah_donasi), 0) AS jumlah_donasi_tunai, 
                    IF(ts.metode_pembayaran = 'Transfer', SUM(ts.jumlah_donasi), 0) AS jumlah_donasi_transfer, 
                    CONCAT(YEAR(ts.tgl_donasi), '-', RIGHT(CONCAT('0', MONTH(ts.tgl_donasi)), 2)) AS tgl_donasi
                    FROM tpaket_sumbangan tps
                    JOIN tpaket tp ON tps.id_paket = tp.id_paket
                    JOIN tsumbangan ts ON tps.id_paket_sumbangan = ts.id_paket_sumbangan
                    WHERE (ts.tgl_donasi BETWEEN @tgl1 AND @tgl2) AND (tp.kode_paket LIKE @filter OR tp.nama_paket LIKE @filter) AND 
                    (IF(@metode_pembayaran = '', TRUE, ts.metode_pembayaran = @metode_pembayaran))
                    GROUP BY tps.id_paket, YEAR(ts.tgl_donasi), MONTH(ts.tgl_donasi), ts.metode_pembayaran
                ) t1
                GROUP BY id, id_paket, tgl_donasi
                ORDER BY id_paket, tgl_donasi");
        }
    }

    /** laporan/paket/rekapan */
    function lpaket_rekapan_get() {
        switch (func_get_arg(0)) {
            case 1: // laporan/paket/rekapan
                return $this->pbs->query("
                    SELECT * FROM (
                        SELECT YEAR(ts.tgl_donasi) AS thn, tb.kode AS bln, tb.nama AS nm_bln
                        FROM tsumbangan ts
                        JOIN tbulan tb ON MONTH(ts.tgl_donasi) = tb.kode
                        GROUP BY YEAR(ts.tgl_donasi), MONTH(ts.tgl_donasi)
                        
                        UNION ALL
                        SELECT YEAR(tps.tgl_input) AS thn, tb.kode AS bln, tb.nama AS nm_bln
                        FROM tpaket_sumbangan tps
                        JOIN tbulan tb ON MONTH(tps.tgl_input) = tb.kode
                        GROUP BY YEAR(tps.tgl_input), MONTH(tps.tgl_input)
                    ) t1
                    GROUP BY thn DESC, bln DESC
                ");
            case 2: // laporan/paket/rekapan/print_laporan
                $tgl1 = func_get_arg(1);
                $tgl2 = func_get_arg(2);
                $filter = func_get_arg(3);
                return $this->pbs->query("
                    SELECT 
                        tp.id_paket, tp.kode_paket, tp.nama_paket, IFNULL(tp.nilai_paket, '-') AS nilai_paket, IFNULL(tp.periode, '-') AS periode,
                        IFNULL(t1.jumlah_paket, 0) AS jumlah_paket,
                        IF(tp.nilai_paket IS NULL, '-', tp.nilai_paket * IFNULL(t1.jumlah_paket, 0)) AS total_nilai_paket,
                        IFNULL(t2.jumlah_donasi, 0) AS total_donasi,
                        IF(tp.nilai_paket IS NULL, '-', (tp.nilai_paket * IFNULL(t1.jumlah_paket, 0) - IFNULL(t2.jumlah_donasi, 0))) AS sisa
                    FROM tpaket tp
                    LEFT JOIN (
                        SELECT id_paket, SUM(jumlah_paket) AS jumlah_paket
                        FROM tpaket_sumbangan
                        WHERE DATE(tgl_input) BETWEEN '$tgl1' AND '$tgl2'
                        GROUP BY id_paket
                    ) t1 ON tp.id_paket = t1.id_paket
                    LEFT JOIN (
                        SELECT tps.id_paket, SUM(ts.jumlah_donasi) AS jumlah_donasi
                        FROM tpaket_sumbangan tps
                        JOIN tsumbangan ts ON tps.id_paket_sumbangan = ts.id_paket_sumbangan
                        WHERE ts.tgl_donasi BETWEEN '$tgl1' AND '$tgl2'
                        GROUP BY tps.id_paket
                    ) t2 ON tp.id_paket = t2.id_paket
                    WHERE tp.kode_paket LIKE '%$filter%' OR tp.nama_paket LIKE '%$filter%'
                ");
        }
    }

    /** laporan rekapan donatur */
    function lrekapan_donatur_get() {
        switch (func_get_arg(0)) {
            case 1: // laporan/rekap_donatur/by_kolektor
                return $this->pbs->query("
                    SELECT YEAR(td.tgl_gabung) AS thn, MONTH(td.tgl_gabung) AS bln, tb.nama AS nm_bln
                    FROM tdonatur td
                    JOIN tbulan tb ON MONTH(td.tgl_gabung) = tb.kode
                    GROUP BY YEAR(td.tgl_gabung), MONTH(td.tgl_gabung)
                    ORDER BY td.tgl_gabung DESC");
            case 2: // laporan/rekap_donatur/by_kolektor/print_laporan
                $tgl1 = func_get_arg(1);
                $tgl2 = func_get_arg(2);
                $paket = func_get_arg(3);
                $donatur = func_get_arg(4);
                $kolektor = func_get_arg(5);
                $lunas = func_get_arg(6);
                $case_when = "(
                    CASE 
                        WHEN @lunas = '0' THEN TRUE
                        WHEN @lunas = '1' THEN 
                            (tp.nilai_paket IS NULL AND tps.total_donasi > 0) OR 
                            (tp.nilai_paket * tps.jumlah_paket - tps.total_donasi <= 0)
                        WHEN @lunas = '2' THEN
                            (tp.nilai_paket IS NULL AND tps.total_donasi = 0) OR 
                            (tp.nilai_paket * tps.jumlah_paket - tps.total_donasi > 0)
                        ELSE FALSE
                    END
                )";

                $this->pbs->query("SET @tgl1 = '$tgl1', @tgl2 = '$tgl2', @donatur = '%$donatur%', @paket = '%$paket%', @kolektor = '%$kolektor%', @lunas = '$lunas';");
                return $this->pbs->query("
                SELECT * FROM (
                    SELECT 
                        id_kolektor, kode_kolektor, nama_kolektor, COUNT(id_donatur) AS jumlah_donatur, SUM(jumlah_paket) AS jumlah_paket_k, 
                        SUM(total_nilai_paket) AS total_nilai_paket_k, SUM(total_donasi) AS total_donasi_k, SUM(sisa) AS sisa_k, 
                        NULL AS id_donatur, NULL AS kode_donatur, NULL AS nama_donatur, NULL AS kota_domisili, NULL AS no_hp1, 
                        NULL AS tgl_gabung, NULL AS jumlah_paket_d, NULL AS total_nilai_paket_d, NULL AS total_donasi_d, NULL AS sisa_d, 
                        NULL AS pembayaran_terakhir, NULL AS tgl_jatuh_tempo, 
                        0 AS urutan
                    FROM (
                        SELECT 
                            tps.id_paket_sumbangan, tps.id_kolektor, tk.kode_kolektor, tk.nama AS nama_kolektor, tps.id_donatur, 
                            td.kode_donatur, td.nama_id AS nama_donatur, td.kota_domisili, td.no_hp1, td.tgl_gabung, 
                            tps.jumlah_paket, (tp.nilai_paket * tps.jumlah_paket) AS total_nilai_paket, tps.total_donasi, 
                            (tp.nilai_paket * tps.jumlah_paket - tps.total_donasi) AS sisa, NULL AS pembayaran_terakhir, tgl_jatuh_tempo
                            FROM tpaket_sumbangan tps
                            JOIN tkolektor tk ON tps.id_kolektor = tk.id_kolektor
                            JOIN tpaket tp ON tps.id_paket = tp.id_paket
                            JOIN tdonatur td ON tps.id_donatur = td.id_donatur
                        WHERE 
                            (td.tgl_gabung BETWEEN @tgl1 AND @tgl2) AND (tp.kode_paket LIKE @paket OR tp.nama_paket LIKE @paket) AND
                            (td.kode_donatur LIKE @donatur OR td.nama_id LIKE @donatur) AND (tk.kode_kolektor LIKE @kolektor OR tk.nama LIKE @kolektor) AND $case_when
                    ) t1
                    GROUP BY id_kolektor
                    
                    UNION ALL 
                    SELECT 
                        t1.id_kolektor,
                        NULL AS kode_kolektor, NULL AS nama_kolektor, NULL AS jumlah_donatur, NULL AS jumlah_paket_k, NULL AS total_nilai_paket_k, 
                        NULL AS total_donasi_k, NULL AS sisa_k, 
                        t1.id_donatur, t1.kode_donatur, t1.nama_donatur, IFNULL(t1.kota_domisili, '-') AS kota_domisili, 
                        IFNULL(t1.no_hp1, '-') AS no_hp1, t1.tgl_gabung, t1.jumlah_paket AS jumlah_paket_d, 
                        t1.total_nilai_paket AS total_nilai_paket_d, t1.total_donasi AS total_donasi_d, t1.sisa AS sisa_d, 
                        IFNULL(t2.tgl_donasi, '-') AS pembayaran_terakhir, t1.tgl_jatuh_tempo,
                        1 AS urutan
                    FROM (
                        SELECT 
                            tps.id_paket_sumbangan, tps.id_kolektor, tk.kode_kolektor, tk.nama AS nama_kolektor, tps.id_donatur, 
                            td.kode_donatur, td.nama_id AS nama_donatur, td.kota_domisili, td.no_hp1, td.tgl_gabung, 
                            tps.jumlah_paket, (tp.nilai_paket * tps.jumlah_paket) AS total_nilai_paket, tps.total_donasi, 
                            (tp.nilai_paket * tps.jumlah_paket - tps.total_donasi) AS sisa, tps.tgl_jatuh_tempo
                        FROM tpaket_sumbangan tps
                        JOIN tkolektor tk ON tps.id_kolektor = tk.id_kolektor
                        JOIN tpaket tp ON tps.id_paket = tp.id_paket
                        JOIN tdonatur td ON tps.id_donatur = td.id_donatur
                        WHERE 
                            (td.tgl_gabung BETWEEN @tgl1 AND @tgl2) AND (tp.kode_paket LIKE @paket OR tp.nama_paket LIKE @paket) AND 
                            (td.kode_donatur LIKE @donatur OR td.nama_id LIKE @donatur) AND (tk.kode_kolektor LIKE @kolektor OR tk.nama LIKE @kolektor) AND $case_when
                    ) t1
                    LEFT JOIN (
                        SELECT tps.id_kolektor, tps.id_donatur, MAX(ts.tgl_donasi) AS tgl_donasi
                        FROM tsumbangan ts
                        JOIN tpaket_sumbangan tps ON ts.id_paket_sumbangan = tps.id_paket_sumbangan
                        GROUP BY ts.id_paket_sumbangan
                    ) t2 ON t1.id_kolektor = t2.id_kolektor AND t1.id_donatur = t2.id_donatur
                    GROUP BY t1.id_paket_sumbangan, t1.id_kolektor, t1.id_donatur
                ) t1
                ORDER BY id_kolektor, urutan, id_donatur");
            case 3: // laporan/rekap_donatur/by_kota_domisili
                return $this->pbs->query("
                    SELECT YEAR(ts.tgl_donasi) AS thn, MONTH(ts.tgl_donasi) AS bln, tb.nama AS nm_bln
                    FROM tsumbangan ts
                    JOIN tbulan tb ON MONTH(ts.tgl_donasi) = tb.kode
                    GROUP BY YEAR(ts.tgl_donasi), MONTH(ts.tgl_donasi)
                    ORDER BY ts.tgl_donasi DESC");
            case 4: // laporan/rekap_donatur/by_kota_domisili/print_laporan
                $tgl1 = func_get_arg(1);
                $tgl2 = func_get_arg(2);
                $filter = "'%" .func_get_arg(3). "%'";
                return $this->pbs->query("
                    SELECT td.id_donatur, td.kode_donatur, td.nama_id, UPPER(IFNULL(td.kota_domisili, '-')) AS kota_domisili, 
                    SUM(t1.jumlah_donasi) AS jumlah_donasi
                    FROM tdonatur td
                    JOIN tpaket_sumbangan tps ON td.id_donatur = tps.id_donatur
                    JOIN (
                        SELECT id_sumbangan, id_paket_sumbangan, nama_penyumbang, SUM(jumlah_donasi) AS jumlah_donasi
                        FROM tsumbangan
                        WHERE tgl_donasi BETWEEN '$tgl1' AND '$tgl2'
                        GROUP BY id_paket_sumbangan
                    ) t1 ON tps.id_paket_sumbangan = t1.id_paket_sumbangan
                    WHERE 
                    td.kode_donatur LIKE $filter OR td.nama_id LIKE $filter OR td.nama_cn LIKE $filter OR 
                    td.kota_domisili LIKE $filter OR t1.nama_penyumbang LIKE $filter
                    GROUP BY td.id_donatur
                    ORDER BY td.kota_domisili, td.id_donatur");
        }
    }

    /** tbank */
    function tbank_get() {
        switch (func_get_arg(0)) {
        case 1: // ajax_repository/get_bank_list, master/bank
                return $this->pbs->select(func_get_arg(1))->get("tbank");
        case 2: // master/c_bank/is_valid_bank, master/c_paket/is_valid_bank_list, input/c_sumbangan/is_valid_sumbangan
            $this->pbs->select(func_get_arg(1));
            if (func_get_arg(2)[0] === "where") {
                $this->pbs->where(func_get_arg(2)[1], func_get_arg(2)[2]);
            } else if (func_get_arg(2)[0] === "like") {
                $this->pbs->like("an", func_get_arg(2)[1])->or_like("no_rek", func_get_arg(2)[1]);
            }
            return $this->pbs->get("tbank");
        case 3: // input/sumbangan/get_bank_list
            return $this->pbs->query("SELECT id_bank, an, no_rek FROM tbank WHERE id_bank NOT IN(SELECT id_bank FROM tpaket1)");
        }
    }

    function tbank_put($bank) {
        return $this->pbs->query("CALL save_bank(?, ?, ?, ?)", $bank);
    }

    /** tpaket_sumbangan1 & tsumbangan1 */
    function tbiaowen_get() {
        switch (func_get_arg(0)) {
            case 1: // daftar/biaowen/bakar_biaowen
                $id_sumbangan = func_get_arg(1);
                $biaowen = func_get_arg(2);
                return $this->pbs->query("
                SELECT lunas
                FROM tsumbangan1
                WHERE id_sumbangan = '$id_sumbangan' AND biaowen = '$biaowen'");
            case 2: // daftar/biaowen/get_biaowen_list
                $nama_paket = func_get_arg(1);
                $biaowen = func_get_arg(2);
                $lunas = func_get_arg(3);
                $bakar = func_get_arg(4);
                $limit = is_empty(func_get_arg(5)) ? "" : "LIMIT " .(func_get_arg(5) * func_get_arg(6)). ", " .func_get_arg(6);
                $this->pbs->query("SET @nama_paket = '%$nama_paket%', @biaowen = '%$biaowen%', @lunas = '$lunas', @bakar = '$bakar'");
                return $this->pbs->query("
                SELECT t1.id_sumbangan, t1.id_paket_sumbangan, td.nama_id AS nama_donatur, tp.nama_paket, t1.nmr, t1.biaowen, t1.lunas, t1.bakar, 
                t1.tgl_bakar, t1.total_donasi
                FROM (
                    SELECT id_sumbangan, id_paket_sumbangan, nmr, biaowen, lunas, bakar, tgl_bakar, SUM(total_donasi) AS total_donasi
                    FROM (
                        SELECT id_sumbangan, id_paket_sumbangan, nmr, biaowen, lunas, bakar, tgl_bakar, 0 AS total_donasi
                        FROM (
                            SELECT 0 AS id_sumbangan, tp1.id_paket_sumbangan, tp1.nmr, tp1.biaowen, 0 AS lunas, 0 AS bakar, NULL AS tgl_bakar
                            FROM tpaket_sumbangan1 tp1
                            
                            UNION ALL 
                            SELECT ts1.id_sumbangan, ts.id_paket_sumbangan, ts1.nmr, ts1.biaowen, ts1.lunas, ts1.bakar, ts1.tgl_bakar
                            FROM tsumbangan1 ts1
                            JOIN tsumbangan ts ON ts1.id_sumbangan = ts.id_sumbangan
                            ORDER BY lunas DESC
                        ) t1
                        GROUP BY id_paket_sumbangan, biaowen

                        UNION ALL 
                        SELECT ts.id_sumbangan, ts.id_paket_sumbangan, NULL AS nmr, ts1.biaowen, NULL AS lunas, NULL AS bakar, NULL AS tgl_bakar, 
                        SUM(ts.jumlah_donasi) AS total_donasi
                        FROM tsumbangan1 ts1
                        JOIN tsumbangan ts ON ts1.id_sumbangan = ts.id_sumbangan
                        GROUP BY ts.id_paket_sumbangan, ts.id_paket_sumbangan, ts1.biaowen
                    ) t1
                    GROUP BY id_paket_sumbangan, biaowen
                ) t1
                JOIN tpaket_sumbangan tps ON t1.id_paket_sumbangan = tps.id_paket_sumbangan
                JOIN tdonatur td ON tps.id_donatur = td.id_donatur
                JOIN tpaket tp ON tps.id_paket = tp.id_paket 
                WHERE ((td.kode_donatur LIKE @nama_paket OR td.nama_id LIKE @nama_paket) OR 
                (tp.kode_paket LIKE @nama_paket OR tp.nama_paket LIKE @nama_paket)) AND t1.biaowen LIKE @biaowen AND 
                CASE
                    WHEN @lunas != '0' AND @lunas != '1' THEN TRUE
                    ELSE t1.lunas = @lunas
                END AND
                CASE
                    WHEN @bakar != '0' AND @bakar != '1' THEN TRUE
                    ELSE t1.bakar = @bakar
                END
                ORDER BY id_paket_sumbangan, biaowen $limit");
        }
    }

    function tbiaowen_update($id_sumbangan, $biaowen) {
        return $this->pbs->where("id_sumbangan", $id_sumbangan)->where("biaowen", $biaowen)->
        update("tsumbangan1", array("bakar" => 1, "tgl_bakar" => date("Y-m-d H:i:s")));
    }

    /** tdonatur */
    function tdonatur_delete($id_donatur) {
        return $this->pbs->where("id_donatur", $id_donatur)->delete("tdonatur");
    }

    function tdonatur_get() {
        switch (func_get_arg(0)) {
            case 1: // ajax_repository/get_donatur_list
                return $this->pbs->select(func_get_arg(1))->get("tdonatur");
            case 2: // master/donatur, input/c_paket_sumbangan/is_valid_paket_sumbangan
                $this->pbs->select(func_get_arg(1));
                if (func_get_arg(2)[0] === "where") {
                    $this->pbs->where(func_get_arg(2)[1], func_get_arg(2)[2]);
                } else if (func_get_arg(2)[0] === "like"){
                    $this->pbs->like("kode_donatur", func_get_arg(2)[1])->or_like("nama_id", func_get_arg(2)[1]);
                }
                return $this->pbs->get("tdonatur");
            case 3: // daftar/donatur/get_donatur_list
                $donatur = func_get_arg(1);
                $tgl_terakhir = is_empty(func_get_arg(2)) ? "" : " AND (t2.pembayaran_terakhir <= '" .func_get_arg(2). "')";
                $limit = is_empty(func_get_arg(3)) ? "" : "LIMIT " .(func_get_arg(3) * func_get_arg(4)). ", " .func_get_arg(4);
                return $this->pbs->query("
                SELECT td.id_donatur, td.kode_donatur, td.nama_id, td.nama_cn, td.alamat, td.kota_lahir, 
                td.tgl_lahir, td.kota_domisili, td.no_hp1, td.no_hp2, td.email, 
                td.ket, td.tgl_gabung, IFNULL(SUM(tps.jumlah_paket), 0) AS jmlh_paket, IFNULL(SUM(tp.nilai_paket * tps.jumlah_paket), 0) AS total_nilai_paket, 
                IFNULL(t1.jumlah_biaowen, 0) AS jumlah_biaowen, t2.pembayaran_terakhir
                FROM tdonatur td 
                LEFT JOIN tpaket_sumbangan tps ON td.id_donatur = tps.id_donatur
                LEFT JOIN tpaket tp ON tps.id_paket = tp.id_paket 
                LEFT JOIN (
                        SELECT id_donatur, COUNT(DISTINCT biaowen) AS jumlah_biaowen FROM (
                        SELECT tps.id_donatur, 0 AS id_sumbangan, tps1.nmr, tps1.biaowen
                        FROM tpaket_sumbangan1 tps1
                        JOIN tpaket_sumbangan tps ON tps1.id_paket_sumbangan = tps.id_paket_sumbangan
                        
                        UNION ALL 
                        SELECT tps.id_donatur, ts1.id_sumbangan, ts1.nmr, ts1.biaowen
                        FROM tsumbangan1 ts1
                        JOIN tsumbangan ts ON ts1.id_sumbangan = ts.id_sumbangan
                        JOIN tpaket_sumbangan tps ON ts.id_paket_sumbangan = tps.id_paket_sumbangan
                    ) t1
                    GROUP BY id_donatur
                ) t1 ON td.id_donatur = t1.id_donatur
                LEFT JOIN (
                    SELECT tps.id_donatur, MAX(ts.tgl_donasi) AS pembayaran_terakhir
                    FROM tpaket_sumbangan tps
                    JOIN tsumbangan ts ON tps.id_paket_sumbangan = ts.id_paket_sumbangan
                    GROUP BY tps.id_donatur
                ) t2 ON td.id_donatur = t2.id_donatur
                WHERE (td.kode_donatur LIKE '%$donatur%' OR td.nama_id LIKE '%$donatur%') $tgl_terakhir
                GROUP BY td.id_donatur $limit");
            case 4: // input/sumbangan
                return $this->pbs->query("
                SELECT id_donatur, kode_donatur, nama_id, GROUP_CONCAT(detail_paket SEPARATOR '#') AS detail_paket
                FROM (
                    SELECT id_donatur, kode_donatur, nama_id, CONCAT_WS('|', id_paket_sumbangan, nama_paket, IFNULL(sisa_nilai_paket, '-'), GROUP_CONCAT(detail_paket SEPARATOR '|')) AS detail_paket
                    FROM (
                        SELECT tps.id_paket_sumbangan, td.id_donatur, td.kode_donatur, td.nama_id, tp.nama_paket, ((tp.nilai_paket * tps.jumlah_paket) - tps.total_donasi) AS sisa_nilai_paket, NULL AS detail_paket
                        FROM tpaket_sumbangan tps
                        JOIN tdonatur td ON tps.id_donatur = td.id_donatur
                        JOIN tpaket tp ON tps.id_paket = tp.id_paket
                        WHERE tp.nilai_paket IS NULL OR ((tp.nilai_paket * tps.jumlah_paket) - tps.total_donasi) > 0
                        
                        UNION ALL
                        SELECT id_paket_sumbangan, NULL AS id_donatur, NULL AS kode_donatur, NULL AS nama_id, NULL AS nama_paket, NULL AS sisa_nilai_paket, 
                        IF(detail_bank = '', '-', GROUP_CONCAT(detail_bank ORDER BY id_bank SEPARATOR ';')) AS detail_paket
                        FROM (
                            SELECT tps.id_paket_sumbangan, tb.id_bank, CONCAT_WS('~', tb.id_bank, tb.an, tb.no_rek) AS detail_bank
                            FROM tpaket_sumbangan tps
                            JOIN tpaket tp ON tps.id_paket = tp.id_paket
                            LEFT JOIN tpaket1 tp1 ON tps.id_paket = tp1.id_paket
                            LEFT JOIN tbank tb ON tp1.id_bank = tb.id_bank
                            WHERE tp.nilai_paket IS NULL OR ((tp.nilai_paket * tps.jumlah_paket) - tps.total_donasi) > 0
                        ) t1
                        GROUP BY id_paket_sumbangan
                        
                        UNION ALL
                        SELECT id_paket_sumbangan, NULL AS id_donatur, NULL AS kode_donatur, NULL AS nama_id, NULL AS nama_paket, NULL AS sisa_nilai_paket,
                        GROUP_CONCAT(biaowen ORDER BY nmr SEPARATOR ';') AS detail_paket
                        FROM (
                            SELECT id_paket_sumbangan, nmr, biaowen 
                            FROM (
                                SELECT id_paket_sumbangan, lunas, nmr, biaowen 
                                FROM (
                                    SELECT tps1.id_paket_sumbangan, 0 AS lunas, tps1.nmr, tps1.biaowen
                                    FROM tpaket_sumbangan1 tps1
                                    
                                    UNION ALL
                                    SELECT ts.id_paket_sumbangan, ts1.lunas, ts1.nmr, ts1.biaowen
                                    FROM tsumbangan ts
                                    JOIN tsumbangan1 ts1 ON ts.id_sumbangan = ts1.id_sumbangan
                                    
                                    ORDER BY lunas DESC
                                ) t1
                                GROUP BY id_paket_sumbangan, biaowen
                            ) t1
                            WHERE lunas = 0
                            ORDER BY id_paket_sumbangan, nmr
                        ) t1
                        GROUP BY id_paket_sumbangan
                    ) t1
                    GROUP BY id_paket_sumbangan
                ) t1
                WHERE id_donatur IS NOT NULL
                GROUP BY id_donatur");
        }
    }
    
    function tdonatur_put($donatur) {
        return $this->pbs->query("CALL save_donatur(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", $donatur);
    }

    /** tjenis_paket */
    function tjenis_paket_get() {
        return $this->pbs->select("nama")->get("tjenis_paket");
    }

    /** tjenis_souvenir */ 
    function tjenis_souvenir_get() {
        return $this->pbs->get("tjenis_souvenir");
    }

    /** tkolektor */
    function tkolektor_delete($id_kolektor) {
        return $this->pbs->where("id_kolektor", $id_kolektor)->delete("tkolektor");
    }

    function tkolektor_get() {
        switch (func_get_arg(0)) {
            case 1: // ajax_repository
                return $this->pbs->select(func_get_arg(1))->get("tkolektor");
            case 2: // master/kolektor, input/c_paket_sumbangan
                $this->pbs->select(func_get_arg(1));
                if (func_get_arg(2)[0] === "where") {
                    $this->pbs->where(func_get_arg(2)[1], func_get_arg(2)[2]);
                } else if (func_get_arg(2)[0] === "like") {
                    $this->pbs->like("kode_kolektor", func_get_arg(2)[1])->or_like("nama", func_get_arg(2)[1]);
                }
                return $this->pbs->get("tkolektor");
            case 3: // daftar/kolektor
                $filter = func_get_arg(1);
                $limit = is_empty(func_get_arg(2)) ? "" : "LIMIT " .(func_get_arg(2) * func_get_arg(3)). ", " .func_get_arg(3);
                return $this->pbs->query("
                SELECT tk.id_kolektor, tk.kode_kolektor, tk.nama, tk.no_hp1, tk.no_hp2, tk.email, tk.ket, IFNULL(SUM(tps.jumlah_paket), 0) AS jmlh_paket, 
                IFNULL(SUM(tp.nilai_paket * tps.jumlah_paket), 0) AS total_nilai_paket, COUNT(DISTINCT tps.id_donatur) AS jumlah_donatur
                FROM tkolektor tk
                LEFT JOIN tpaket_sumbangan tps ON tk.id_kolektor = tps.id_kolektor
                LEFT JOIN tpaket tp ON tps.id_paket = tp.id_paket
                WHERE (tk.kode_kolektor LIKE '%$filter%' OR tk.nama LIKE '%$filter%')
                GROUP BY tk.id_kolektor $limit");
        }
    }

    function tkolektor_put($kolektor) {
        return $this->pbs->query("CALL save_kolektor(?, ?, ?, ?, ?, ?, ?)", $kolektor);
    }

    /** tkota */
    function tkota_get() {
        return $this->pbs->get("tkota");
    }

    /** tmenu */
    function tmenu_get() {
        return $this->pbs->where("LENGTH(kode)", func_get_arg(0))->like("kode", func_get_arg(1), "after")->get("tmenu");
    }

    /** tpaket */
    function tpaket_delete($id_paket) {
        return $this->pbs->where("id_paket", $id_paket)->delete("tpaket");
    }

    function tpaket_get() {
        switch (func_get_arg(0)) {
            case 1: // ajax_repository
                $select = is_empty(func_get_arg(1)) ? "tp.id_paket, tp.nama_perusahaan, tp.kode_paket, tp.nama_paket, tp.nilai_paket, tp.periode, 
                GROUP_CONCAT(CONCAT_WS('|', tp1.id_bank, tb.an, tb.no_rek) ORDER BY tb.id_bank SEPARATOR '#') AS bank_list" : func_get_arg(1);
                return $this->pbs->query("
                SELECT $select
                FROM tpaket tp
                LEFT JOIN tpaket1 tp1 ON tp.id_paket = tp1.id_paket
                LEFT JOIN tbank tb ON tp1.id_bank = tb.id_bank
                GROUP BY tp.id_paket");
            case 2: // master/paket, input/c_paket_sumbangan, daftar/paket
                $select = is_empty(func_get_arg(1)) ? "tp.id_paket, tp.nama_perusahaan, tp.kode_paket, tp.nama_paket, tp.nilai_paket, tp.periode, 
                GROUP_CONCAT(CONCAT_WS('|', tp1.id_bank, tb.an, tb.no_rek) ORDER BY tb.id_bank SEPARATOR '#') AS bank_list" : func_get_arg(1);
                if (func_get_arg(2)[0] === "where") {
                    $filter = "WHERE " .func_get_arg(2)[1]. " = '" .func_get_arg(2)[2]. "'";
                } else if (func_get_arg(2)[0] === "like") {
                    $filter =  "WHERE tp.kode_paket LIKE '%" .func_get_arg(2)[1]. "%' OR tp.nama_paket LIKE '%" .func_get_arg(2)[1]. "%'";
                }
                $limit = is_empty(func_get_arg(3)) ? "" : "LIMIT " .(func_get_arg(3) * func_get_arg(4)). ", " .func_get_arg(4);
                return $this->pbs->query("
                SELECT $select
                FROM tpaket tp
                LEFT JOIN tpaket1 tp1 ON tp.id_paket = tp1.id_paket
                LEFT JOIN tbank tb ON tp1.id_bank = tb.id_bank
                $filter
                GROUP BY tp.id_paket $limit");
        }
    }

    function tpaket_put($id_paket, $paket) {
        $this->trans_begin();
        if (!is_empty($id_paket)) $this->pbs->query("SET @id_paket = $id_paket");
        $this->pbs->query("CALL save_paket(@id_paket, ?, ?, ?, ?, ?)", $paket);
        return $this->pbs->query("SELECT @id_paket AS id_paket");
    }

    function tpaket_rollback($id_paket) {
        $this->rollback();
        $this->pbs->query("ALTER TABLE tpaket AUTO_INCREMENT = ?", ($id_paket - 1));
    }

    /** tpaket1 */
    function tpaket1_delete($id_paket) {
        return $this->pbs->where("id_paket", $id_paket)->delete("tpaket1");
    }

    function tpaket1_put($paket1) {
        $result = $this->pbs->insert_batch("tpaket1", $paket1);
        return $result === count($paket1);
    }

    /** tpaket_sumbangan */
    function tpaket_sumbangan_delete($id_paket_sumbangan) {
        return $this->pbs->where("id_paket_sumbangan", $id_paket_sumbangan)->delete("tpaket_sumbangan");
    }

    function tpaket_sumbangan_get() {
        switch (func_get_arg(0)) {
            case 1: // input/c_sumbangan
                return $this->pbs->select(func_get_arg(1))->where(func_get_arg(2)[0], func_get_arg(2)[1])->get("tpaket_sumbangan");
            case 2: // tampil/paket_sumbangan
                $filter = "(kode_paket LIKE '%" .func_get_arg(1). "%' OR nama_paket LIKE '%" .func_get_arg(1). "%' OR 
                kode_donatur LIKE '%" .func_get_arg(1). "%' OR nama_donatur LIKE '%" .func_get_arg(1). "%' OR 
                kode_kolektor LIKE '%" .func_get_arg(1). "%' OR nama_kolektor LIKE '%" .func_get_arg(1). "%')";
                $lunas = "";
                switch (func_get_arg(2)) {
                    case "1": $lunas = " AND (nilai_paket IS NULL OR nilai_paket * jumlah_paket - total_donasi <= 0)"; break;
                    case "2": $lunas = " AND ((nilai_paket IS NULL AND total_donasi > 0) OR (nilai_paket * jumlah_paket - total_donasi > 0))"; break;
                }
                $tgl1 = func_get_arg(3);
                $tgl2 = func_get_arg(4);
                $limit = is_empty(func_get_arg(5)) ? "" : "LIMIT " .(func_get_arg(5) * func_get_arg(6)). ", " .func_get_arg(6);
                return $this->pbs->query("
                SELECT t1.id_paket_sumbangan, t1.kode_donatur, t1.nama_donatur, t1.kode_kolektor, t1.nama_kolektor, t1.kode_paket, t1.nama_paket, t1.nilai_paket, 
                t1.jumlah_paket, t1.total_donasi, t1.sisa, t1.ket, t1.tgl_jatuh_tempo, biaowen_list
                FROM (
                    SELECT t1.id_paket_sumbangan, t1.kode_donatur, t1.nama_donatur, t1.kode_kolektor, t1.nama_kolektor, t1.kode_paket, t1.nama_paket, t1.nilai_paket, 
                    t1.jumlah_paket, t1.total_donasi, t1.sisa, t1.ket, t1.tgl_jatuh_tempo, t1.tgl_input, GROUP_CONCAT(t1.biaowen_list) AS biaowen_list 
                    FROM (
                        SELECT tps.id_paket_sumbangan, td.kode_donatur, td.nama_id AS nama_donatur, tk.kode_kolektor, tk.nama AS nama_kolektor, tp.kode_paket, 
                        tp.nama_paket, tp.nilai_paket, tps.jumlah_paket, tps.total_donasi, (tp.nilai_paket * tps.jumlah_paket - tps.total_donasi) AS sisa, tps.ket, 
                        tps.tgl_jatuh_tempo, tps.tgl_input, NULL AS biaowen_list
                        FROM tpaket_sumbangan tps
                        JOIN tdonatur td ON tps.id_donatur = td.id_donatur
                        JOIN tkolektor tk ON tps.id_kolektor = tk.id_kolektor
                        JOIN tpaket tp ON tps.id_paket = tp.id_paket
                
                        UNION ALL
                        SELECT id_paket_sumbangan, NULL AS kode_donatur, NULL AS nama_donatur, NULL AS kode_kolektor, NULL AS nama_kolektor, NULL AS kode_paket,
                        NULL AS nama_paket, NULL AS nilai_paket, NULL AS jumlah_paket, NULL AS total_donasi, NULL AS sisa, NULL AS ket, NULL AS tgl_jatuh_tempo, 
                        NULL AS tgl_input,
                        GROUP_CONCAT(biaowen ORDER BY biaowen SEPARATOR '#') AS biaowen_list
                        FROM (
                            SELECT id_paket_sumbangan, lunas, nmr, biaowen 
                            FROM (
                                SELECT tps1.id_paket_sumbangan, 0 AS lunas, tps1.nmr, tps1.biaowen
                                FROM tpaket_sumbangan1 tps1
                                
                                UNION ALL
                                SELECT ts.id_paket_sumbangan, ts1.lunas, ts1.nmr, ts1.biaowen
                                FROM tsumbangan ts
                                JOIN tsumbangan1 ts1 ON ts.id_sumbangan = ts1.id_sumbangan
                                ORDER BY lunas DESC
                            ) t1
                            GROUP BY id_paket_sumbangan, biaowen
                        ) t1
                        GROUP BY id_paket_sumbangan
                    ) t1
                    GROUP BY id_paket_sumbangan
                ) t1
                WHERE $filter $lunas AND (DATE(tgl_input) BETWEEN '$tgl1' AND '$tgl2') $limit");
            case 3: // input/paket_sumbangan
                $filter = func_get_arg(1);
                return $this->pbs->query("
                SELECT tps.id_paket_sumbangan, tps.id_donatur, td.kode_donatur, td.nama_id AS nama_donatur, tps.id_kolektor, tk.kode_kolektor, tk.nama AS nama_kolektor, 
                tps.id_paket, tp.kode_paket, tp.nama_paket, tp.nilai_paket, tps.jumlah_paket, tps.ket, tps.tgl_jatuh_tempo, 
                GROUP_CONCAT(tps1.biaowen ORDER BY tps1.nmr SEPARATOR '#') AS biaowen_list
                FROM tpaket_sumbangan tps
                JOIN tdonatur td ON tps.id_donatur = td.id_donatur
                JOIN tkolektor tk ON tps.id_kolektor = tk.id_kolektor
                JOIN tpaket tp ON tps.id_paket = tp.id_paket
                LEFT JOIN tpaket_sumbangan1 tps1 ON tps.id_paket_sumbangan = tps1.id_paket_sumbangan
                WHERE tps.id_paket_sumbangan = '$filter' GROUP BY tps.id_paket");
            case 4: // tampil/paket_sumbangan/print_paket_sumbangan
                $id_paket_sumbangan = func_get_arg(1);
                return $this->pbs->query("
                SELECT id_paket_sumbangan, nama_paket, kode_kolektor, nama_kolektor, kode_donatur, nama_donatur, kota_domisili, alamat, nilai_paket, 
                total_donasi, sisa, pembayaran_terakhir, jumlah_biaowen, biaowen_list, tgl_donasi, no_kwitansi, jumlah_donasi, metode_pembayaran, ket
                FROM (
                    SELECT id_paket_sumbangan, nama_paket, kode_kolektor, nama_kolektor, kode_donatur, nama_donatur, kota_domisili, alamat, nilai_paket, 
                    total_donasi, sisa, pembayaran_terakhir, SUM(jumlah_biaowen) AS jumlah_biaowen, GROUP_CONCAT(biaowen_list) AS biaowen_list,
                    NULL AS tgl_donasi, NULL AS no_kwitansi, NULL AS jumlah_donasi, NULL AS metode_pembayaran, NULL AS ket
                    FROM (
                        SELECT tps.id_paket_sumbangan, tp.nama_paket, tk.kode_kolektor, tk.nama AS nama_kolektor, td.kode_donatur, td.nama_id AS nama_donatur, 
                        td.kota_domisili, td.alamat, (tp.nilai_paket * tps.jumlah_paket) AS nilai_paket, tps.total_donasi, tp.nilai_paket * tps.jumlah_paket - tps.total_donasi AS sisa, 
                        MAX(ts.tgl_donasi) AS pembayaran_terakhir, 0 AS jumlah_biaowen, NULL AS biaowen_list
                        FROM tpaket_sumbangan tps
                        JOIN tdonatur td ON tps.id_donatur = td.id_donatur
                        JOIN tkolektor tk ON tps.id_kolektor = tk.id_kolektor
                        JOIN tpaket tp ON tps.id_paket = tp.id_paket
                        LEFT JOIN tsumbangan ts ON tps.id_paket_sumbangan = ts.id_paket_sumbangan
                        WHERE tps.id_paket_sumbangan = '$id_paket_sumbangan'
                        GROUP BY tps.id_paket_sumbangan
                        
                        UNION ALL
                        SELECT id_paket_sumbangan, NULL AS nama_paket, NULL AS kode_kolektor, NULL AS nama_kolektor, NULL AS kode_donatur, NULL AS nama_donatur, 
                        NULL AS kota_domisili, NULL AS alamat, NULL AS nilai_paket, NULL AS total_donasi, NULL AS sisa, NULL AS pembayaran_terakhir, 
                        COUNT(biaowen) AS jumlah_biaowen, GROUP_CONCAT(biaowen ORDER BY biaowen SEPARATOR '#') AS biaowen_list
                        FROM (
                        SELECT id_paket_sumbangan, lunas, nmr, biaowen 
                        FROM (
                            SELECT tps1.id_paket_sumbangan, 0 AS lunas, tps1.nmr, tps1.biaowen
                            FROM tpaket_sumbangan1 tps1
                            WHERE tps1.id_paket_sumbangan = '$id_paket_sumbangan'
                            
                            UNION ALL
                            SELECT ts.id_paket_sumbangan, ts1.lunas, ts1.nmr, ts1.biaowen
                            FROM tsumbangan ts
                            JOIN tsumbangan1 ts1 ON ts.id_sumbangan = ts1.id_sumbangan
                            WHERE ts.id_paket_sumbangan = '$id_paket_sumbangan'
                            ORDER BY lunas DESC
                        ) t1
                        GROUP BY id_paket_sumbangan, biaowen
                        ) t1
                        GROUP BY id_paket_sumbangan
                    ) t1
                    GROUP BY id_paket_sumbangan
                    
                    UNION ALL 
                    SELECT ts.id_paket_sumbangan, NULL AS nama_paket, NULL AS kode_kolektor, NULL AS nama_kolektor, NULL AS kode_donatur, NULL AS nama_donatur, 
                    NULL AS kota_domisili, NULL AS alamat, NULL AS nilai_paket, NULL AS total_donasi, NULL AS sisa, NULL AS pembayaran_terakhir, 
                    NULL AS jumlah_biaowen, NULL AS biaowen_list, ts.tgl_donasi, ts.no_kwitansi, ts.jumlah_donasi, ts.metode_pembayaran, ts.ket
                    FROM tsumbangan ts
                    WHERE ts.id_paket_sumbangan = '$id_paket_sumbangan'
                ) t1
                ORDER BY id_paket_sumbangan");
            case 5: // input/souvenir_keluar
                return $this->pbs->query("
                SELECT tps.id_paket_sumbangan, tp.nama_paket, td.nama_id AS nama_donatur, tps.total_donasi
                FROM tpaket_sumbangan tps
                JOIN tpaket tp ON tps.id_paket = tp.id_paket
                JOIN tdonatur td ON tps.id_donatur = td.id_donatur
                WHERE (tp.nilai_paket IS NULL AND tps.total_donasi > 0) OR (tp.nilai_paket * tps.jumlah_paket - tps.total_donasi <= 0 AND tps.id_souvenir2 IS NULL)");
            case 6: // input/c_souvenir_keluar
                $filter = func_get_arg(1);
                return $this->pbs->query("
                SELECT tps.id_paket_sumbangan
                FROM tpaket_sumbangan tps
                JOIN tpaket tp ON tps.id_paket = tp.id_paket
                WHERE tps.id_paket_sumbangan = '$filter' AND ((tp.nilai_paket IS NULL AND tps.total_donasi > 0) OR 
                (tp.nilai_paket * tps.jumlah_paket - tps.total_donasi <= 0 AND tps.id_souvenir2 IS NULL))");
            case 7: // input/c_sumbangan
                $id_sumbangan = func_get_arg(1);
                $id_paket_sumbangan = func_get_arg(2);
                return $this->pbs->query("
                SELECT tp.nilai_paket * tps.jumlah_paket - tps.total_donasi + IFNULL(ts.jumlah_donasi, 0) AS sisa_nilai_paket
                FROM tpaket_sumbangan tps
                JOIN tpaket tp ON tps.id_paket = tp.id_paket
                LEFT JOIN (
                    SELECT id_paket_sumbangan, jumlah_donasi FROM tsumbangan WHERE id_sumbangan = '$id_sumbangan'
                ) ts ON tps.id_paket_sumbangan = ts.id_paket_sumbangan
                WHERE tps.id_paket_sumbangan = '$id_paket_sumbangan'");
            case 8: // daftar/souvenir/delete
                $id_souvenir = func_get_arg(1);
                return $this->pbs->query("
                SELECT ts.id_souvenir, tps.id_souvenir2
                FROM tpaket_sumbangan tps
                JOIN tsouvenir2 ts2 ON tps.id_souvenir2 = ts2.id
                JOIN tsouvenir ts ON ts2.id_souvenir = ts.id_souvenir
                WHERE ts.id_souvenir = '$id_souvenir'");
        }
    }

    function tpaket_sumbangan_get_month() {
        return $this->pbs->query("
        SELECT YEAR(tps.tgl_input) AS thn, tb.kode AS bln, tb.nama AS nm_bln
        FROM tpaket_sumbangan tps                   
        JOIN tbulan tb ON MONTH(tps.tgl_input) = tb.kode
        GROUP BY YEAR(tps.tgl_input), MONTH(tps.tgl_input)
        ORDER BY tps.tgl_input DESC");
    }

    function tpaket_sumbangan_put($id_paket_sumbangan, $paket_sumbangan) {
        $this->trans_begin();
        if (!is_empty($id_paket_sumbangan)) $this->pbs->query("SET @id_paket_sumbangan = $id_paket_sumbangan");
        $this->pbs->query("CALL save_paket_sumbangan(@id_paket_sumbangan, ?, ?, ?, ?, ?, ?)", $paket_sumbangan);
        return $this->pbs->query("SELECT @id_paket_sumbangan AS id_paket_sumbangan");
    }

    function tpaket_sumbangan_rollback($id_paket_sumbangan) {
        $this->rollback();
        $this->pbs->query("ALTER TABLE tpaket_sumbangan AUTO_INCREMENT = ?", ($id_paket_sumbangan - 1));
    }

    /** tpaket_sumbangan1 */
    function tpaket_sumbangan1_delete($id_paket_sumbangan) {
        return $this->pbs->where("id_paket_sumbangan", $id_paket_sumbangan)->delete("tpaket_sumbangan1");
    }

    function tpaket_sumbangan1_put($paket_sumbangan1) {
        $result = $this->pbs->insert_batch("tpaket_sumbangan1", $paket_sumbangan1);
        return $result === count($paket_sumbangan1);
    }
    
    /** tsatuan */
    function tsatuan_get() {
        return $this->pbs->get("tsatuan");
    }

    /** tsouvenir */
    function tsouvenir_delete($id_souvenir) {
        return $this->pbs->where("id_souvenir", $id_souvenir)->delete("tsouvenir");
    }

    function tsouvenir_get() {
        switch(func_get_arg(0)) {
            case 1: // ajax_repository
                return $this->pbs->select(func_get_arg(1))->get("tsouvenir");
            case 2: // master/souvenir, input/c_souvenir_masuk
                $this->pbs->select(func_get_arg(1));
                if (func_get_arg(2)[0] === "where") {
                    $this->pbs->where(func_get_arg(2)[1], func_get_arg(2)[2]);
                } else if (func_get_arg(2)[0] === "like") {
                    $this->pbs->like("kode_souvenir", func_get_arg(2)[1])->or_like("nama", func_get_arg(2)[1]);
                }
                return $this->pbs->get("tsouvenir");
            case 3: // input/c_souvenir_keluar, input/souvenir_keluar, daftar/souvenir
                $filter = func_get_arg(1) === "=" ? "= '" .func_get_arg(2). "'" : 
                "IN (SELECT id_souvenir FROM tsouvenir WHERE kode_souvenir LIKE '%" .func_get_arg(2). "%' OR nama LIKE '%" .func_get_arg(2). "%')";
                $filter2 = "LEFT JOIN (
                    SELECT id_souvenir, stok_keluar
                    FROM tsouvenir2
                    WHERE id = '" .func_get_arg(3). "'
                ) t1 ON ts2.id_souvenir = t1.id_souvenir";
                $limit = "";
                if (func_get_arg(1) === "IN") {
                    $limit = is_empty(func_get_arg(4)) ? "" : "LIMIT " .(func_get_arg(4) * func_get_arg(5)). ", " .func_get_arg(5);
                }
                return $this->pbs->query("
                SELECT id_souvenir, kode_souvenir, nama, SUM(stok_awal) AS stok_awal, SUM(stok_masuk) AS stok_masuk, SUM(stok_keluar) AS stok_keluar,
                (SUM(stok_awal) + SUM(stok_masuk) - SUM(stok_keluar)) AS stok_akhir FROM (
                    SELECT ts.id_souvenir, ts.kode_souvenir, ts.nama, ts.stok_awal, 0 AS stok_masuk, 0 AS stok_keluar
                    FROM tsouvenir ts
                    WHERE ts.id_souvenir $filter
                    
                    UNION ALL
                    SELECT ts1.id_souvenir, NULL AS kode_souvenir, NULL AS nama, 0 AS stok_awal, SUM(ts1.stok_masuk) AS stok_masuk, 0 AS stok_keluar
                    FROM tsouvenir1 ts1
                    WHERE ts1.id_souvenir $filter
                    GROUP BY ts1.id_souvenir
                    
                    UNION ALL
                    SELECT ts2.id_souvenir, NULL AS kode_souvenir, NULL AS nama, 0 AS stok_awal, 0 AS stok_masuk, 
                    (SUM(ts2.stok_keluar) - IFNULL(t1.stok_keluar, 0)) AS stok_keluar
                    FROM tsouvenir2 ts2
                    $filter2
                    WHERE ts2.id_souvenir $filter
                    GROUP BY ts2.id_souvenir
                ) t1
                GROUP BY id_souvenir
                ORDER BY nama");
            case 4: // input/souvenir_keluar
                $id = func_get_arg(1);
                return $this->pbs->query("
                SELECT ts2.id, ts2.id_paket_sumbangan, tp.nama_paket, td.nama_id AS nama_donatur, tps.total_donasi, ts2.penerima_souvenir, ts.id_souvenir, 
                ts.kode_souvenir, ts.nama AS nama_souvenir, t1.stok_akhir AS stok_tersedia, ts2.stok_keluar, ts2.tgl_serah, ts2.ket
                FROM tsouvenir2 ts2
                JOIN tpaket_sumbangan tps ON ts2.id_paket_sumbangan = tps.id_paket_sumbangan
                JOIN tpaket tp ON tps.id_paket = tp.id_paket
                JOIN tdonatur td ON tps.id_donatur = td.id_donatur
                JOIN tsouvenir ts ON ts2.id_souvenir = ts.id_souvenir
                JOIN (
                    SELECT id_souvenir, (SUM(stok_awal) + SUM(stok_masuk) - SUM(stok_keluar)) AS stok_akhir FROM (
                    SELECT ts.id_souvenir, ts.stok_awal, 0 AS stok_masuk, 0 AS stok_keluar
                    FROM tsouvenir ts
                    
                    UNION ALL
                    SELECT ts1.id_souvenir, 0 AS stok_awal, SUM(ts1.stok_masuk) AS stok_masuk, 0 AS stok_keluar
                    FROM tsouvenir1 ts1
                    GROUP BY ts1.id_souvenir
                    
                    UNION ALL
                    SELECT ts2.id_souvenir, 0 AS stok_awal, 0 AS stok_masuk, 
                    (SUM(ts2.stok_keluar) - IFNULL(t1.stok_keluar, 0)) AS stok_keluar
                    FROM tsouvenir2 ts2
                    LEFT JOIN (
                        SELECT id_souvenir, stok_keluar
                        FROM tsouvenir2
                        WHERE id = '$id'
                    ) t1 ON ts2.id_souvenir = t1.id_souvenir
                    GROUP BY ts2.id_souvenir
                    ) t1
                    GROUP BY id_souvenir
                ) t1 ON ts.id_souvenir = t1.id_souvenir
                WHERE id = '$id'");

        }
    }

    function tsouvenir_put($souvenir) {
        return $this->pbs->query("CALL save_souvenir(?, ?, ?, ?, ?, ?, ?)", $souvenir);
    }

    /** tsouvenir1 */
    function tsouvenir1_delete($id) {
        return $this->pbs->where("id", $id)->delete("tsouvenir1");
    }

    function tsouvenir1_get() {
        switch(func_get_arg(0)) {
            case 1: // daftar/souvenir/delete
                return $this->pbs->select(func_get_arg(1))->where(func_get_arg(2)[0], func_get_arg(2)[1])->get("tsouvenir1");
            case 2: // tampil/souvenir_masuk/get_souvenir1_list
                $filter = func_get_arg(1);
                $tgl1 = func_get_arg(2);
                $tgl2 = func_get_arg(3);
                $limit = is_empty(func_get_arg(4)) ? "" : "LIMIT " .(func_get_arg(4) * func_get_arg(5)). ", " .func_get_arg(5);
                return $this->pbs->query("
                SELECT ts1.id, ts.kode_souvenir, ts.nama, ts1.stok_masuk, ts1.ket, DATE(ts1.tgl_input) AS tgl_input
                FROM tsouvenir1 ts1
                JOIN tsouvenir ts ON ts1.id_souvenir = ts.id_souvenir
                WHERE (ts.kode_souvenir LIKE '%$filter%' OR ts.nama LIKE '%$filter%') AND (DATE(ts1.tgl_input) BETWEEN '$tgl1' AND '$tgl2')
                $limit");
            case 3: // input/souvenir_masuk
                $id = func_get_arg(1);
                return $this->pbs->query("
                SELECT ts1.id, ts1.id_souvenir, ts.kode_souvenir, ts.nama, ts1.stok_masuk, ts1.ket
                FROM tsouvenir1 ts1
                JOIN tsouvenir ts ON ts1.id_souvenir = ts.id_souvenir
                WHERE ts1.id = '$id'");
        }
    }

    function tsouvenir1_get_month() {
        return $this->pbs->query("
        SELECT YEAR(ts1.tgl_input) AS thn, tb.kode AS bln, tb.nama AS nm_bln
        FROM tsouvenir1 ts1
        JOIN tbulan tb ON MONTH(ts1.tgl_input) = tb.kode
        GROUP BY YEAR(ts1.tgl_input), MONTH(ts1.tgl_input)
        ORDER BY ts1.tgl_input DESC");
    }

    function tsouvenir1_put($souvenir1) {
        return $this->pbs->query("CALL save_souvenir1(?, ?, ?, ?)", $souvenir1);
    }

    /** tsouvenir2 */
    function tsouvenir2_delete($id) {
        return $this->pbs->where("id", $id)->delete("tsouvenir2");
    }

    function tsouvenir2_get() {
        switch(func_get_arg(0)) {
            case 1: // daftar/souvenir/delete
                return $this->pbs->select(func_get_arg(1))->where(func_get_arg(2)[0], func_get_arg(2)[1])->get("tsouvenir2");
            case 2: // tampil/souvenir_keluar/get_souvenir2_list
                $filter = func_get_arg(1);
                $tgl1 = func_get_arg(2);
                $tgl2 = func_get_arg(3);
                $limit = is_empty(func_get_arg(4)) ? "" : "LIMIT " .(func_get_arg(4) * func_get_arg(5)). ", " .func_get_arg(5);
                return $this->pbs->query("
                SELECT ts2.id, ts.kode_souvenir, ts.nama, ts2.penerima_souvenir, ts2.stok_keluar, ts2.ket, ts2.tgl_serah
                FROM tsouvenir2 ts2
                JOIN tsouvenir ts ON ts2.id_souvenir = ts.id_souvenir
                WHERE (ts.kode_souvenir LIKE '%$filter%' OR ts.nama LIKE '%$filter%' OR ts2.penerima_souvenir LIKE '%$filter%') AND 
                (ts2.tgl_serah BETWEEN '$tgl1' AND '$tgl2')
                $limit");
        }
    }

    function tsouvenir2_get_month() {
        return $this->pbs->query("
        SELECT YEAR(ts2.tgl_serah) AS thn, tb.kode AS bln, tb.nama AS nm_bln
        FROM tsouvenir2 ts2
        JOIN tbulan tb ON MONTH(ts2.tgl_serah) = tb.kode
        GROUP BY YEAR(ts2.tgl_serah), MONTH(ts2.tgl_serah)
        ORDER BY ts2.tgl_serah DESC");
    }

    function tsouvenir2_put($souvenir2) {
        return $this->pbs->query("CALL save_souvenir2(?, ?, ?, ?, ?, ?, ?)", $souvenir2);
    }

    /** tsumbangan */
    function tsumbangan_delete($id_sumbangan) {
        return $this->pbs->where("id_sumbangan", $id_sumbangan)->delete("tsumbangan");
    }

    function tsumbangan_get() {
        switch (func_get_arg(0)) {
            case 1: // tampil/sumbangan/print
                $id_sumbangan = func_get_arg(1);
                return $this->pbs->query("
                SELECT ts.no_kwitansi, ts.nama_penyumbang, td.kode_donatur, td.nama_id AS nama_donatur, tp.nama_paket, ts.jumlah_donasi, ts.tgl_donasi
                FROM tsumbangan ts
                JOIN tpaket_sumbangan tps ON ts.id_paket_sumbangan = tps.id_paket_sumbangan
                JOIN tpaket tp ON tps.id_paket = tp.id_paket
                JOIN tdonatur td ON tps.id_donatur = td.id_donatur
                WHERE ts.id_sumbangan = '$id_sumbangan'");
            case 2: // tampil/sumbangan
                $filter = func_get_arg(1);
                $tgl1 = func_get_arg(2);
                $tgl2 = func_get_arg(3);
                $limit = is_empty(func_get_arg(4)) ? "" : "LIMIT " .(func_get_arg(4) * func_get_arg(5)). ", " .func_get_arg(5);
                return $this->pbs->query("
                SELECT ts.id_sumbangan, ts.no_kwitansi, td.kode_donatur, td.nama_id AS nama_donatur, ts.tgl_donasi, tp.nama_paket, ts.jumlah_donasi, 
                ts.metode_pembayaran, ts.ket
                FROM tsumbangan ts 
                JOIN tpaket_sumbangan tps ON ts.id_paket_sumbangan = tps.id_paket_sumbangan
                JOIN tpaket tp ON tps.id_paket = tp.id_paket
                JOIN tdonatur td ON tps.id_donatur = td.id_donatur
                WHERE (ts.no_kwitansi LIKE '%$filter%' OR td.kode_donatur LIKE '%$filter%' OR td.nama_id LIKE '%$filter%') AND 
                (ts.tgl_donasi BETWEEN '$tgl1' AND '$tgl2') ORDER BY ts.id_sumbangan $limit");
            case 3: // input/sumbangan
                $id_sumbangan = func_get_arg(1);
                return $this->pbs->query("
                SELECT ts.id_sumbangan, ts.no_kwitansi, td.kode_donatur, td.nama_id AS nama_donatur, ts.nama_penyumbang, ts.tgl_donasi, 
                tps.id_paket_sumbangan, tp.nama_paket, (tp.nilai_paket * tps.jumlah_paket - tps.total_donasi + ts.jumlah_donasi) AS sisa_nilai_paket, 
                ts.jumlah_donasi, ts.metode_pembayaran, ts.id_bank, tb.an, tb.no_rek, ts.rek_pengirim, ts.ket, 
                GROUP_CONCAT(CONCAT_WS('|', ts1.lunas, ts1.biaowen) ORDER BY ts1.nmr SEPARATOR ';') AS biaowen_list
                FROM tsumbangan ts
                JOIN tsumbangan1 ts1 ON ts.id_sumbangan = ts1.id_sumbangan
                JOIN tpaket_sumbangan tps ON ts.id_paket_sumbangan = tps.id_paket_sumbangan
                JOIN tdonatur td ON tps.id_donatur = td.id_donatur
                JOIN tpaket tp ON tps.id_paket = tp.id_paket
                LEFT JOIN tbank tb ON ts.id_bank = tb.id_bank
                WHERE ts.id_sumbangan = '$id_sumbangan'");
            case 4: // input/sumbangan
                $id_sumbangan = func_get_arg(1);
                $no_kwitansi = func_get_arg(2);
                return $this->pbs->select("no_kwitansi")->
                where("id_sumbangan", $id_sumbangan)->
                where("no_kwitansi", $no_kwitansi)->get("tsumbangan");
            case 5: // tampil/paket_sumbangan/delete
                return $this->pbs->select(func_get_arg(1))->where(func_get_arg(2)[0], func_get_arg(2)[1])->get("tsumbangan");
        }
    }

    function tsumbangan_get_month() {
        return $this->pbs->query("
        SELECT YEAR(ts.tgl_donasi) AS thn, tb.kode AS bln, tb.nama AS nm_bln
        FROM tsumbangan ts                   
        JOIN tbulan tb ON MONTH(ts.tgl_donasi) = tb.kode
        GROUP BY YEAR(ts.tgl_donasi), MONTH(ts.tgl_donasi)
        ORDER BY ts.tgl_donasi DESC");
    }

    function tsumbangan_put($id_sumbangan, $sumbangan) {
        $this->pbs->trans_begin();
        if (!is_empty($id_sumbangan)) $this->pbs->query("SET @id_sumbangan = $id_sumbangan");
        $this->pbs->query("CALL save_sumbangan(@id_sumbangan, ?, ?, ?, ?, ?, ?, ?, ?, ?)", $sumbangan);
        return $this->pbs->query("SELECT @id_sumbangan AS id_sumbangan");
    }

    function tsumbangan_rollback($id_sumbangan) {
        $this->rollback();
        $this->pbs->query("ALTER TABLE tsumbangan AUTO_INCREMENT = ?", ($id_sumbangan - 1));
    }

    /** tsumbangan1 */
    function tsumbangan1_delete($id_sumbangan) {
        return $this->pbs->where("id_sumbangan", $id_sumbangan)->delete("tsumbangan1");
    }

    function tsumbangan1_get() {
        switch (func_get_arg(0)) {
            case 1: // input/c_sumbangan:
                $id_paket_sumbangan = func_get_arg(1);
                $id_sumbangan = func_get_arg(2);
                $biaowen = func_get_arg(3);
                return $this->pbs->query("
                SELECT ts1.biaowen 
                FROM tsumbangan1 ts1
                JOIN tsumbangan ts ON ts1.id_sumbangan = ts.id_sumbangan
                WHERE ts1.id_sumbangan IN (SELECT id_sumbangan FROM tsumbangan WHERE id_paket_sumbangan = $id_paket_sumbangan) AND 
                ts1.id_sumbangan != '$id_sumbangan' AND ts1.biaowen = '$biaowen' AND ts1.lunas = 1");
            case 2: // tampil/sumbangan/dialog
                return $this->pbs->select("biaowen, lunas, bakar, tgl_bakar")->where("id_sumbangan", func_get_arg(1))->get("tsumbangan1");
        }
    }

    function tsumbangan1_put($sumbangan1) {
        $result = $this->pbs->insert_batch("tsumbangan1", $sumbangan1);
        return $result === count($sumbangan1);
    }

    /** tuser */
    function tuser_get($data = NULL) {
        $this->pbs->select(!isset($data["select"]) ? "*" : $data["select"]);
        if (isset($data["filter"])) {
            foreach ($data["filter"] as $k => $v) {
                $this->pbs->where("$k", "$v");
            }
        }
        return $this->pbs->get("tuser");
    }

    function tuser_put($data) {
        switch ($data["option"]) {
            case 1: // sistem/tambah_user
                return $this->pbs->insert("tuser", $data["insert"]);
            case 2: // sistem/ubah_password
                return $this->pbs->where("username", $data["update"][0])->update("tuser", $data["update"][1]);
        }
    }

    /** Utility */
    function trans_begin() {
        $this->pbs->trans_begin();
    }

    function commit() {
        $this->pbs->trans_commit();
    }

    function rollback() {
        $this->pbs->trans_rollback();
    }
}
