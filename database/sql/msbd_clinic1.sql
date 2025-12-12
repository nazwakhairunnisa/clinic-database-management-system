-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2025 at 07:55 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `msbd_clinic`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `BatalkanReservasi` (IN `p_id_reservasi` INT, IN `p_alasan` TEXT, IN `p_id_user` INT)   BEGIN
    DECLARE v_status_current VARCHAR(20);
    DECLARE v_id_pasien INT;
    DECLARE v_tanggal_reservasi DATE;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Gagal membatalkan reservasi';
    END;
    
    START TRANSACTION;
    
    -- Cek status reservasi dan data pasien
    SELECT status, id_pasien, tanggal_reservasi 
    INTO v_status_current, v_id_pasien, v_tanggal_reservasi
    FROM reservasi
    WHERE id_reservasi = p_id_reservasi
    FOR UPDATE;
    
    -- Validasi reservasi ditemukan
    IF v_status_current IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Reservasi tidak ditemukan';
    END IF;
    
    -- Validasi: tidak bisa batalkan yang sudah done
    IF v_status_current = 'done' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Tidak bisa membatalkan reservasi yang sudah selesai';
    END IF;
    
    IF v_status_current = 'cancelled' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Reservasi sudah dibatalkan sebelumnya';
    END IF;
    
    -- Update status reservasi
    UPDATE reservasi
    SET status = 'cancelled',
        keterangan = CONCAT(COALESCE(keterangan, ''), ' | DIBATALKAN: ', p_alasan),
        updated_at = NOW()
    WHERE id_reservasi = p_id_reservasi;
    -- Trigger update_jadwal_reservasi akan update jadwal menjadi 'available'
    
    -- Log activity dengan detail
    INSERT INTO log_activity(id_user, activity, created_at)
    VALUES(
        p_id_user, 
        CONCAT('Membatalkan reservasi ID: ', p_id_reservasi, 
               ' (Pasien ID: ', v_id_pasien, ', Tanggal: ', v_tanggal_reservasi, 
               ') - Alasan: ', p_alasan), 
        NOW()
    );
    
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `BuatRekamMedisLengkap` (IN `p_id_pasien` INT, IN `p_keluhan` TEXT, IN `p_jenis_kulit` VARCHAR(20), IN `p_kelembapan` VARCHAR(20), IN `p_kondisi_pasien` VARCHAR(20), IN `p_produk_terakhir` TEXT, IN `p_riwayat_penyakit` TEXT, IN `p_riwayat_pengobatan` TEXT, IN `p_riwayat_alergi` TEXT, IN `p_kondisi_kulit` JSON, OUT `p_id_rekam_medis_baru` INT)   BEGIN
    DECLARE v_index INT DEFAULT 0;
    DECLARE v_array_length INT;
    DECLARE v_jenis_kondisi TEXT;
    DECLARE v_status_kondisi VARCHAR(20);
    DECLARE v_area VARCHAR(255);
    DECLARE v_derajat VARCHAR(20);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Gagal membuat rekam medis.';
    END;
    
    START TRANSACTION;
    
    -- Insert rekam medis utama
    INSERT INTO rekam_medis (
        id_pasien, keluhan, jenis_kulit, kelembapan, 
        kondisi_pasien, produk_terakhir_dipakai,
        riwayat_penyakit, riwwayat_pengobatan, riwayat_alergi,
        created_at
    ) VALUES (
        p_id_pasien, p_keluhan, p_jenis_kulit, p_kelembapan,
        p_kondisi_pasien, p_produk_terakhir,
        p_riwayat_penyakit, p_riwayat_pengobatan, p_riwayat_alergi,
        NOW()
    );
    
    SET p_id_rekam_medis_baru = LAST_INSERT_ID();
    
    -- Insert detail kondisi kulit (loop)
    SET v_array_length = JSON_LENGTH(p_kondisi_kulit);
    
    WHILE v_index < v_array_length DO
        SET v_jenis_kondisi = JSON_UNQUOTE(JSON_EXTRACT(p_kondisi_kulit, CONCAT('$[', v_index, '].jenis_kondisi')));
        SET v_status_kondisi = JSON_UNQUOTE(JSON_EXTRACT(p_kondisi_kulit, CONCAT('$[', v_index, '].status_kondisi')));
        SET v_area = JSON_UNQUOTE(JSON_EXTRACT(p_kondisi_kulit, CONCAT('$[', v_index, '].area')));
        SET v_derajat = JSON_UNQUOTE(JSON_EXTRACT(p_kondisi_kulit, CONCAT('$[', v_index, '].derajat')));
        
        INSERT INTO rekam_kondisi_kulit (
            id_rekam_medis, jenis_kondisi, status_kondisi, 
            area, derajat, created_at, updated_at
        ) VALUES (
            p_id_rekam_medis_baru, v_jenis_kondisi, v_status_kondisi,
            v_area, NULLIF(v_derajat, 'null'), NOW(), NOW()
        );
        
        SET v_index = v_index + 1;
    END WHILE;
    
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `BuatReservasiBaru` (IN `p_id_pasien` INT, IN `p_id_user` INT, IN `p_id_jadwal` INT, IN `p_tanggal_reservasi` DATE, IN `p_jam_reservasi` TIME, IN `p_metode_reservasi` VARCHAR(20), IN `p_keterangan` TEXT, IN `p_treatments` JSON, OUT `p_id_reservasi_baru` INT)   BEGIN
    DECLARE v_status_jadwal VARCHAR(20);
    DECLARE v_total_harga DECIMAL(10,2) DEFAULT 0;

    DECLARE v_index INT DEFAULT 0;
    DECLARE v_array_length INT DEFAULT 0;

    DECLARE v_id_treatment INT;
    DECLARE v_quantity INT;

    DECLARE v_harga DECIMAL(10,2);
    DECLARE v_harga_promo DECIMAL(10,2);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Gagal membuat reservasi baru.';
    END;

    START TRANSACTION;

    -- Cek jadwal
    SELECT status INTO v_status_jadwal
    FROM jadwal_operasional
    WHERE id_jadwal = p_id_jadwal
    FOR UPDATE;

    IF v_status_jadwal IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Jadwal tidak ditemukan.';
    END IF;

    IF v_status_jadwal <> 'available' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Jadwal tidak tersedia.';
    END IF;
    
    -- Insert ke tabel reservasi
    INSERT INTO reservasi(
        id_pasien, id_user, id_jadwal,
        tanggal_reservasi, jam_reservasi,
        status, metode_reservasi, keterangan,
        created_at, updated_at
    )
    VALUES (
        p_id_pasien, p_id_user, p_id_jadwal,
        p_tanggal_reservasi, p_jam_reservasi,
        'requested', p_metode_reservasi, p_keterangan,
        NOW(), NOW()
    );

    SET p_id_reservasi_baru = LAST_INSERT_ID();

    -- Panjang array JSON
    SET v_array_length = JSON_LENGTH(p_treatments);

    -- Loop tiap treatment
    WHILE v_index < v_array_length DO

        SET v_id_treatment = JSON_EXTRACT(p_treatments, CONCAT('$[', v_index, '].id_treatment'));
        SET v_id_treatment = JSON_UNQUOTE(v_id_treatment);

        SET v_quantity = JSON_EXTRACT(p_treatments, CONCAT('$[', v_index, '].quantity'));
        SET v_quantity = JSON_UNQUOTE(v_quantity);

        -- Ambil harga normal
        SELECT harga INTO v_harga
        FROM treatment
        WHERE id_treatment = v_id_treatment;

        -- Ambil promo bila ada
        SELECT harga_promo INTO v_harga_promo
        FROM promo
        WHERE id_treatment = v_id_treatment
          AND p_tanggal_reservasi BETWEEN periode_mulai AND periode_selesai
          AND deleted_at IS NULL
        LIMIT 1;

        -- Jika promo ada, pakai harga promo
        IF v_harga_promo IS NOT NULL THEN
            SET v_harga = v_harga_promo;
        END IF;

        -- Insert detail reservasi
        INSERT INTO detail_reservasi(
            id_reservasi, id_treatment,
            harga_saat_reservasi, quantity,
            created_at, updated_at
        ) VALUES (
            p_id_reservasi_baru, v_id_treatment,
            v_harga, v_quantity,
            NOW(), NOW()
        );

        -- Tambah total
        SET v_total_harga = v_total_harga + (v_harga * v_quantity);

        SET v_index = v_index + 1;
    END WHILE;

    -- Insert pembayaran
    INSERT INTO pembayaran(
        id_reservasi, id_user,
        tanggal_pembayaran,
        total_pembayaran,
        metode_pembayaran, status_pembayaran,
        created_at, updated_at
    )
    VALUES (
        p_id_reservasi_baru, p_id_user,
        p_tanggal_reservasi,
        v_total_harga,
        'cash',
        'belum',
        NOW(), NOW()
    );

    -- Log
    INSERT INTO log_activity(id_user, activity, created_at)
    VALUES (p_id_user, CONCAT('Membuat reservasi ', p_id_reservasi_baru), NOW());

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `BuatResumePasien` (IN `p_id_pasien` INT, IN `p_id_user` INT, IN `p_tanggal_kunjungan` DATE, IN `p_anamnesa` TEXT, IN `p_riwayat_eksfo` TEXT, IN `p_terapi` TEXT, IN `p_foto_sebelum` VARCHAR(255), IN `p_foto_sesudah` VARCHAR(255), OUT `p_id_resume_baru` INT)   BEGIN
    DECLARE v_nama_pasien VARCHAR(255);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Gagal membuat resume pasien.';
    END;
    
    START TRANSACTION;
    
    -- Validasi pasien exist
    SELECT CONCAT(nama_depan, ' ', nama_belakang) INTO v_nama_pasien
    FROM pasien
    WHERE id_pasien = p_id_pasien
    AND deleted_at IS NULL;
    
    IF v_nama_pasien IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Pasien tidak ditemukan.';
    END IF;
    
    -- Insert resume
    INSERT INTO resume_pasien (
        id_pasien, tanggal_kunjungan, anamnesa,
        riwayat_eksfo, terapi, 
        foto_sebelum_treatment, foto_sesudah_treatment,
        created_at, updated_at
    ) VALUES (
        p_id_pasien, p_tanggal_kunjungan, p_anamnesa,
        p_riwayat_eksfo, p_terapi,
        p_foto_sebelum, p_foto_sesudah,
        NOW(), NOW()
    );
    
    SET p_id_resume_baru = LAST_INSERT_ID();
    
    -- Log activity
    INSERT INTO log_activity(id_user, activity, created_at)
    VALUES (
        p_id_user, 
        CONCAT('Membuat resume kunjungan untuk pasien: ', v_nama_pasien, 
               ' - Tanggal: ', p_tanggal_kunjungan),
        NOW()
    );
    
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GunakanObat` (IN `p_id_obat` INT, IN `p_jumlah` INT, IN `p_id_treatment` INT)   BEGIN
    DECLARE v_stok INT;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Terjadi kesalahan pada penggunaan obat.';
    END;

    START TRANSACTION;

    SELECT stok_terkini INTO v_stok
    FROM stok_obat
    WHERE id_obat = p_id_obat
    FOR UPDATE;

    IF v_stok < p_jumlah THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Stok obat tidak mencukupi.';
    END IF;

    UPDATE stok_obat
    SET stok_terkini = stok_terkini - p_jumlah,
        tanggal_update = NOW()
    WHERE id_obat = p_id_obat;

    INSERT INTO pemakaian_obat(id_obat, id_treatment, jumlah_pakai, tanggal_pakai)
    VALUES(p_id_obat, p_id_treatment, p_jumlah, NOW());

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `HapusPasien` (IN `p_id_pasien` INT, IN `p_id_user` INT)   BEGIN
    DECLARE v_nama_pasien VARCHAR(255);
    DECLARE v_ada_reservasi INT;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Gagal menghapus pasien.';
    END;
    
    START TRANSACTION;
    
    -- Ambil nama pasien
    SELECT CONCAT(nama_depan, ' ', nama_belakang) INTO v_nama_pasien
    FROM pasien
    WHERE id_pasien = p_id_pasien
    AND deleted_at IS NULL;
    
    IF v_nama_pasien IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Pasien tidak ditemukan.';
    END IF;
    
    -- Cek apakah ada reservasi aktif
    SELECT COUNT(*) INTO v_ada_reservasi
    FROM reservasi
    WHERE id_pasien = p_id_pasien
    AND status IN ('requested', 'confirmed');
    
    IF v_ada_reservasi > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Tidak bisa menghapus pasien yang memiliki reservasi aktif.';
    END IF;
    
    -- Soft delete
    UPDATE pasien
    SET deleted_at = NOW()
    WHERE id_pasien = p_id_pasien;
    
    -- Log activity
    INSERT INTO log_activity(id_user, activity, created_at)
    VALUES (
        p_id_user,
        CONCAT('Menghapus data pasien: ', v_nama_pasien, ' (ID: ', p_id_pasien, ')'),
        NOW()
    );
    
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `KonfirmasiReservasi` (IN `p_id_reservasi` INT, IN `p_id_user` INT)   BEGIN
    DECLARE v_id_jadwal INT;
    DECLARE v_status_jadwal VARCHAR(20);
    DECLARE v_status_reservasi VARCHAR(20);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Gagal mengkonfirmasi reservasi';
    END;
    
    START TRANSACTION;
    
    -- Ambil data reservasi & jadwal
    SELECT 
        r.id_jadwal,
        r.status,
        j.status
    INTO 
        v_id_jadwal,
        v_status_reservasi,
        v_status_jadwal
    FROM reservasi r
    JOIN jadwal_operasional j ON r.id_jadwal = j.id_jadwal
    WHERE r.id_reservasi = p_id_reservasi
    FOR UPDATE;
    
    -- Validasi status reservasi
    IF v_status_reservasi = 'confirmed' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Reservasi sudah dikonfirmasi sebelumnya';
    END IF;
    
    IF v_status_reservasi = 'cancelled' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Tidak bisa konfirmasi reservasi yang sudah dibatalkan';
    END IF;
    
    -- Validasi status jadwal
    IF v_status_jadwal = 'booked' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Jadwal sudah dibooking oleh reservasi lain';
    END IF;
    
    -- Update status reservasi
    UPDATE reservasi
    SET status = 'confirmed',
        updated_at = NOW()
    WHERE id_reservasi = p_id_reservasi;
    -- Trigger update_jadwal_reservasi akan update jadwal menjadi 'booked'
    
    -- Log activity
    INSERT INTO log_activity(id_user, activity, created_at)
    VALUES(p_id_user, CONCAT('Mengkonfirmasi reservasi ID: ', p_id_reservasi), NOW());
    
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `LaporanKeuanganBulanan` (IN `p_bulan` INT, IN `p_tahun` INT, OUT `p_total_pemasukan` DECIMAL(10,2), OUT `p_total_pengeluaran` DECIMAL(10,2), OUT `p_saldo` DECIMAL(10,2))   BEGIN
    -- Hitung total pemasukan
    SELECT COALESCE(SUM(jumlah), 0) INTO p_total_pemasukan
    FROM transaksi_keuangan
    WHERE jenis_transaksi = 'pemasukan'
      AND MONTH(tanggal_transaksi) = p_bulan
      AND YEAR(tanggal_transaksi) = p_tahun;
    
    -- Hitung total pengeluaran
    SELECT COALESCE(SUM(jumlah), 0) INTO p_total_pengeluaran
    FROM transaksi_keuangan
    WHERE jenis_transaksi = 'pengeluaran'
      AND MONTH(tanggal_transaksi) = p_bulan
      AND YEAR(tanggal_transaksi) = p_tahun;
    
    -- Hitung saldo
    SET p_saldo = p_total_pemasukan - p_total_pengeluaran;
    
    -- Tampilkan ringkasan
    SELECT 
        p_bulan as bulan,
        p_tahun as tahun,
        p_total_pemasukan as total_pemasukan,
        p_total_pengeluaran as total_pengeluaran,
        p_saldo as saldo_bersih;
    
    -- Tampilkan detail transaksi per jenis
    SELECT 
        jenis_transaksi,
        metode_pembayaran,
        COUNT(*) as jumlah_transaksi,
        SUM(jumlah) as total_nilai
    FROM transaksi_keuangan
    WHERE MONTH(tanggal_transaksi) = p_bulan
      AND YEAR(tanggal_transaksi) = p_tahun
    GROUP BY jenis_transaksi, metode_pembayaran
    ORDER BY jenis_transaksi, total_nilai DESC;
    
    -- Tampilkan detail transaksi lengkap
    SELECT 
        DATE_FORMAT(tanggal_transaksi, '%d/%m/%Y') as tanggal,
        nama_transaksi,
        jenis_transaksi,
        metode_pembayaran,
        FORMAT(jumlah, 0) as jumlah_rupiah,
        keterangan
    FROM transaksi_keuangan
    WHERE MONTH(tanggal_transaksi) = p_bulan
      AND YEAR(tanggal_transaksi) = p_tahun
    ORDER BY tanggal_transaksi DESC, jenis_transaksi;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ProsesLunaskanPembayaran` (IN `p_id_pembayaran` INT, IN `p_id_user` INT, IN `p_bukti_pembayaran` VARCHAR(255))   BEGIN
    DECLARE v_total DECIMAL(10,2);
    DECLARE v_id_reservasi INT;
    DECLARE v_metode_pembayaran VARCHAR(20);
    DECLARE v_status_current VARCHAR(20);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Gagal memproses pelunasan pembayaran';
    END;
    
    START TRANSACTION;
    
    -- Ambil data pembayaran & validasi
    SELECT 
        total_pembayaran, 
        id_reservasi, 
        metode_pembayaran,
        status_pembayaran
    INTO 
        v_total, 
        v_id_reservasi, 
        v_metode_pembayaran,
        v_status_current
    FROM pembayaran
    WHERE id_pembayaran = p_id_pembayaran
    FOR UPDATE;
    
    -- Validasi: Cek apakah sudah lunas
    IF v_status_current = 'lunas' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Pembayaran sudah lunas sebelumnya';
    END IF;
    
    -- 1. Update status pembayaran
    UPDATE pembayaran
    SET status_pembayaran = 'lunas',
        tanggal_pembayaran = CURDATE(),
        bukti_pembayaran = p_bukti_pembayaran,
        updated_at = NOW()
    WHERE id_pembayaran = p_id_pembayaran;
    
    -- 2. Insert transaksi keuangan (menggantikan trigger)
    INSERT INTO transaksi_keuangan (
        id_user,
        id_pembayaran,
        nama_transaksi,
        tanggal_transaksi,
        jenis_transaksi,
        metode_pembayaran,
        jumlah,
        keterangan,
        created_at,
        updated_at
    ) VALUES (
        p_id_user,
        p_id_pembayaran,
        'Pembayaran Reservasi',
        CURDATE(),
        'pemasukan',
        v_metode_pembayaran,
        v_total,
        CONCAT('Pembayaran reservasi ID ', v_id_reservasi),
        NOW(),
        NOW()
    );
    
    -- 3. Update status reservasi menjadi done (menggantikan trigger)
    UPDATE reservasi
    SET status = 'done',
        updated_at = NOW()
    WHERE id_reservasi = v_id_reservasi;
    -- Trigger update_jadwal_reservasi akan otomatis update jadwal
    
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `TerapkanPromo` (IN `p_id_treatment` INT, IN `p_tanggal_reservasi` DATE, OUT `p_harga_final` DECIMAL(10,2), OUT `p_ada_promo` BOOLEAN, OUT `p_nama_promo` VARCHAR(255), OUT `p_hemat` DECIMAL(10,2))   BEGIN
    DECLARE v_harga_normal DECIMAL(10,2);
    DECLARE v_harga_promo DECIMAL(10,2);
    DECLARE v_nama_treatment VARCHAR(255);
    
    -- Ambil harga normal dan nama treatment
    SELECT harga, nama_treatment 
    INTO v_harga_normal, v_nama_treatment
    FROM treatment
    WHERE id_treatment = p_id_treatment
      AND deleted_at IS NULL;
    
    IF v_harga_normal IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Treatment tidak ditemukan atau sudah dihapus';
    END IF;
    
    -- Cek apakah ada promo aktif (ambil yang paling murah jika ada beberapa)
    SELECT harga_promo, nama_promo 
    INTO v_harga_promo, p_nama_promo
    FROM promo
    WHERE id_treatment = p_id_treatment
      AND p_tanggal_reservasi BETWEEN periode_mulai AND periode_selesai
      AND deleted_at IS NULL
    ORDER BY harga_promo ASC
    LIMIT 1;
    
    -- Set output berdasarkan hasil
    IF v_harga_promo IS NOT NULL THEN
        SET p_harga_final = v_harga_promo;
        SET p_ada_promo = TRUE;
        SET p_hemat = v_harga_normal - v_harga_promo;
    ELSE
        SET p_harga_final = v_harga_normal;
        SET p_ada_promo = FALSE;
        SET p_nama_promo = NULL;
        SET p_hemat = 0;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `TransaksiPembelianObat` (IN `p_id_obat` INT, IN `p_id_supplier` INT, IN `p_jumlah` INT, IN `p_harga_satuan` DECIMAL(10,2), IN `p_status_pembayaran` ENUM('belum','lunas'), IN `p_tanggal_jatuh_tempo` DATE, IN `p_metode_pembayaran` ENUM('cash','transfer','ewallet'), IN `p_id_user` INT, OUT `p_id_pembelian_baru` INT)   BEGIN
    DECLARE v_stok_terkini INT;
    DECLARE v_nama_obat VARCHAR(255);
    DECLARE v_nama_supplier VARCHAR(255);
    DECLARE v_satuan VARCHAR(255);
    DECLARE v_total_pembelian DECIMAL(10,2);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Terjadi kesalahan saat memproses pembelian obat.';
    END;

    START TRANSACTION;

    -- Ambil data obat
    SELECT nama_obat, stok_terkini, satuan 
    INTO v_nama_obat, v_stok_terkini, v_satuan
    FROM stok_obat
    WHERE id_obat = p_id_obat;

    IF v_nama_obat IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Obat tidak ditemukan.';
    END IF;

    -- Ambil nama supplier
    SELECT nama_supplier INTO v_nama_supplier
    FROM supplier
    WHERE id_supplier = p_id_supplier;

    IF v_nama_supplier IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Supplier tidak ditemukan.';
    END IF;

    -- Validasi tanggal jatuh tempo jika belum lunas
    IF p_status_pembayaran = 'belum' AND p_tanggal_jatuh_tempo IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Tanggal jatuh tempo wajib diisi untuk pembelian belum lunas.';
    END IF;

    -- Insert pembelian obat
    INSERT INTO pembelian_obat (
        id_obat,
        id_supplier,
        tanggal_beli,
        jumlah,
        harga_satuan,
        status_pembayaran,
        tanggal_jatuh_tempo,
        created_at,
        updated_at
    ) VALUES (
        p_id_obat,
        p_id_supplier,
        NOW(),
        p_jumlah,
        p_harga_satuan,
        p_status_pembayaran,
        IF(p_status_pembayaran = 'lunas', NULL, p_tanggal_jatuh_tempo),
        NOW(),
        NOW()
    );

    SET p_id_pembelian_baru = LAST_INSERT_ID();

    -- Update stok obat
    UPDATE stok_obat
    SET stok_terkini = stok_terkini + p_jumlah,
        tanggal_update = NOW(),
        updated_at = NOW()
    WHERE id_obat = p_id_obat;

    -- Hitung total pembelian
    SET v_total_pembelian = p_jumlah * p_harga_satuan;

    -- Insert transaksi keuangan HANYA jika sudah lunas
    IF p_status_pembayaran = 'lunas' THEN
        INSERT INTO transaksi_keuangan (
            id_user,
            id_pembelian_obat,
            nama_transaksi,
            tanggal_transaksi,
            jenis_transaksi,
            metode_pembayaran,
            jumlah,
            keterangan,
            created_at,
            updated_at
        ) VALUES (
            p_id_user,
            p_id_pembelian_baru,
            CONCAT('Pembelian Obat: ', v_nama_obat),
            CURDATE(),
            'pengeluaran',
            p_metode_pembayaran,
            v_total_pembelian,
            CONCAT('Pembelian ', p_jumlah, ' ', v_satuan, ' ', v_nama_obat, ' dari ', v_nama_supplier),
            NOW(),
            NOW()
        );
    END IF;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateStatusPembayaranObat` (IN `p_id_pembelian_obat` INT, IN `p_status_pembayaran` ENUM('belum','lunas'), IN `p_metode_pembayaran` ENUM('cash','transfer','ewallet'), IN `p_id_user` INT)   BEGIN
    DECLARE v_status_lama ENUM('belum','lunas');
    DECLARE v_id_obat INT;
    DECLARE v_jumlah INT;
    DECLARE v_harga_satuan DECIMAL(10,2);
    DECLARE v_nama_obat VARCHAR(255);
    DECLARE v_nama_supplier VARCHAR(255);
    DECLARE v_satuan VARCHAR(255);
    DECLARE v_total_pembelian DECIMAL(10,2);
    DECLARE v_id_supplier INT;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Gagal mengupdate status pembayaran.';
    END;

    START TRANSACTION;

    -- Ambil data pembelian
    SELECT 
        status_pembayaran, 
        id_obat, 
        id_supplier,
        jumlah, 
        harga_satuan
    INTO 
        v_status_lama,
        v_id_obat,
        v_id_supplier,
        v_jumlah,
        v_harga_satuan
    FROM pembelian_obat
    WHERE id_pembelian_obat = p_id_pembelian_obat
    FOR UPDATE;

    IF v_status_lama IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Data pembelian tidak ditemukan.';
    END IF;

    -- Update status pembayaran
    UPDATE pembelian_obat
    SET status_pembayaran = p_status_pembayaran,
        tanggal_jatuh_tempo = IF(p_status_pembayaran = 'lunas', NULL, tanggal_jatuh_tempo),
        updated_at = NOW()
    WHERE id_pembelian_obat = p_id_pembelian_obat;

    -- Hitung total
    SET v_total_pembelian = v_jumlah * v_harga_satuan;

    -- Ambil nama obat dan supplier
    SELECT nama_obat, satuan INTO v_nama_obat, v_satuan
    FROM stok_obat WHERE id_obat = v_id_obat;

    SELECT nama_supplier INTO v_nama_supplier
    FROM supplier WHERE id_supplier = v_id_supplier;

    -- Handle perubahan status
    IF v_status_lama = 'belum' AND p_status_pembayaran = 'lunas' THEN
        -- Buat transaksi baru saat pelunasan
        INSERT INTO transaksi_keuangan (
            id_user,
            id_pembelian_obat,
            nama_transaksi,
            tanggal_transaksi,
            jenis_transaksi,
            metode_pembayaran,
            jumlah,
            keterangan,
            created_at,
            updated_at
        ) VALUES (
            p_id_user,
            p_id_pembelian_obat,
            CONCAT('Pelunasan Pembelian Obat: ', v_nama_obat),
            CURDATE(),
            'pengeluaran',
            p_metode_pembayaran,
            v_total_pembelian,
            CONCAT('Pelunasan pembelian ', v_jumlah, ' ', v_satuan, ' ', v_nama_obat, ' dari ', v_nama_supplier),
            NOW(),
            NOW()
        );
    ELSEIF v_status_lama = 'lunas' AND p_status_pembayaran = 'belum' THEN
        -- Hapus transaksi jika status dikembalikan ke belum lunas
        DELETE FROM transaksi_keuangan
        WHERE id_pembelian_obat = p_id_pembelian_obat;
    END IF;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateTreatment` (IN `p_id_treatment` INT, IN `p_id_user` INT, IN `p_nama_treatment` VARCHAR(255), IN `p_deskripsi` TEXT, IN `p_harga` DECIMAL(10,2), IN `p_durasi` INT, IN `p_foto_treatment` VARCHAR(255))   BEGIN
    DECLARE v_harga_lama DECIMAL(10,2);
    DECLARE v_nama_lama VARCHAR(255);
    DECLARE v_ada_promo INT;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Gagal mengupdate treatment.';
    END;
    
    START TRANSACTION;
    
    -- Ambil data lama
    SELECT harga, nama_treatment INTO v_harga_lama, v_nama_lama
    FROM treatment
    WHERE id_treatment = p_id_treatment
    AND deleted_at IS NULL;
    
    IF v_harga_lama IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Treatment tidak ditemukan.';
    END IF;
    
    -- Validasi harga
    IF p_harga <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Harga treatment harus lebih besar dari 0.';
    END IF;
    
    -- Validasi durasi
    IF p_durasi <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Durasi treatment harus lebih besar dari 0.';
    END IF;
    
    -- Cek apakah ada promo aktif
    SELECT COUNT(*) INTO v_ada_promo
    FROM promo
    WHERE id_treatment = p_id_treatment
    AND CURDATE() BETWEEN periode_mulai AND periode_selesai
    AND deleted_at IS NULL;
    
    -- Warning jika harga naik dan ada promo aktif
    IF p_harga > v_harga_lama AND v_ada_promo > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Tidak bisa menaikkan harga karena treatment sedang promo.';
    END IF;
    
    -- Update treatment
    UPDATE treatment
    SET nama_treatment = p_nama_treatment,
        deskripsi = p_deskripsi,
        harga = p_harga,
        durasi = p_durasi,
        foto_treatment = COALESCE(p_foto_treatment, foto_treatment),
        updated_at = NOW()
    WHERE id_treatment = p_id_treatment;
    
    -- Log jika ada perubahan harga
    IF v_harga_lama <> p_harga THEN
        INSERT INTO log_activity(id_user, activity, created_at)
        VALUES (
            p_id_user,
            CONCAT('Mengubah harga treatment "', v_nama_lama, 
                   '" dari Rp ', FORMAT(v_harga_lama, 0),
                   ' ke Rp ', FORMAT(p_harga, 0)),
            NOW()
        );
    END IF;
    
    COMMIT;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `HargaAkhir` (`p_id_treatment` INT, `p_tanggal` DATE) RETURNS DECIMAL(10,2) DETERMINISTIC READS SQL DATA BEGIN
    DECLARE v_harga_promo DECIMAL(10,2);
    DECLARE v_harga_normal DECIMAL(10,2);
    
    -- Cek promo aktif
    SELECT harga_promo INTO v_harga_promo
    FROM promo
    WHERE id_treatment = p_id_treatment
      AND p_tanggal BETWEEN periode_mulai AND periode_selesai
      AND deleted_at IS NULL
    ORDER BY harga_promo ASC
    LIMIT 1;
    
    -- Jika tidak ada promo, return harga normal
    IF v_harga_promo IS NULL THEN
        SELECT harga INTO v_harga_normal
        FROM treatment
        WHERE id_treatment = p_id_treatment
          AND deleted_at IS NULL;
        
        RETURN COALESCE(v_harga_normal, 0);
    END IF;
    
    RETURN v_harga_promo;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `HitungTotalReservasi` (`p_id_reservasi` INT) RETURNS DECIMAL(10,2) DETERMINISTIC READS SQL DATA BEGIN
    DECLARE v_total DECIMAL(10,2);
    
    SELECT SUM(harga_saat_reservasi * quantity) INTO v_total
    FROM detail_reservasi
    WHERE id_reservasi = p_id_reservasi
    AND deleted_at IS NULL;
    
    RETURN COALESCE(v_total, 0);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `HitungUmur` (`tanggal_lahir` DATE) RETURNS INT(11) DETERMINISTIC BEGIN
    RETURN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE());
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `NamaPasien` (`p_id_pasien` INT) RETURNS VARCHAR(255) CHARSET utf8mb4 COLLATE utf8mb4_general_ci DETERMINISTIC READS SQL DATA BEGIN
    DECLARE v_nama VARCHAR(255);
    
    SELECT CONCAT(nama_depan, ' ', nama_belakang) INTO v_nama
    FROM pasien
    WHERE id_pasien = p_id_pasien
    AND deleted_at IS NULL;
    
    RETURN COALESCE(v_nama, 'Pasien Tidak Ditemukan');
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_reservasi`
--

CREATE TABLE `detail_reservasi` (
  `id_detail` bigint(20) UNSIGNED NOT NULL,
  `id_reservasi` bigint(20) UNSIGNED NOT NULL,
  `id_treatment` bigint(20) UNSIGNED NOT NULL,
  `harga_saat_reservasi` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_operasional`
--

CREATE TABLE `jadwal_operasional` (
  `id_jadwal` bigint(20) UNSIGNED NOT NULL,
  `hari_tanggal` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `status` enum('available','booked') NOT NULL DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_operasional`
--

INSERT INTO `jadwal_operasional` (`id_jadwal`, `hari_tanggal`, `jam_mulai`, `jam_selesai`, `status`, `created_at`, `updated_at`) VALUES
(1, '2025-11-25', '09:00:00', '16:00:00', 'available', '2025-11-25 06:39:30', '2025-11-25 06:39:30');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_activity`
--

CREATE TABLE `log_activity` (
  `id_log` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `activity` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_11_09_111234_create_users_table', 1),
(4, '2025_11_09_111258_create_pasien_table', 1),
(5, '2025_11_09_111337_create_resume_pasien_table', 1),
(6, '2025_11_09_111410_create_rekam_medis_table', 1),
(7, '2025_11_09_111450_create_rekam_kondisi_kulit_table', 1),
(8, '2025_11_09_111518_create_log_activity_table', 1),
(9, '2025_11_09_111535_create_jadwal_operasional_table', 1),
(10, '2025_11_09_111553_create_reservasi_table', 1),
(11, '2025_11_09_111600_create_treatment_table', 1),
(12, '2025_11_09_111617_create_detail_reservasi_table', 1),
(13, '2025_11_09_111638_create_promo_table', 1),
(14, '2025_11_09_111648_create_stok_obat_table', 1),
(15, '2025_11_09_111702_create_supplier_table', 1),
(16, '2025_11_09_111728_create_pembelian_obat_table', 1),
(17, '2025_11_09_111745_create_pembayaran_table', 1),
(18, '2025_11_09_111802_create_transaksi_keuangan_table', 1),
(23, '2025_10_31_045740_create_permission_tables', 2),
(24, '2025_11_11_085743_create_sessions_table', 2),
(25, '2025_11_11_143708_add_nama_obat_to_stok_obat_table', 2),
(26, '2025_11_14_014641_add_status_to_jadwal_operasional', 2),
(27, '2025_11_16_120136_update_role_enum_in_users_table', 3),
(28, '2025_11_16_123315_drop_spatie_permission_tables', 4),
(29, '2025_11_16_131357_fix_timestamps_on_users_table', 5),
(30, '2025_11_16_142344_update_all_tables_timestamps', 6),
(31, '2025_11_23_133542_update_pembelian_obat_tanggal_jatuh_tempo_nullable', 6);

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--

CREATE TABLE `pasien` (
  `id_pasien` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `nama_depan` varchar(255) NOT NULL,
  `nama_belakang` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `no_telepon` varchar(15) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `pasien`
--
DELIMITER $$
CREATE TRIGGER `log_tambah_pasien` AFTER INSERT ON `pasien` FOR EACH ROW BEGIN
    INSERT INTO log_Activity (id_user, activity, created_at)
    VALUES (NEW.id_user, CONCAT('Menambahkan pasien baru: ', NEW.nama_depan, ' ', NEW.nama_belakang), NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_update_pasien` AFTER UPDATE ON `pasien` FOR EACH ROW BEGIN
    IF OLD.nama_depan <> NEW.nama_depan THEN
        INSERT INTO log_Activity (id_user, activity, created_at)
        VALUES (
            NEW.id_user,
            CONCAT(
                'Mengubah nama depan pasien: ',
                OLD.nama_depan, ' → ', NEW.nama_depan
            ),
            NOW()
        );
    END IF;

    IF OLD.nama_belakang <> NEW.nama_belakang THEN
        INSERT INTO log_Activity (id_user, activity, created_at)
        VALUES (
            NEW.id_user,
            CONCAT(
                'Mengubah nama belakang pasien: ',
                OLD.nama_belakang, ' → ', NEW.nama_belakang
            ),
            NOW()
        );
    END IF;

    IF OLD.no_telepon <> NEW.no_telepon THEN
        INSERT INTO log_Activity (id_user, activity, created_at)
        VALUES (
            NEW.id_user,
            CONCAT(
                'Mengubah nomor telepon pasien: ',
                OLD.no_telepon, ' → ', NEW.no_telepon
            ),
            NOW()
        );
    END IF;

    IF OLD.alamat <> NEW.alamat THEN
        INSERT INTO log_Activity (id_user, activity, created_at)
        VALUES (
            NEW.id_user,
            CONCAT(
                'Mengubah alamat pasien: ',
                OLD.alamat, ' → ', NEW.alamat
            ),
            NOW()
        );
    END IF;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validasi_nomor_telepon` BEFORE INSERT ON `pasien` FOR EACH ROW BEGIN
    IF NEW.no_telepon NOT REGEXP '^(\+62|0)[0-9]{9,14}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Nomor pasien tidak valid. Gunakan format Indonesia (+62 / 08...)';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` bigint(20) UNSIGNED NOT NULL,
  `id_reservasi` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `tanggal_pembayaran` date NOT NULL,
  `total_pembayaran` decimal(10,2) NOT NULL,
  `metode_pembayaran` enum('cash','transfer','ewallet') NOT NULL,
  `status_pembayaran` enum('belum','lunas') NOT NULL DEFAULT 'belum',
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `pembayaran`
--
DELIMITER $$
CREATE TRIGGER `log_update_pembayaran` AFTER UPDATE ON `pembayaran` FOR EACH ROW BEGIN
    IF NEW.status_pembayaran <> OLD.status_pembayaran THEN
        INSERT INTO log_activity (id_user, activity, created_at)
        VALUES (
            NEW.id_user,
            CONCAT('Mengubah status pembayaran ID ', NEW.id_pembayaran, 
                   ' menjadi ', NEW.status_pembayaran),
            NOW()
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_obat`
--

CREATE TABLE `pembelian_obat` (
  `id_pembelian_obat` bigint(20) UNSIGNED NOT NULL,
  `id_obat` bigint(20) UNSIGNED NOT NULL,
  `id_supplier` bigint(20) UNSIGNED NOT NULL,
  `tanggal_beli` date NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(10,2) NOT NULL,
  `status_pembayaran` enum('belum','lunas') NOT NULL DEFAULT 'belum',
  `tanggal_jatuh_tempo` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelian_obat`
--

INSERT INTO `pembelian_obat` (`id_pembelian_obat`, `id_obat`, `id_supplier`, `tanggal_beli`, `jumlah`, `harga_satuan`, `status_pembayaran`, `tanggal_jatuh_tempo`, `created_at`, `updated_at`) VALUES
(4, 2, 1, '2025-11-24', 1, 100000.00, 'lunas', NULL, '2025-11-24 10:37:53', '2025-11-24 10:37:53'),
(5, 1, 1, '2025-11-24', 2, 150000.00, 'lunas', NULL, '2025-11-24 10:42:01', '2025-11-24 10:42:01');

-- --------------------------------------------------------

--
-- Table structure for table `promo`
--

CREATE TABLE `promo` (
  `promo_id` bigint(20) UNSIGNED NOT NULL,
  `id_treatment` bigint(20) UNSIGNED NOT NULL,
  `nama_promo` varchar(255) NOT NULL,
  `gambar_promo` varchar(255) NOT NULL,
  `periode_mulai` date NOT NULL,
  `periode_selesai` date NOT NULL,
  `harga_promo` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promo`
--

INSERT INTO `promo` (`promo_id`, `id_treatment`, `nama_promo`, `gambar_promo`, `periode_mulai`, `periode_selesai`, `harga_promo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 11, 'promo november', 'promos/uBSUPPbnDl52gk0z1CiCBgfQ4GbiXkSoxfmohI2Q.jpg', '2025-11-01', '2025-11-30', 15000.00, '2025-11-19 23:09:10', '2025-11-19 23:09:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rekam_kondisi_kulit`
--

CREATE TABLE `rekam_kondisi_kulit` (
  `id_kondisi` bigint(20) UNSIGNED NOT NULL,
  `id_rekam_medis` bigint(20) UNSIGNED NOT NULL,
  `jenis_kondisi` text NOT NULL,
  `status_kondisi` enum('ada','tidak ada') NOT NULL DEFAULT 'tidak ada',
  `area` varchar(255) NOT NULL,
  `derajat` enum('ringan','sedang','berat') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `rekam_kondisi_kulit`
--
DELIMITER $$
CREATE TRIGGER `log_tambah_kondisi_kulit` AFTER INSERT ON `rekam_kondisi_kulit` FOR EACH ROW BEGIN
    INSERT INTO log_activity (id_user, activity, created_at)
    VALUES (
        1,
        CONCAT('Menambahkan kondisi kulit: ', NEW.jenis_kondisi,
               ' | Status: ', NEW.status_kondisi,
               ' | Area: ', NEW.area,
               CASE WHEN NEW.derajat IS NOT NULL THEN CONCAT(' | Derajat: ', NEW.derajat) ELSE '' END),
        NOW()
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `rekam_medis`
--

CREATE TABLE `rekam_medis` (
  `id_rekam_medis` bigint(20) UNSIGNED NOT NULL,
  `id_pasien` bigint(20) UNSIGNED NOT NULL,
  `keluhan` text NOT NULL,
  `jenis_kulit` enum('normal','dry','oily','sensitive','kombinasi') NOT NULL,
  `kelembapan` enum('baik','cukup','kurang') NOT NULL,
  `kondisi_pasien` enum('normal','hamil','menyusui','kontrasepsi') NOT NULL,
  `produk_terakhir_dipakai` text NOT NULL,
  `riwayat_penyakit` text NOT NULL,
  `riwwayat_pengobatan` text NOT NULL,
  `riwayat_alergi` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `rekam_medis`
--
DELIMITER $$
CREATE TRIGGER `log_tambah_rekam_medis` AFTER INSERT ON `rekam_medis` FOR EACH ROW BEGIN
    DECLARE v_nama_pasien VARCHAR(255);
    
    SELECT CONCAT(nama_depan, ' ', nama_belakang) INTO v_nama_pasien
    FROM pasien
    WHERE id_pasien = NEW.id_pasien;
    
    INSERT INTO log_activity (id_user, activity, created_at)
    VALUES (
        1, 
        CONCAT('Membuat rekam medis untuk pasien: ', v_nama_pasien,
               ' | Keluhan: ', LEFT(NEW.keluhan, 50), 
               CASE WHEN LENGTH(NEW.keluhan) > 50 THEN '...' ELSE '' END),
        NOW()
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_update_rekam_medis` AFTER UPDATE ON `rekam_medis` FOR EACH ROW BEGIN
    DECLARE v_nama_pasien VARCHAR(255);
    DECLARE v_changes TEXT DEFAULT '';
    
    SELECT CONCAT(nama_depan, ' ', nama_belakang) INTO v_nama_pasien
    FROM pasien
    WHERE id_pasien = NEW.id_pasien;
    
    -- Track perubahan keluhan
    IF OLD.keluhan <> NEW.keluhan THEN
        SET v_changes = CONCAT(v_changes, 'Keluhan diubah; ');
    END IF;
    
    -- Track perubahan jenis kulit
    IF OLD.jenis_kulit <> NEW.jenis_kulit THEN
        SET v_changes = CONCAT(v_changes, 'Jenis kulit: ', OLD.jenis_kulit, '→', NEW.jenis_kulit, '; ');
    END IF;
    
    -- Track perubahan kondisi pasien
    IF OLD.kondisi_pasien <> NEW.kondisi_pasien THEN
        SET v_changes = CONCAT(v_changes, 'Kondisi: ', OLD.kondisi_pasien, '→', NEW.kondisi_pasien, '; ');
    END IF;
    
    -- Track perubahan riwayat penyakit
    IF OLD.riwayat_penyakit <> NEW.riwayat_penyakit THEN
        SET v_changes = CONCAT(v_changes, 'Riwayat penyakit diubah; ');
    END IF;
    
    IF OLD.riwayat_alergi <> NEW.riwayat_alergi THEN
        SET v_changes = CONCAT(v_changes, 'Riwayat alergi: ', OLD.riwayat_alergi, '→', NEW.riwayat_alergi, '; ');
    END IF;
    
    -- Insert log jika ada perubahan
    IF LENGTH(v_changes) > 0 THEN
        INSERT INTO log_activity (id_user, activity, created_at)
        VALUES (
            1,
            CONCAT('Mengupdate rekam medis pasien: ', v_nama_pasien,
                   ' (ID: ', NEW.id_rekam_medis, ') | Perubahan: ', v_changes),
            NOW()
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `reservasi`
--

CREATE TABLE `reservasi` (
  `id_reservasi` bigint(20) UNSIGNED NOT NULL,
  `id_pasien` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `id_jadwal` bigint(20) UNSIGNED NOT NULL,
  `tanggal_reservasi` date NOT NULL,
  `jam_reservasi` time NOT NULL,
  `status` enum('requested','confirmed','cancelled','done') NOT NULL DEFAULT 'requested',
  `metode_reservasi` enum('manual','online') NOT NULL DEFAULT 'online',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `reservasi`
--
DELIMITER $$
CREATE TRIGGER `log_update_reservasi` AFTER UPDATE ON `reservasi` FOR EACH ROW BEGIN
    DECLARE v_nama_pasien VARCHAR(255);
    
    -- Ambil nama pasien untuk log yang lebih informatif
    SELECT CONCAT(nama_depan, ' ', nama_belakang) INTO v_nama_pasien
    FROM pasien
    WHERE id_pasien = NEW.id_pasien;
    
    -- Log perubahan tanggal reservasi
    IF OLD.tanggal_reservasi <> NEW.tanggal_reservasi THEN
        INSERT INTO log_activity (id_user, activity, created_at)
        VALUES (
            NEW.id_user,
            CONCAT('Mengubah tanggal reservasi ID ', NEW.id_reservasi,
                   ' (', v_nama_pasien, ') dari ', OLD.tanggal_reservasi, 
                   ' ke ', NEW.tanggal_reservasi),
            NOW()
        );
    END IF;
    
    -- Log perubahan jam reservasi
    IF OLD.jam_reservasi <> NEW.jam_reservasi THEN
        INSERT INTO log_activity (id_user, activity, created_at)
        VALUES (
            NEW.id_user,
            CONCAT('Mengubah jam reservasi ID ', NEW.id_reservasi,
                   ' (', v_nama_pasien, ') dari ', OLD.jam_reservasi, 
                   ' ke ', NEW.jam_reservasi),
            NOW()
        );
    END IF;
    
    -- Log perubahan jadwal operasional
    IF OLD.id_jadwal <> NEW.id_jadwal THEN
        INSERT INTO log_activity (id_user, activity, created_at)
        VALUES (
            NEW.id_user,
            CONCAT('Memindahkan reservasi ID ', NEW.id_reservasi,
                   ' (', v_nama_pasien, ') ke jadwal baru (ID: ', NEW.id_jadwal, ')'),
            NOW()
        );
    END IF;
    
    -- Log perubahan metode reservasi
    IF OLD.metode_reservasi <> NEW.metode_reservasi THEN
        INSERT INTO log_activity (id_user, activity, created_at)
        VALUES (
            NEW.id_user,
            CONCAT('Mengubah metode reservasi ID ', NEW.id_reservasi,
                   ' dari ', OLD.metode_reservasi, ' ke ', NEW.metode_reservasi),
            NOW()
        );
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_jadwal_reservasi` AFTER UPDATE ON `reservasi` FOR EACH ROW BEGIN
    IF NEW.status = 'cancelled' THEN
        UPDATE Jadwal_operasional
        SET status = 'available'
        WHERE id_jadwal = NEW.id_jadwal;
    END IF;

    IF NEW.status = 'confirmed' THEN
        UPDATE Jadwal_operasional
        SET status = 'booked'
        WHERE id_jadwal = NEW.id_jadwal;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `resume_pasien`
--

CREATE TABLE `resume_pasien` (
  `id_resume` bigint(20) UNSIGNED NOT NULL,
  `id_pasien` bigint(20) UNSIGNED NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `anamnesa` text NOT NULL,
  `riwayat_eksfo` text NOT NULL,
  `terapi` text NOT NULL,
  `foto_sebelum_treatment` varchar(255) NOT NULL,
  `foto_sesudah_treatment` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1BJv2jNi12ABEDBSHFxZL2bS9RBycLcoTcbcDNSH', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR0F5MWtXeGFzdWx5ZDlFRENSelZnUEZDemd6Rml2MnlZdjFwMGtrVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9vd25lci9wYXNpZW4vY3JlYXRlIjtzOjU6InJvdXRlIjtzOjE2OiJvd25lci5wYXNpZW4uYWRkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1764054487);

-- --------------------------------------------------------

--
-- Table structure for table `stok_obat`
--

CREATE TABLE `stok_obat` (
  `id_obat` bigint(20) UNSIGNED NOT NULL,
  `nama_obat` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `satuan` varchar(255) NOT NULL DEFAULT 'pcs',
  `stok_awal` int(11) NOT NULL DEFAULT 0,
  `stok_terkini` int(11) NOT NULL DEFAULT 0,
  `tanggal_update` date NOT NULL DEFAULT curdate(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stok_obat`
--

INSERT INTO `stok_obat` (`id_obat`, `nama_obat`, `deskripsi`, `satuan`, `stok_awal`, `stok_terkini`, `tanggal_update`, `created_at`, `updated_at`) VALUES
(1, 'Vitamin C', 'kosong dulu', 'botol', 5, 7, '2025-11-24', '2025-11-22 07:42:23', '2025-11-24 10:42:01'),
(2, 'brightening serum', 'mencerahkan wajah', 'tube', 10, 11, '2025-11-24', '2025-11-23 02:26:01', '2025-11-24 10:37:53');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` bigint(20) UNSIGNED NOT NULL,
  `nama_supplier` varchar(255) NOT NULL,
  `nomor_supplier` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `nomor_supplier`, `created_at`, `updated_at`) VALUES
(1, 'PT Kimia Farma', '081234567891', '2025-11-23 00:56:39', '2025-11-23 00:59:24');

--
-- Triggers `supplier`
--
DELIMITER $$
CREATE TRIGGER `validasi_nomor_supplier` BEFORE INSERT ON `supplier` FOR EACH ROW BEGIN
    IF NEW.nomor_supplier NOT REGEXP '^(\+62|0)[0-9]{9,14}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Nomor supplier tidak valid. Gunakan format Indonesia (+62 / 08...)';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_keuangan`
--

CREATE TABLE `transaksi_keuangan` (
  `id_transaksi` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED DEFAULT NULL,
  `id_pembayaran` bigint(20) UNSIGNED DEFAULT NULL,
  `id_pembelian_obat` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_transaksi` varchar(255) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `jenis_transaksi` enum('pemasukan','pengeluaran') NOT NULL,
  `metode_pembayaran` enum('cash','transfer','ewallet') NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_keuangan`
--

INSERT INTO `transaksi_keuangan` (`id_transaksi`, `id_user`, `id_pembayaran`, `id_pembelian_obat`, `nama_transaksi`, `tanggal_transaksi`, `jenis_transaksi`, `metode_pembayaran`, `jumlah`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, 'Beli Alat 1', '2025-11-24', 'pengeluaran', 'cash', 50000.00, NULL, '2025-11-23 23:18:19', '2025-11-23 23:18:19'),
(2, NULL, NULL, 4, 'Pembelian Obat: brightening serum', '2025-11-24', 'pengeluaran', 'cash', 100000.00, 'Pembelian 1 tube brightening serum dari PT Kimia Farma', '2025-11-24 10:37:53', '2025-11-24 10:37:53'),
(3, NULL, NULL, 5, 'Pembelian Obat: Vitamin C', '2025-11-24', 'pengeluaran', 'transfer', 300000.00, 'Pembelian 2 botol Vitamin C dari PT Kimia Farma', '2025-11-24 10:42:01', '2025-11-24 10:42:01');

-- --------------------------------------------------------

--
-- Table structure for table `treatment`
--

CREATE TABLE `treatment` (
  `id_treatment` bigint(20) UNSIGNED NOT NULL,
  `nama_treatment` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `foto_treatment` varchar(255) DEFAULT NULL,
  `durasi` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `treatment`
--

INSERT INTO `treatment` (`id_treatment`, `nama_treatment`, `deskripsi`, `harga`, `foto_treatment`, `durasi`, `created_at`, `updated_at`, `deleted_at`) VALUES
(11, 'Facial Whitening', 'mencerahkan wajah', 20000.00, 'treatments/fcAhAtV8YVqcDXVQlndc3UaH6Eq7i9hiOdyzYchc.jpg', 120, '2025-11-18 20:17:44', '2025-11-20 00:01:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super admin','dokter','admin','user') NOT NULL DEFAULT 'user',
  `status_akun` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `role`, `status_akun`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'owner', 'owner@clinic.com', '$2y$12$P3QxCQ0sQUXca1UwGYD1N.sjncrSBa.0A8bKu4u2DUThCb9A6QViu', 'super admin', 'aktif', '2025-11-18 20:06:49', '2025-11-18 20:06:49', NULL),
(2, 'admin', 'admin@clinic.com', '$2y$12$0PIyphUCUiijwcDyXXgdtOC7irBSVSi2EQooHQ4dwdXmyWiGoHV7q', 'admin', 'aktif', '2025-11-18 20:06:49', '2025-11-18 20:06:49', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_detail_treatment_reservasi`
-- (See below for the actual view)
--
CREATE TABLE `v_detail_treatment_reservasi` (
`id_reservasi` bigint(20) unsigned
,`tanggal_reservasi` date
,`status_reservasi` enum('requested','confirmed','cancelled','done')
,`nama_pasien` varchar(511)
,`no_telepon` varchar(15)
,`id_detail` bigint(20) unsigned
,`id_treatment` bigint(20) unsigned
,`nama_treatment` varchar(255)
,`deskripsi_treatment` text
,`durasi` int(11)
,`quantity` int(11)
,`harga_saat_reservasi` decimal(10,2)
,`subtotal` decimal(20,2)
,`harga_normal` decimal(10,2)
,`status_harga` varchar(12)
,`diskon_per_item` decimal(11,2)
,`total_hemat` decimal(21,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_laporan_keuangan`
-- (See below for the actual view)
--
CREATE TABLE `v_laporan_keuangan` (
`id_transaksi` bigint(20) unsigned
,`tanggal_transaksi` date
,`nama_transaksi` varchar(255)
,`jenis_transaksi` enum('pemasukan','pengeluaran')
,`metode_pembayaran` enum('cash','transfer','ewallet')
,`jumlah` decimal(10,2)
,`keterangan` text
,`diinput_oleh` varchar(255)
,`id_pembayaran` bigint(20) unsigned
,`id_reservasi` bigint(20) unsigned
,`nama_pasien` varchar(511)
,`id_pembelian_obat` bigint(20) unsigned
,`nama_supplier` varchar(255)
,`periode` varchar(7)
,`tahun` int(4)
,`bulan` int(2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_profil_pasien_lengkap`
-- (See below for the actual view)
--
CREATE TABLE `v_profil_pasien_lengkap` (
`id_pasien` bigint(20) unsigned
,`nama_lengkap` varchar(511)
,`nama_depan` varchar(255)
,`nama_belakang` varchar(255)
,`tanggal_lahir` date
,`usia` bigint(21)
,`jenis_kelamin` enum('L','P')
,`no_telepon` varchar(15)
,`alamat` varchar(255)
,`id_rekam_medis` bigint(20) unsigned
,`keluhan_awal` text
,`jenis_kulit` enum('normal','dry','oily','sensitive','kombinasi')
,`kelembapan` enum('baik','cukup','kurang')
,`kondisi_pasien` enum('normal','hamil','menyusui','kontrasepsi')
,`riwayat_alergi` text
,`riwayat_penyakit` text
,`riwwayat_pengobatan` text
,`produk_terakhir_dipakai` text
,`tanggal_rekam_medis_awal` timestamp
,`id_resume` bigint(20) unsigned
,`kunjungan_terakhir_date` date
,`kondisi_terkini` text
,`riwayat_eksfo` text
,`terapi_terakhir` text
,`foto_before_last` varchar(255)
,`foto_after_last` varchar(255)
,`total_kunjungan` bigint(21)
,`kunjungan_selesai` bigint(21)
,`tanggal_reservasi_terakhir` date
,`total_belanja` decimal(32,2)
,`didaftarkan_oleh` varchar(255)
,`tanggal_daftar` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_rekap_pembayaran`
-- (See below for the actual view)
--
CREATE TABLE `v_rekap_pembayaran` (
`id_pembayaran` bigint(20) unsigned
,`tanggal_pembayaran` date
,`total_pembayaran` decimal(10,2)
,`metode_pembayaran` enum('cash','transfer','ewallet')
,`status_pembayaran` enum('belum','lunas')
,`bukti_pembayaran` varchar(255)
,`id_reservasi` bigint(20) unsigned
,`tanggal_reservasi` date
,`jam_reservasi` time
,`status_reservasi` enum('requested','confirmed','cancelled','done')
,`id_pasien` bigint(20) unsigned
,`nama_pasien` varchar(511)
,`kontak_pasien` varchar(15)
,`diproses_oleh` varchar(255)
,`hari_sejak_transaksi` int(7)
,`status_aging` varchar(28)
,`waktu_dibuat` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_reservasi_lengkap`
-- (See below for the actual view)
--
CREATE TABLE `v_reservasi_lengkap` (
`id_reservasi` bigint(20) unsigned
,`tanggal_reservasi` date
,`jam_reservasi` time
,`status` enum('requested','confirmed','cancelled','done')
,`metode_reservasi` enum('manual','online')
,`keterangan` text
,`id_pasien` bigint(20) unsigned
,`nama_pasien` varchar(511)
,`no_telepon` varchar(15)
,`jenis_kelamin` enum('L','P')
,`usia` bigint(21)
,`id_jadwal` bigint(20) unsigned
,`tanggal_jadwal` date
,`jam_mulai` time
,`jam_selesai` time
,`status_jadwal` enum('available','booked')
,`dibuat_oleh` varchar(255)
,`role_pembuat` enum('super admin','dokter','admin','user')
,`id_pembayaran` bigint(20) unsigned
,`total_pembayaran` decimal(10,2)
,`metode_pembayaran` enum('cash','transfer','ewallet')
,`status_pembayaran` enum('belum','lunas')
,`tanggal_pembayaran` date
,`waktu_dibuat` timestamp
,`waktu_update` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_stok_obat_lengkap`
-- (See below for the actual view)
--
CREATE TABLE `v_stok_obat_lengkap` (
`id_obat` bigint(20) unsigned
,`nama_obat` varchar(255)
,`deskripsi` text
,`satuan` varchar(255)
,`stok_awal` int(11)
,`stok_terkini` int(11)
,`last_update` date
,`status_stok` varchar(12)
,`id_pembelian_obat` bigint(20) unsigned
,`pembelian_terakhir` date
,`jumlah_beli_terakhir` int(11)
,`harga_beli_terakhir` decimal(10,2)
,`status_bayar_supplier` enum('belum','lunas')
,`id_supplier` bigint(20) unsigned
,`nama_supplier` varchar(255)
,`kontak_supplier` varchar(255)
,`hari_sejak_beli` int(7)
,`total_terpakai` bigint(12)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_treatment_promo`
-- (See below for the actual view)
--
CREATE TABLE `v_treatment_promo` (
`id_treatment` bigint(20) unsigned
,`nama_treatment` varchar(255)
,`deskripsi` text
,`harga_normal` decimal(10,2)
,`foto_treatment` varchar(255)
,`durasi` int(11)
,`promo_id` bigint(20) unsigned
,`nama_promo` varchar(255)
,`gambar_promo` varchar(255)
,`periode_mulai` date
,`periode_selesai` date
,`harga_promo` decimal(10,2)
,`hemat` decimal(11,2)
,`persen_diskon` decimal(15,0)
,`status_promo` varchar(12)
,`harga_sekarang` decimal(10,2)
,`sisa_hari_promo` int(7)
);

-- --------------------------------------------------------

--
-- Structure for view `v_detail_treatment_reservasi`
--
DROP TABLE IF EXISTS `v_detail_treatment_reservasi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_detail_treatment_reservasi`  AS SELECT `r`.`id_reservasi` AS `id_reservasi`, `r`.`tanggal_reservasi` AS `tanggal_reservasi`, `r`.`status` AS `status_reservasi`, concat(`p`.`nama_depan`,' ',`p`.`nama_belakang`) AS `nama_pasien`, `p`.`no_telepon` AS `no_telepon`, `dr`.`id_detail` AS `id_detail`, `t`.`id_treatment` AS `id_treatment`, `t`.`nama_treatment` AS `nama_treatment`, `t`.`deskripsi` AS `deskripsi_treatment`, `t`.`durasi` AS `durasi`, `dr`.`quantity` AS `quantity`, `dr`.`harga_saat_reservasi` AS `harga_saat_reservasi`, `dr`.`harga_saat_reservasi`* `dr`.`quantity` AS `subtotal`, `t`.`harga` AS `harga_normal`, CASE WHEN `dr`.`harga_saat_reservasi` < `t`.`harga` THEN 'Ada Promo' ELSE 'Harga Normal' END AS `status_harga`, `t`.`harga`- `dr`.`harga_saat_reservasi` AS `diskon_per_item`, (`t`.`harga` - `dr`.`harga_saat_reservasi`) * `dr`.`quantity` AS `total_hemat` FROM (((`reservasi` `r` join `pasien` `p` on(`r`.`id_pasien` = `p`.`id_pasien`)) join `detail_reservasi` `dr` on(`r`.`id_reservasi` = `dr`.`id_reservasi`)) join `treatment` `t` on(`dr`.`id_treatment` = `t`.`id_treatment`)) WHERE `p`.`deleted_at` is null AND `dr`.`deleted_at` is null AND `t`.`deleted_at` is null ;

-- --------------------------------------------------------

--
-- Structure for view `v_laporan_keuangan`
--
DROP TABLE IF EXISTS `v_laporan_keuangan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_laporan_keuangan`  AS SELECT `tk`.`id_transaksi` AS `id_transaksi`, `tk`.`tanggal_transaksi` AS `tanggal_transaksi`, `tk`.`nama_transaksi` AS `nama_transaksi`, `tk`.`jenis_transaksi` AS `jenis_transaksi`, `tk`.`metode_pembayaran` AS `metode_pembayaran`, `tk`.`jumlah` AS `jumlah`, `tk`.`keterangan` AS `keterangan`, `u`.`username` AS `diinput_oleh`, `pb`.`id_pembayaran` AS `id_pembayaran`, `r`.`id_reservasi` AS `id_reservasi`, concat(`p`.`nama_depan`,' ',`p`.`nama_belakang`) AS `nama_pasien`, `po`.`id_pembelian_obat` AS `id_pembelian_obat`, `s`.`nama_supplier` AS `nama_supplier`, date_format(`tk`.`tanggal_transaksi`,'%Y-%m') AS `periode`, year(`tk`.`tanggal_transaksi`) AS `tahun`, month(`tk`.`tanggal_transaksi`) AS `bulan` FROM ((((((`transaksi_keuangan` `tk` left join `users` `u` on(`tk`.`id_user` = `u`.`id_user`)) left join `pembayaran` `pb` on(`tk`.`id_pembayaran` = `pb`.`id_pembayaran`)) left join `reservasi` `r` on(`pb`.`id_reservasi` = `r`.`id_reservasi`)) left join `pasien` `p` on(`r`.`id_pasien` = `p`.`id_pasien`)) left join `pembelian_obat` `po` on(`tk`.`id_pembelian_obat` = `po`.`id_pembelian_obat`)) left join `supplier` `s` on(`po`.`id_supplier` = `s`.`id_supplier`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_profil_pasien_lengkap`
--
DROP TABLE IF EXISTS `v_profil_pasien_lengkap`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_profil_pasien_lengkap`  AS SELECT `p`.`id_pasien` AS `id_pasien`, concat(`p`.`nama_depan`,' ',`p`.`nama_belakang`) AS `nama_lengkap`, `p`.`nama_depan` AS `nama_depan`, `p`.`nama_belakang` AS `nama_belakang`, `p`.`tanggal_lahir` AS `tanggal_lahir`, timestampdiff(YEAR,`p`.`tanggal_lahir`,curdate()) AS `usia`, `p`.`jenis_kelamin` AS `jenis_kelamin`, `p`.`no_telepon` AS `no_telepon`, `p`.`alamat` AS `alamat`, `rm`.`id_rekam_medis` AS `id_rekam_medis`, `rm`.`keluhan` AS `keluhan_awal`, `rm`.`jenis_kulit` AS `jenis_kulit`, `rm`.`kelembapan` AS `kelembapan`, `rm`.`kondisi_pasien` AS `kondisi_pasien`, `rm`.`riwayat_alergi` AS `riwayat_alergi`, `rm`.`riwayat_penyakit` AS `riwayat_penyakit`, `rm`.`riwwayat_pengobatan` AS `riwwayat_pengobatan`, `rm`.`produk_terakhir_dipakai` AS `produk_terakhir_dipakai`, `rm`.`created_at` AS `tanggal_rekam_medis_awal`, `rp`.`id_resume` AS `id_resume`, `rp`.`tanggal_kunjungan` AS `kunjungan_terakhir_date`, `rp`.`anamnesa` AS `kondisi_terkini`, `rp`.`riwayat_eksfo` AS `riwayat_eksfo`, `rp`.`terapi` AS `terapi_terakhir`, `rp`.`foto_sebelum_treatment` AS `foto_before_last`, `rp`.`foto_sesudah_treatment` AS `foto_after_last`, count(distinct `r`.`id_reservasi`) AS `total_kunjungan`, count(distinct case when `r`.`status` = 'done' then `r`.`id_reservasi` end) AS `kunjungan_selesai`, max(`r`.`tanggal_reservasi`) AS `tanggal_reservasi_terakhir`, coalesce(sum(case when `pb`.`status_pembayaran` = 'lunas' then `pb`.`total_pembayaran` end),0) AS `total_belanja`, `u`.`username` AS `didaftarkan_oleh`, `p`.`created_at` AS `tanggal_daftar` FROM (((((`pasien` `p` left join `users` `u` on(`p`.`id_user` = `u`.`id_user`)) left join `rekam_medis` `rm` on(`p`.`id_pasien` = `rm`.`id_pasien` and `rm`.`id_rekam_medis` = (select `rekam_medis`.`id_rekam_medis` from `rekam_medis` where `rekam_medis`.`id_pasien` = `p`.`id_pasien` and `rekam_medis`.`deleted_at` is null order by `rekam_medis`.`created_at` limit 1))) left join `resume_pasien` `rp` on(`p`.`id_pasien` = `rp`.`id_pasien` and `rp`.`id_resume` = (select `resume_pasien`.`id_resume` from `resume_pasien` where `resume_pasien`.`id_pasien` = `p`.`id_pasien` and `resume_pasien`.`deleted_at` is null order by `resume_pasien`.`tanggal_kunjungan` desc limit 1))) left join `reservasi` `r` on(`p`.`id_pasien` = `r`.`id_pasien`)) left join `pembayaran` `pb` on(`r`.`id_reservasi` = `pb`.`id_reservasi`)) WHERE `p`.`deleted_at` is null GROUP BY `p`.`id_pasien`, `p`.`nama_depan`, `p`.`nama_belakang`, `p`.`tanggal_lahir`, `p`.`jenis_kelamin`, `p`.`no_telepon`, `p`.`alamat`, `rm`.`id_rekam_medis`, `rm`.`keluhan`, `rm`.`jenis_kulit`, `rm`.`kelembapan`, `rm`.`kondisi_pasien`, `rm`.`riwayat_alergi`, `rm`.`riwayat_penyakit`, `rm`.`riwwayat_pengobatan`, `rm`.`produk_terakhir_dipakai`, `rm`.`created_at`, `rp`.`id_resume`, `rp`.`tanggal_kunjungan`, `rp`.`anamnesa`, `rp`.`riwayat_eksfo`, `rp`.`terapi`, `rp`.`foto_sebelum_treatment`, `rp`.`foto_sesudah_treatment`, `u`.`username`, `p`.`created_at` ;

-- --------------------------------------------------------

--
-- Structure for view `v_rekap_pembayaran`
--
DROP TABLE IF EXISTS `v_rekap_pembayaran`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_rekap_pembayaran`  AS SELECT `pb`.`id_pembayaran` AS `id_pembayaran`, `pb`.`tanggal_pembayaran` AS `tanggal_pembayaran`, `pb`.`total_pembayaran` AS `total_pembayaran`, `pb`.`metode_pembayaran` AS `metode_pembayaran`, `pb`.`status_pembayaran` AS `status_pembayaran`, `pb`.`bukti_pembayaran` AS `bukti_pembayaran`, `r`.`id_reservasi` AS `id_reservasi`, `r`.`tanggal_reservasi` AS `tanggal_reservasi`, `r`.`jam_reservasi` AS `jam_reservasi`, `r`.`status` AS `status_reservasi`, `p`.`id_pasien` AS `id_pasien`, concat(`p`.`nama_depan`,' ',`p`.`nama_belakang`) AS `nama_pasien`, `p`.`no_telepon` AS `kontak_pasien`, `u`.`username` AS `diproses_oleh`, to_days(curdate()) - to_days(`pb`.`tanggal_pembayaran`) AS `hari_sejak_transaksi`, CASE WHEN `pb`.`status_pembayaran` = 'lunas' THEN 'Lunas' WHEN to_days(curdate()) - to_days(`r`.`tanggal_reservasi`) <= 7 THEN 'Current (< 7 hari)' WHEN to_days(curdate()) - to_days(`r`.`tanggal_reservasi`) <= 30 THEN 'Aging 7-30 hari' ELSE 'Aging > 30 hari (Prioritas!)' END AS `status_aging`, `pb`.`created_at` AS `waktu_dibuat` FROM (((`pembayaran` `pb` join `reservasi` `r` on(`pb`.`id_reservasi` = `r`.`id_reservasi`)) join `pasien` `p` on(`r`.`id_pasien` = `p`.`id_pasien`)) join `users` `u` on(`pb`.`id_user` = `u`.`id_user`)) WHERE `p`.`deleted_at` is null ;

-- --------------------------------------------------------

--
-- Structure for view `v_reservasi_lengkap`
--
DROP TABLE IF EXISTS `v_reservasi_lengkap`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_reservasi_lengkap`  AS SELECT `r`.`id_reservasi` AS `id_reservasi`, `r`.`tanggal_reservasi` AS `tanggal_reservasi`, `r`.`jam_reservasi` AS `jam_reservasi`, `r`.`status` AS `status`, `r`.`metode_reservasi` AS `metode_reservasi`, `r`.`keterangan` AS `keterangan`, `p`.`id_pasien` AS `id_pasien`, concat(`p`.`nama_depan`,' ',`p`.`nama_belakang`) AS `nama_pasien`, `p`.`no_telepon` AS `no_telepon`, `p`.`jenis_kelamin` AS `jenis_kelamin`, timestampdiff(YEAR,`p`.`tanggal_lahir`,curdate()) AS `usia`, `j`.`id_jadwal` AS `id_jadwal`, `j`.`hari_tanggal` AS `tanggal_jadwal`, `j`.`jam_mulai` AS `jam_mulai`, `j`.`jam_selesai` AS `jam_selesai`, `j`.`status` AS `status_jadwal`, `u`.`username` AS `dibuat_oleh`, `u`.`role` AS `role_pembuat`, `pb`.`id_pembayaran` AS `id_pembayaran`, `pb`.`total_pembayaran` AS `total_pembayaran`, `pb`.`metode_pembayaran` AS `metode_pembayaran`, `pb`.`status_pembayaran` AS `status_pembayaran`, `pb`.`tanggal_pembayaran` AS `tanggal_pembayaran`, `r`.`created_at` AS `waktu_dibuat`, `r`.`updated_at` AS `waktu_update` FROM ((((`reservasi` `r` join `pasien` `p` on(`r`.`id_pasien` = `p`.`id_pasien`)) join `users` `u` on(`r`.`id_user` = `u`.`id_user`)) join `jadwal_operasional` `j` on(`r`.`id_jadwal` = `j`.`id_jadwal`)) left join `pembayaran` `pb` on(`r`.`id_reservasi` = `pb`.`id_reservasi`)) WHERE `p`.`deleted_at` is null ;

-- --------------------------------------------------------

--
-- Structure for view `v_stok_obat_lengkap`
--
DROP TABLE IF EXISTS `v_stok_obat_lengkap`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stok_obat_lengkap`  AS SELECT `so`.`id_obat` AS `id_obat`, `so`.`nama_obat` AS `nama_obat`, `so`.`deskripsi` AS `deskripsi`, `so`.`satuan` AS `satuan`, `so`.`stok_awal` AS `stok_awal`, `so`.`stok_terkini` AS `stok_terkini`, `so`.`tanggal_update` AS `last_update`, CASE WHEN `so`.`stok_terkini` = 0 THEN 'Habis' WHEN `so`.`stok_terkini` <= 10 THEN 'sedikit lagi' WHEN `so`.`stok_terkini` <= 20 THEN 'Menipis' ELSE '✅ Aman' END AS `status_stok`, `po`.`id_pembelian_obat` AS `id_pembelian_obat`, `po`.`tanggal_beli` AS `pembelian_terakhir`, `po`.`jumlah` AS `jumlah_beli_terakhir`, `po`.`harga_satuan` AS `harga_beli_terakhir`, `po`.`status_pembayaran` AS `status_bayar_supplier`, `s`.`id_supplier` AS `id_supplier`, `s`.`nama_supplier` AS `nama_supplier`, `s`.`nomor_supplier` AS `kontak_supplier`, to_days(curdate()) - to_days(`po`.`tanggal_beli`) AS `hari_sejak_beli`, `so`.`stok_awal`- `so`.`stok_terkini` AS `total_terpakai` FROM ((`stok_obat` `so` left join `pembelian_obat` `po` on(`so`.`id_obat` = `po`.`id_obat` and `po`.`id_pembelian_obat` = (select `pembelian_obat`.`id_pembelian_obat` from `pembelian_obat` where `pembelian_obat`.`id_obat` = `so`.`id_obat` order by `pembelian_obat`.`tanggal_beli` desc limit 1))) left join `supplier` `s` on(`po`.`id_supplier` = `s`.`id_supplier`)) ORDER BY `so`.`stok_terkini` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_treatment_promo`
--
DROP TABLE IF EXISTS `v_treatment_promo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_treatment_promo`  AS SELECT `t`.`id_treatment` AS `id_treatment`, `t`.`nama_treatment` AS `nama_treatment`, `t`.`deskripsi` AS `deskripsi`, `t`.`harga` AS `harga_normal`, `t`.`foto_treatment` AS `foto_treatment`, `t`.`durasi` AS `durasi`, `pr`.`promo_id` AS `promo_id`, `pr`.`nama_promo` AS `nama_promo`, `pr`.`gambar_promo` AS `gambar_promo`, `pr`.`periode_mulai` AS `periode_mulai`, `pr`.`periode_selesai` AS `periode_selesai`, `pr`.`harga_promo` AS `harga_promo`, `t`.`harga`- coalesce(`pr`.`harga_promo`,`t`.`harga`) AS `hemat`, round((`t`.`harga` - coalesce(`pr`.`harga_promo`,`t`.`harga`)) / `t`.`harga` * 100,0) AS `persen_diskon`, CASE WHEN `pr`.`promo_id` is not null AND curdate() between `pr`.`periode_mulai` and `pr`.`periode_selesai` THEN 'Ada Promo' ELSE 'Harga Normal' END AS `status_promo`, CASE WHEN `pr`.`promo_id` is not null AND curdate() between `pr`.`periode_mulai` and `pr`.`periode_selesai` THEN `pr`.`harga_promo` ELSE `t`.`harga` END AS `harga_sekarang`, CASE WHEN `pr`.`promo_id` is not null AND curdate() between `pr`.`periode_mulai` and `pr`.`periode_selesai` THEN to_days(`pr`.`periode_selesai`) - to_days(curdate()) ELSE NULL END AS `sisa_hari_promo` FROM (`treatment` `t` left join `promo` `pr` on(`t`.`id_treatment` = `pr`.`id_treatment` and curdate() between `pr`.`periode_mulai` and `pr`.`periode_selesai` and `pr`.`deleted_at` is null)) WHERE `t`.`deleted_at` is null ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `detail_reservasi`
--
ALTER TABLE `detail_reservasi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `detail_reservasi_id_reservasi_foreign` (`id_reservasi`),
  ADD KEY `detail_reservasi_id_treatment_foreign` (`id_treatment`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jadwal_operasional`
--
ALTER TABLE `jadwal_operasional`
  ADD PRIMARY KEY (`id_jadwal`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_activity`
--
ALTER TABLE `log_activity`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `log_activity_id_user_foreign` (`id_user`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id_pasien`),
  ADD UNIQUE KEY `pasien_no_telepon_unique` (`no_telepon`),
  ADD KEY `pasien_id_user_foreign` (`id_user`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `pembayaran_id_reservasi_foreign` (`id_reservasi`),
  ADD KEY `pembayaran_id_user_foreign` (`id_user`);

--
-- Indexes for table `pembelian_obat`
--
ALTER TABLE `pembelian_obat`
  ADD PRIMARY KEY (`id_pembelian_obat`),
  ADD KEY `pembelian_obat_id_obat_foreign` (`id_obat`),
  ADD KEY `pembelian_obat_id_supplier_foreign` (`id_supplier`);

--
-- Indexes for table `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`promo_id`),
  ADD KEY `promo_id_treatment_foreign` (`id_treatment`);

--
-- Indexes for table `rekam_kondisi_kulit`
--
ALTER TABLE `rekam_kondisi_kulit`
  ADD PRIMARY KEY (`id_kondisi`),
  ADD KEY `rekam_kondisi_kulit_id_rekam_medis_foreign` (`id_rekam_medis`);

--
-- Indexes for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD PRIMARY KEY (`id_rekam_medis`),
  ADD KEY `rekam_medis_id_pasien_foreign` (`id_pasien`);

--
-- Indexes for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`id_reservasi`),
  ADD KEY `reservasi_id_pasien_foreign` (`id_pasien`),
  ADD KEY `reservasi_id_user_foreign` (`id_user`),
  ADD KEY `reservasi_id_jadwal_foreign` (`id_jadwal`);

--
-- Indexes for table `resume_pasien`
--
ALTER TABLE `resume_pasien`
  ADD PRIMARY KEY (`id_resume`),
  ADD KEY `resume_pasien_id_pasien_foreign` (`id_pasien`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stok_obat`
--
ALTER TABLE `stok_obat`
  ADD PRIMARY KEY (`id_obat`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`),
  ADD UNIQUE KEY `supplier_nama_supplier_unique` (`nama_supplier`),
  ADD UNIQUE KEY `supplier_nomor_supplier_unique` (`nomor_supplier`);

--
-- Indexes for table `transaksi_keuangan`
--
ALTER TABLE `transaksi_keuangan`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `transaksi_keuangan_id_user_foreign` (`id_user`),
  ADD KEY `transaksi_keuangan_id_pembayaran_foreign` (`id_pembayaran`),
  ADD KEY `transaksi_keuangan_id_pembelian_obat_foreign` (`id_pembelian_obat`);

--
-- Indexes for table `treatment`
--
ALTER TABLE `treatment`
  ADD PRIMARY KEY (`id_treatment`),
  ADD UNIQUE KEY `treatment_nama_treatment_unique` (`nama_treatment`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_reservasi`
--
ALTER TABLE `detail_reservasi`
  MODIFY `id_detail` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_operasional`
--
ALTER TABLE `jadwal_operasional`
  MODIFY `id_jadwal` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_activity`
--
ALTER TABLE `log_activity`
  MODIFY `id_log` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id_pasien` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembelian_obat`
--
ALTER TABLE `pembelian_obat`
  MODIFY `id_pembelian_obat` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `promo`
--
ALTER TABLE `promo`
  MODIFY `promo_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rekam_kondisi_kulit`
--
ALTER TABLE `rekam_kondisi_kulit`
  MODIFY `id_kondisi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  MODIFY `id_rekam_medis` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `id_reservasi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resume_pasien`
--
ALTER TABLE `resume_pasien`
  MODIFY `id_resume` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stok_obat`
--
ALTER TABLE `stok_obat`
  MODIFY `id_obat` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaksi_keuangan`
--
ALTER TABLE `transaksi_keuangan`
  MODIFY `id_transaksi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `treatment`
--
ALTER TABLE `treatment`
  MODIFY `id_treatment` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_reservasi`
--
ALTER TABLE `detail_reservasi`
  ADD CONSTRAINT `detail_reservasi_id_reservasi_foreign` FOREIGN KEY (`id_reservasi`) REFERENCES `reservasi` (`id_reservasi`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_reservasi_id_treatment_foreign` FOREIGN KEY (`id_treatment`) REFERENCES `treatment` (`id_treatment`) ON DELETE CASCADE;

--
-- Constraints for table `log_activity`
--
ALTER TABLE `log_activity`
  ADD CONSTRAINT `log_activity_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `pasien`
--
ALTER TABLE `pasien`
  ADD CONSTRAINT `pasien_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_id_reservasi_foreign` FOREIGN KEY (`id_reservasi`) REFERENCES `reservasi` (`id_reservasi`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayaran_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `pembelian_obat`
--
ALTER TABLE `pembelian_obat`
  ADD CONSTRAINT `pembelian_obat_id_obat_foreign` FOREIGN KEY (`id_obat`) REFERENCES `stok_obat` (`id_obat`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembelian_obat_id_supplier_foreign` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`) ON DELETE CASCADE;

--
-- Constraints for table `promo`
--
ALTER TABLE `promo`
  ADD CONSTRAINT `promo_id_treatment_foreign` FOREIGN KEY (`id_treatment`) REFERENCES `treatment` (`id_treatment`) ON DELETE CASCADE;

--
-- Constraints for table `rekam_kondisi_kulit`
--
ALTER TABLE `rekam_kondisi_kulit`
  ADD CONSTRAINT `rekam_kondisi_kulit_id_rekam_medis_foreign` FOREIGN KEY (`id_rekam_medis`) REFERENCES `rekam_medis` (`id_rekam_medis`) ON DELETE CASCADE;

--
-- Constraints for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD CONSTRAINT `rekam_medis_id_pasien_foreign` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`) ON DELETE CASCADE;

--
-- Constraints for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD CONSTRAINT `reservasi_id_jadwal_foreign` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_operasional` (`id_jadwal`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservasi_id_pasien_foreign` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservasi_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `resume_pasien`
--
ALTER TABLE `resume_pasien`
  ADD CONSTRAINT `resume_pasien_id_pasien_foreign` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi_keuangan`
--
ALTER TABLE `transaksi_keuangan`
  ADD CONSTRAINT `transaksi_keuangan_id_pembayaran_foreign` FOREIGN KEY (`id_pembayaran`) REFERENCES `pembayaran` (`id_pembayaran`) ON DELETE SET NULL,
  ADD CONSTRAINT `transaksi_keuangan_id_pembelian_obat_foreign` FOREIGN KEY (`id_pembelian_obat`) REFERENCES `pembelian_obat` (`id_pembelian_obat`) ON DELETE SET NULL,
  ADD CONSTRAINT `transaksi_keuangan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
