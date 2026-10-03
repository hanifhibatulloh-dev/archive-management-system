-- ============================================
-- DISARDA CIMAHI - Database Schema
-- Sistem Informasi Dinas Arsip dan Perpustakaan Kota Cimahi
-- ============================================

-- Buat Database
-- Database
CREATE DATABASE IF NOT EXISTS archive_management_system;
USE archive_management_system;

-- ============================================
-- 1. TABEL UTAMA
-- ============================================

-- Table: Users (untuk login)
CREATE TABLE Users (
    ID_User INT PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(100) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Nama_Lengkap VARCHAR(255) NOT NULL,
    Email VARCHAR(255) UNIQUE,
    Role ENUM('Admin', 'Operator', 'Viewer') DEFAULT 'Viewer',
    Status ENUM('Aktif', 'Nonaktif') DEFAULT 'Aktif',
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Last_Login TIMESTAMP NULL
);

-- Table: Pegawai
CREATE TABLE Pegawai (
    ID_Pegawai INT PRIMARY KEY AUTO_INCREMENT,
    Nama_Pegawai VARCHAR(255) NOT NULL,
    NIP VARCHAR(50) UNIQUE,
    Jabatan VARCHAR(255) NOT NULL,
    Tugas VARCHAR(255),
    Tanggal_Masuk DATE,
    Email VARCHAR(255),
    Nomor_Telepon VARCHAR(20),
    Status ENUM('Aktif', 'Nonaktif') DEFAULT 'Aktif',
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Jenis_Arsip
CREATE TABLE Jenis_Arsip (
    ID_Jenis_Arsip INT PRIMARY KEY AUTO_INCREMENT,
    Nama_Jenis_Arsip VARCHAR(255) NOT NULL,
    Kode_Jenis VARCHAR(20),
    Deskripsi_Jenis_Arsip TEXT,
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Klasifikasi_Arsip
CREATE TABLE Klasifikasi_Arsip (
    ID_Klasifikasi_Arsip INT PRIMARY KEY AUTO_INCREMENT,
    Kode_Klasifikasi VARCHAR(50),
    Nama_Klasifikasi VARCHAR(255) NOT NULL,
    Deskripsi_Klasifikasi TEXT,
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Penyimpanan
CREATE TABLE Penyimpanan (
    ID_Penyimpanan INT PRIMARY KEY AUTO_INCREMENT,
    Kode_Lokasi VARCHAR(50),
    Lokasi_Penyimpanan VARCHAR(255) NOT NULL,
    Kapasitas_Penyimpanan INT DEFAULT 0,
    Kapasitas_Terpakai INT DEFAULT 0,
    Jenis_Penyimpanan ENUM('Fisikal', 'Server') DEFAULT 'Fisikal',
    Kondisi_Penyimpanan TEXT,
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Arsip (Tabel Utama)
CREATE TABLE Arsip (
    ID_Arsip INT PRIMARY KEY AUTO_INCREMENT,
    Nomor_Arsip VARCHAR(100) UNIQUE,
    Nama_Arsip VARCHAR(255) NOT NULL,
    Deskripsi_Arsip TEXT,
    Tanggal_Arsip DATE,
    Tanggal_Masuk DATE,
    Tanggal_Penyimpanan DATE,
    Status_Arsip ENUM('Aktif', 'Inaktif', 'Terarsipkan', 'Musnah') DEFAULT 'Aktif',
    ID_Jenis_Arsip INT,
    ID_Klasifikasi_Arsip INT,
    ID_Penyimpanan INT,
    ID_Pegawai INT,
    Tags TEXT,
    Keterangan TEXT,
    File_Path VARCHAR(500),
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Jenis_Arsip) REFERENCES Jenis_Arsip(ID_Jenis_Arsip) ON DELETE SET NULL,
    FOREIGN KEY (ID_Klasifikasi_Arsip) REFERENCES Klasifikasi_Arsip(ID_Klasifikasi_Arsip) ON DELETE SET NULL,
    FOREIGN KEY (ID_Penyimpanan) REFERENCES Penyimpanan(ID_Penyimpanan) ON DELETE SET NULL,
    FOREIGN KEY (ID_Pegawai) REFERENCES Pegawai(ID_Pegawai) ON DELETE SET NULL
);

-- Table: Pengguna
CREATE TABLE Pengguna (
    ID_Pengguna INT PRIMARY KEY AUTO_INCREMENT,
    Nama_Pengguna VARCHAR(255) NOT NULL,
    Tipe_Pengguna ENUM('Internal', 'Eksternal') DEFAULT 'Internal',
    Email_Pengguna VARCHAR(255) UNIQUE,
    Nomor_Telepon VARCHAR(20),
    Instansi VARCHAR(255),
    Alamat TEXT,
    Akses_Arsip TEXT,
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Proses_Pengarsipan
CREATE TABLE Proses_Pengarsipan (
    ID_Proses INT PRIMARY KEY AUTO_INCREMENT,
    ID_Arsip INT,
    Nama_Proses VARCHAR(255) NOT NULL,
    Deskripsi_Proses TEXT,
    Tanggal_Proses DATE,
    Status_Proses ENUM('Pending', 'Diproses', 'Selesai', 'Ditolak') DEFAULT 'Pending',
    ID_Pegawai INT,
    Catatan TEXT,
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_Arsip) REFERENCES Arsip(ID_Arsip) ON DELETE CASCADE,
    FOREIGN KEY (ID_Pegawai) REFERENCES Pegawai(ID_Pegawai) ON DELETE SET NULL
);

-- Table: Roles
CREATE TABLE Roles (
    ID_Role INT PRIMARY KEY AUTO_INCREMENT,
    Nama_Role VARCHAR(255) NOT NULL,
    Deskripsi_Role TEXT,
    Hak_Akses TEXT,
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- 2. TABEL PENGHUBUNG (Junction Tables)
-- ============================================

-- Table: Pengguna_Arsip (Many-to-Many)
CREATE TABLE Pengguna_Arsip (
    ID_Pengguna INT,
    ID_Arsip INT,
    Tanggal_Akses TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Jenis_Akses ENUM('Lihat', 'Pinjam', 'Download') DEFAULT 'Lihat',
    PRIMARY KEY (ID_Pengguna, ID_Arsip),
    FOREIGN KEY (ID_Pengguna) REFERENCES Pengguna(ID_Pengguna) ON DELETE CASCADE,
    FOREIGN KEY (ID_Arsip) REFERENCES Arsip(ID_Arsip) ON DELETE CASCADE
);

-- Table: Pengguna_Roles (Many-to-Many)
CREATE TABLE Pengguna_Roles (
    ID_Pengguna INT,
    ID_Role INT,
    PRIMARY KEY (ID_Pengguna, ID_Role),
    FOREIGN KEY (ID_Pengguna) REFERENCES Pengguna(ID_Pengguna) ON DELETE CASCADE,
    FOREIGN KEY (ID_Role) REFERENCES Roles(ID_Role) ON DELETE CASCADE
);

-- ============================================
-- 3. TABEL AUDIT LOG
-- ============================================

CREATE TABLE Audit_Log (
    ID_Audit INT PRIMARY KEY AUTO_INCREMENT,
    Table_Name VARCHAR(255),
    Action_Type ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
    Record_ID INT,
    Column_Name VARCHAR(255),
    Old_Value TEXT,
    New_Value TEXT,
    Change_Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    User_ID INT,
    IP_Address VARCHAR(50)
);

-- ============================================
-- 4. INDEXES UNTUK PERFORMA
-- ============================================

CREATE INDEX idx_arsip_jenis ON Arsip(ID_Jenis_Arsip);
CREATE INDEX idx_arsip_klasifikasi ON Arsip(ID_Klasifikasi_Arsip);
CREATE INDEX idx_arsip_penyimpanan ON Arsip(ID_Penyimpanan);
CREATE INDEX idx_arsip_pegawai ON Arsip(ID_Pegawai);
CREATE INDEX idx_arsip_status ON Arsip(Status_Arsip);
CREATE INDEX idx_arsip_tanggal ON Arsip(Tanggal_Arsip);
CREATE INDEX idx_pegawai_status ON Pegawai(Status);
CREATE INDEX idx_users_username ON Users(Username);

-- ============================================
-- 5. TRIGGER UNTUK AUDIT LOG
-- ============================================

DELIMITER $$

CREATE TRIGGER tr_arsip_update
AFTER UPDATE ON Arsip
FOR EACH ROW
BEGIN
    IF OLD.Status_Arsip != NEW.Status_Arsip THEN
        INSERT INTO Audit_Log (Table_Name, Action_Type, Record_ID, Column_Name, Old_Value, New_Value)
        VALUES ('Arsip', 'UPDATE', NEW.ID_Arsip, 'Status_Arsip', OLD.Status_Arsip, NEW.Status_Arsip);
    END IF;
END$$

CREATE TRIGGER tr_arsip_insert
AFTER INSERT ON Arsip
FOR EACH ROW
BEGIN
    INSERT INTO Audit_Log (Table_Name, Action_Type, Record_ID, New_Value)
    VALUES ('Arsip', 'INSERT', NEW.ID_Arsip, NEW.Nama_Arsip);
END$$

CREATE TRIGGER tr_arsip_delete
BEFORE DELETE ON Arsip
FOR EACH ROW
BEGIN
    INSERT INTO Audit_Log (Table_Name, Action_Type, Record_ID, Old_Value)
    VALUES ('Arsip', 'DELETE', OLD.ID_Arsip, OLD.Nama_Arsip);
END$$

DELIMITER ;

-- ============================================
-- 6. STORED PROCEDURES
-- ============================================

DELIMITER $$

CREATE PROCEDURE GetArsipByStatus(IN p_status VARCHAR(50))
BEGIN
    SELECT a.*, j.Nama_Jenis_Arsip, k.Nama_Klasifikasi, p.Lokasi_Penyimpanan, pg.Nama_Pegawai
    FROM Arsip a
    LEFT JOIN Jenis_Arsip j ON a.ID_Jenis_Arsip = j.ID_Jenis_Arsip
    LEFT JOIN Klasifikasi_Arsip k ON a.ID_Klasifikasi_Arsip = k.ID_Klasifikasi_Arsip
    LEFT JOIN Penyimpanan p ON a.ID_Penyimpanan = p.ID_Penyimpanan
    LEFT JOIN Pegawai pg ON a.ID_Pegawai = pg.ID_Pegawai
    WHERE a.Status_Arsip = p_status;
END$$

CREATE PROCEDURE GetStatistikArsip()
BEGIN
    SELECT 
        COUNT(*) AS Total_Arsip,
        SUM(CASE WHEN Status_Arsip = 'Aktif' THEN 1 ELSE 0 END) AS Arsip_Aktif,
        SUM(CASE WHEN Status_Arsip = 'Inaktif' THEN 1 ELSE 0 END) AS Arsip_Inaktif,
        SUM(CASE WHEN Status_Arsip = 'Terarsipkan' THEN 1 ELSE 0 END) AS Arsip_Terarsipkan,
        SUM(CASE WHEN Status_Arsip = 'Musnah' THEN 1 ELSE 0 END) AS Arsip_Musnah
    FROM Arsip;
END$$

DELIMITER ;

-- ============================================
-- 7. DATA AWAL (Sample Data)
-- ============================================

-- Insert default admin user (password: password)
INSERT INTO Users (Username, Password, Nama_Lengkap, Email, Role, Status) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@disarda.cimahi.go.id', 'Admin', 'Aktif'),
('operator', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Operator Arsip', 'operator@disarda.cimahi.go.id', 'Operator', 'Aktif');

-- Insert sample Jenis Arsip
INSERT INTO Jenis_Arsip (Nama_Jenis_Arsip, Kode_Jenis, Deskripsi_Jenis_Arsip) VALUES
('Surat Masuk', 'SM', 'Arsip surat yang diterima dari pihak luar'),
('Surat Keluar', 'SK', 'Arsip surat yang dikirim ke pihak luar'),
('Peraturan Daerah', 'PERDA', 'Arsip peraturan daerah kota Cimahi'),
('Keputusan Walikota', 'KEPWAL', 'Arsip keputusan walikota'),
('Laporan', 'LAP', 'Arsip laporan kegiatan dan keuangan'),
('Dokumen Kepegawaian', 'DK', 'Arsip dokumen terkait kepegawaian');

-- Insert sample Klasifikasi Arsip
INSERT INTO Klasifikasi_Arsip (Kode_Klasifikasi, Nama_Klasifikasi, Deskripsi_Klasifikasi) VALUES
('000', 'Umum', 'Arsip umum dan tata usaha'),
('100', 'Pemerintahan', 'Arsip terkait pemerintahan'),
('200', 'Politik', 'Arsip terkait politik dan organisasi'),
('300', 'Keamanan', 'Arsip terkait keamanan dan ketertiban'),
('400', 'Kesejahteraan', 'Arsip terkait kesejahteraan rakyat'),
('500', 'Perekonomian', 'Arsip terkait perekonomian'),
('600', 'Pekerjaan Umum', 'Arsip terkait pekerjaan umum'),
('700', 'Pengawasan', 'Arsip terkait pengawasan'),
('800', 'Kepegawaian', 'Arsip terkait kepegawaian'),
('900', 'Keuangan', 'Arsip terkait keuangan');

-- Insert sample Penyimpanan
INSERT INTO Penyimpanan (Kode_Lokasi, Lokasi_Penyimpanan, Kapasitas_Penyimpanan, Jenis_Penyimpanan, Kondisi_Penyimpanan) VALUES
('RAK-A1', 'Rak A Lantai 1', 1000, 'Fisikal', 'Baik'),
('RAK-A2', 'Rak A Lantai 2', 1000, 'Fisikal', 'Baik'),
('RAK-B1', 'Rak B Lantai 1', 800, 'Fisikal', 'Baik'),
('SRV-01', 'Server Utama', 10000, 'Server', 'Baik'),
('SRV-02', 'Server Backup', 10000, 'Server', 'Baik');

-- Insert sample Pegawai
INSERT INTO Pegawai (Nama_Pegawai, NIP, Jabatan, Tugas, Tanggal_Masuk, Email, Status) VALUES
('Budi Santoso', '198501012010011001', 'Kepala Dinas', 'Memimpin dan mengkoordinasikan kegiatan dinas', '2010-01-01', 'budi.santoso@disarda.cimahi.go.id', 'Aktif'),
('Siti Nurhaliza', '199001152015012001', 'Kepala Seksi Arsip', 'Mengelola arsip dan dokumentasi', '2015-01-15', 'siti.nurhaliza@disarda.cimahi.go.id', 'Aktif'),
('Ahmad Dahlan', '199205202018011001', 'Arsiparis', 'Pengolahan dan pelestarian arsip', '2018-01-20', 'ahmad.dahlan@disarda.cimahi.go.id', 'Aktif'),
('Dewi Lestari', '199308102019012001', 'Arsiparis', 'Pengolahan dan pelestarian arsip', '2019-01-10', 'dewi.lestari@disarda.cimahi.go.id', 'Aktif');

-- Insert sample Roles
INSERT INTO Roles (Nama_Role, Deskripsi_Role, Hak_Akses) VALUES
('Administrator', 'Akses penuh ke semua fitur sistem', 'all'),
('Operator', 'Dapat mengelola arsip dan data master', 'arsip,pegawai,pengguna'),
('Viewer', 'Hanya dapat melihat data arsip', 'view');

-- Insert sample Pengguna
INSERT INTO Pengguna (Nama_Pengguna, Tipe_Pengguna, Email_Pengguna, Nomor_Telepon, Instansi) VALUES
('PT. Maju Bersama', 'Eksternal', 'info@majubersama.co.id', '022-12345678', 'PT. Maju Bersama'),
('Dinas Pendidikan', 'Internal', 'disdik@cimahi.go.id', '022-87654321', 'Dinas Pendidikan Kota Cimahi'),
('Bagian Umum Setda', 'Internal', 'bagum@cimahi.go.id', '022-11223344', 'Sekretariat Daerah Kota Cimahi');

-- Insert sample Arsip
INSERT INTO Arsip (Nomor_Arsip, Nama_Arsip, Deskripsi_Arsip, Tanggal_Arsip, Tanggal_Masuk, Status_Arsip, ID_Jenis_Arsip, ID_Klasifikasi_Arsip, ID_Penyimpanan, ID_Pegawai, Tags) VALUES
('SM/2024/001', 'Surat Permohonan Kerjasama', 'Surat permohonan kerjasama dari PT. Maju Bersama', '2024-01-15', '2024-01-16', 'Aktif', 1, 1, 1, 3, 'kerjasama,permohonan'),
('SK/2024/001', 'Surat Balasan Kerjasama', 'Surat balasan atas permohonan kerjasama', '2024-01-20', '2024-01-20', 'Aktif', 2, 1, 1, 3, 'kerjasama,balasan'),
('PERDA/2024/001', 'Perda Pengelolaan Arsip', 'Peraturan Daerah tentang Pengelolaan Arsip Kota Cimahi', '2024-02-01', '2024-02-05', 'Aktif', 3, 2, 4, 2, 'perda,arsip,pengelolaan'),
('LAP/2024/001', 'Laporan Keuangan Q1 2024', 'Laporan keuangan triwulan pertama tahun 2024', '2024-04-01', '2024-04-05', 'Aktif', 5, 10, 4, 2, 'laporan,keuangan,q1');
