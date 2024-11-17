<?php
session_start();
include("../connect.php");
$id = $_SESSION['id'];
$id_agen = $_GET['id'];

if(!isset($_SESSION["loginPerwira"])){
  header("Location: ../loginPerwira.php");
  exit;
}

// Memindahkan tugas yang terkait agen ke tabel arsip_tugas
$query_tugas = "SELECT * FROM tugas WHERE id_agen = '$id_agen'";
$query_agen = "SELECT * FROM agen WHERE id = '$id_agen'";
$result_tugas = mysqli_query($conn, $query_tugas);
$result_agen = mysqli_query($conn, $query_agen);
$agen = mysqli_fetch_assoc($result_agen);

if (mysqli_num_rows($result_tugas) > 0) {
    while ($tugas = mysqli_fetch_assoc($result_tugas)) {
        // Memindahkan tugas ke arsip
        $kode = $tugas['kode'];
        $judul = $tugas['judul'];
        $pesan = $tugas['pesan'];
        $file_kasus = $tugas['file_kasus'];
        $gambar = $tugas['gambar'];
        $id_perwira = $tugas['id_perwira'];
        $nama_agen = $agen['nama_asli'];
        $tanggal_mulai = $tugas['tanggal_mulai'];
        $tanggal_selesai = $tugas['tanggal_selesai'];
        $status = $tugas['status'];
        $file_laporan = $tugas['file_laporan'];

        $query_arsip = "INSERT INTO arsip_tugas (kode, judul, pesan, file_kasus, gambar, id_perwira, nama_agen, tanggal_mulai, tanggal_selesai, status, file_laporan) 
                        VALUES ('$kode', '$judul', '$pesan', '$file_kasus', '$gambar', '$id_perwira', '$nama_agen', '$tanggal_mulai', '$tanggal_selesai', '$status', '$file_laporan')";
        mysqli_query($conn, $query_arsip);

        // Setelah dipindahkan ke arsip, hapus tugas dari tabel tugas
        $delete_tugas = "DELETE FROM tugas WHERE id_agen = '$id_agen'";
        mysqli_query($conn, $delete_tugas);
    }
    
  }

$admin = "DELETE FROM agen WHERE id = '$id_agen'";
mysqli_query($conn, $admin);

header('Location: halamanDataAgen.php');

?>