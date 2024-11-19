<?php 
session_start();
include("../connect.php");

// Pastikan sesi login perwira sudah diatur
if (!isset($_SESSION["loginPerwira"])) {
    header("Location: ../loginPerwira.php");
    exit;
}

$id = $_SESSION['id'];
$kode = $_GET['kode'];

// Ambil data tugas berdasarkan kode
$query_tugas = "SELECT * FROM tugas WHERE kode = '$kode'";
$result_tugas = mysqli_query($conn, $query_tugas);


    
    $query_delete = "DELETE FROM tugas WHERE kode = '$kode'";
    mysqli_query($conn, $query_delete);

header('Location: halmanTugasPerwira.php');

?>
