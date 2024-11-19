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

$query_agen = "SELECT * FROM agen WHERE id = '$id_agen'";
$result_agen = mysqli_query($conn, $query_agen);
$agen = mysqli_fetch_assoc($result_agen);

$admin = "DELETE FROM agen WHERE id = '$id_agen'";
mysqli_query($conn, $admin);

header('Location: halamanDataAgen.php');

?>