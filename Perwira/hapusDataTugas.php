<?php 
session_start();
include("../connect.php");
$id = $_SESSION['id'];
$kode = $_GET['kode'];

if(!isset($_SESSION["loginPerwira"])){
  header("Location: ../loginPerwira.php");
  exit;
}

$tugas = "DELETE FROM tugas WHERE kode = '$kode'";
mysqli_query($conn, $tugas);

header('Location: halamanTugasPerwira.php');


?>