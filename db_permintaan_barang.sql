-- ============================================
-- DATABASE: SISTEM PERMINTAAN BARANG
-- ============================================

CREATE DATABASE IF NOT EXISTS db_permintaan_barang;
USE db_permintaan_barang;

-- ============================================
-- TABEL USERS
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'karyawan') NOT NULL DEFAULT 'karyawan',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABEL STOK BARANG
-- ============================================
CREATE TABLE IF NOT EXISTS stok_barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(20) NOT NULL UNIQUE,
    nama_barang VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABEL PERMINTAAN
-- ============================================
CREATE TABLE IF NOT EXISTS permintaan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_permintaan VARCHAR(20) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    nama_permintaan VARCHAR(100) NOT NULL,
    kategori ENUM('IT', 'Fasilitas', 'Alat Kantor') NOT NULL,
    jumlah INT NOT NULL,
    deskripsi TEXT,
    status ENUM('Pending', 'Valid', 'Ditolak') NOT NULL DEFAULT 'Pending',
    keterangan_admin TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- DATA AWAL: USERS
-- ============================================
INSERT INTO users (nama, username, password, role) VALUES
('Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Budi Santoso', 'budi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'karyawan'),
('Siti Rahayu', 'siti', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'karyawan');

-- Password semua akun: password

-- ============================================
-- DATA AWAL: STOK BARANG
-- ============================================
INSERT INTO stok_barang (kode_barang, nama_barang, kategori, stok) VALUES
('BRG-001', 'Kertas A4', 'Alat Kantor', 50),
('BRG-002', 'Pulpen Hitam', 'Alat Kantor', 100),
('BRG-003', 'Stapler', 'Alat Kantor', 20),
('BRG-004', 'Tinta Printer', 'Alat Kantor', 15),
('BRG-005', 'Map Plastik', 'Alat Kantor', 30);

-- ============================================
-- DATA AWAL: PERMINTAAN CONTOH
-- ============================================
INSERT INTO permintaan (kode_permintaan, user_id, nama_permintaan, kategori, jumlah, deskripsi, status) VALUES
('PRM-20260001', 2, 'Kertas A4', 'Alat Kantor', 5, 'Kebutuhan cetak laporan bulanan', 'Pending'),
('PRM-20260002', 3, 'Mouse Wireless', 'IT', 2, 'Mouse lama sudah rusak', 'Pending'),
('PRM-20260003', 2, 'Kursi Kerja', 'Fasilitas', 1, 'Kursi di meja saya sudah rusak', 'Valid');

