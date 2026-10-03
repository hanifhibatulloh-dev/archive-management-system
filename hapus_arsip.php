<?php
// hapus_arsip.php
require_once 'includes/header.php'; // Pastikan koneksi DB ada di sini

// Cek apakah ada ID yang dikirim
if (isset($_GET['id'])) {
    $id_arsip = $_GET['id'];
    $db = getDB(); // Ambil koneksi database

    try {
        // Hapus data berdasarkan ID_Arsip (sesuai database Anda)
        $query = "DELETE FROM arsip WHERE ID_Arsip = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id_arsip]);

        // Jika berhasil, redirect kembali ke arsip.php
        echo "<script>
                alert('Data berhasil dihapus!');
                window.location.href = 'arsip.php';
              </script>";
    } catch (PDOException $e) {
        // Jika gagal
        echo "<script>
                alert('Gagal menghapus data: " . $e->getMessage() . "');
                window.location.href = 'arsip.php';
              </script>";
    }
} else {
    // Jika tidak ada ID, kembalikan saja
    header("Location: arsip.php");
    exit();
}
?>