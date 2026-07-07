-- ============================================================
-- SETUP: Master Alat Kantor & Riwayat Stok
-- Jalankan file ini di phpMyAdmin / MySQL CLI
-- ============================================================

-- 1. Tabel master_alat_kantor
CREATE TABLE IF NOT EXISTS `master_alat_kantor` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama_barang` VARCHAR(150) NOT NULL,
    `stok`        INT NOT NULL DEFAULT 0,
    `created_at`  DATETIME NULL,
    `updated_at`  DATETIME NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Seed data awal
INSERT INTO `master_alat_kantor` (`nama_barang`, `stok`, `created_at`, `updated_at`) VALUES
('Kertas A4',     0, NOW(), NOW()),
('Kertas F4',     0, NOW(), NOW()),
('Pena',          0, NOW(), NOW()),
('Pensil',        0, NOW(), NOW()),
('Penghapus',     0, NOW(), NOW()),
('Spidol',        0, NOW(), NOW()),
('Stabilo',       0, NOW(), NOW()),
('Map Folder',    0, NOW(), NOW()),
('Binder',        0, NOW(), NOW()),
('Lakban',        0, NOW(), NOW()),
('Stapler',       0, NOW(), NOW()),
('Isi Stapler',   0, NOW(), NOW()),
('Gunting',       0, NOW(), NOW()),
('Cutter',        0, NOW(), NOW()),
('Lem',           0, NOW(), NOW()),
('Buku Tulis',    0, NOW(), NOW()),
('Amplop',        0, NOW(), NOW()),
('Post-it Notes', 0, NOW(), NOW());

-- 3. Tabel riwayat_stok
CREATE TABLE IF NOT EXISTS `riwayat_stok` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `barang_id`    INT UNSIGNED NOT NULL,
    `nama_barang`  VARCHAR(150) NOT NULL,
    `jenis`        ENUM('Masuk','Keluar') NOT NULL,
    `jumlah`       INT NOT NULL,
    `stok_sebelum` INT NOT NULL,
    `stok_sesudah` INT NOT NULL,
    `keterangan`   VARCHAR(255) NULL,
    `user_id`      INT UNSIGNED NULL,
    `nama_user`    VARCHAR(100) NULL,
    `created_at`   DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `fk_riwayat_barang` (`barang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tambah kolom barang_id di tabel permintaan (untuk relasi ke master_alat_kantor)
--    Kolom ini NULL-able agar permintaan kategori lain tidak terpengaruh
ALTER TABLE `permintaan`
    ADD COLUMN IF NOT EXISTS `barang_id` INT UNSIGNED NULL DEFAULT NULL AFTER `nama_permintaan`,
    ADD COLUMN IF NOT EXISTS `stok_sudah_dikurangi` TINYINT(1) NOT NULL DEFAULT 0 AFTER `keterangan_admin`;
