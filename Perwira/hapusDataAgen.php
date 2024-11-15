<?php
session_start();
include("../connect.php");
$id = $_SESSION['id'];
$id_agen = $_GET['id'];

if(!isset($_SESSION["loginPerwira"])){
  header("Location: ../loginPerwira.php");
  exit;
}

$admin = "DELETE FROM agen WHERE id = '$id_agen'";
mysqli_query($conn, $admin);

header('Location: halamanDataAgen.php');

?>