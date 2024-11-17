
<?php
session_start();
include("../connect.php");


$file = $_GET['namaFile'];
$filePath = '../Assets/files/' . $file;

if(!isset($_SESSION["loginPerwira"])){
  header("Location: ../loginPerwira.php");
  exit;
}
if (file_exists($filePath)) {
  // Mengunduh file terenkripsi langsung
  header('Content-Description: File Transfer');
  header('Content-Type: application/enc'); // Anda bisa mengubah jenis MIME sesuai dengan jenis file yang diunduh
  header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
  header('Content-Transfer-Encoding: binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Length: ' . filesize($filePath));
  ob_clean();
  flush();
  readfile($filePath);
  exit;
} else {
  echo "
  <script>
      alert('File tidak ditemukan!');
      document.location.href = 'halamanTugasPerwira.php';
  </script>
  ";
}

?>



