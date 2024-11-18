<?php 
session_start();
include("../connect.php");
$id = $_SESSION['id'];
$kode = $_GET['kode'];

if(!isset($_SESSION["loginAgen"])){
  header("Location: ../loginAgen.php");
  exit;
}


// kunci aes
$keyAes = 'makanmakanmakanp';
$ivAes = '12345678abcdefgh';;
$chiperAlgo= 'AES-128-CBC';
$options = 0;



if(isset($_POST["submit"])){


  function uploadFileLaporan()
  {
      $namaFile = $_FILES['file_laporan']['name']; // Nama file asli
      $tmpName = $_FILES['file_laporan']['tmp_name']; // Lokasi file sementara
      $ukuranFile = $_FILES['file_laporan']['size'];
  
      // Validasi file
      if ($ukuranFile > 2000000) {
          echo "<script>alert('Ukuran file terlalu besar!');</script>";
          return false;
      }
  
      // Enkripsi file menggunakan Triple DES
      $key = 'enkripsiRahasia123enkripsiRahasia123'; // Kunci enkripsi (24 byte untuk 3DES)
      $cipher = 'des-ede3-cbc'; // Triple DES (3DES) dalam mode CBC
      $iv = '12345678'; // IV (8 byte untuk DES/3DES)
  
      // Membaca file
      $data = file_get_contents($tmpName);
  
      // Menambahkan nama file asli ke data sebelum enkripsi
      $dataWithMetadata = $namaFile . "::" . $data;
  
      // Enkripsi data
      $encryptedData = openssl_encrypt($dataWithMetadata, $cipher, $key, 0, $iv);
  
      // Membuat nama file terenkripsi
      $namaFileEnkripsi = uniqid('encrypted_') . '.enc';
      $path = '../Assets/files/' . $namaFileEnkripsi;
  
      // Simpan IV dan data terenkripsi ke dalam file
      file_put_contents($path, $iv . $encryptedData);
  
      return $namaFileEnkripsi;
  }
  
  
// Fungsi enkripsi AES (Modern Algorithm)
function encryptAES($data, $key, $iv, $chiperAlgo, $options) {
  return openssl_encrypt($data, $chiperAlgo, $key, $options, $iv);
}


// Fungsi enkripsi Caesar Cipher (Klasik Algorithm)
function caesarEncrypt($data, $shift) {
  $result = "";
  for ($i = 0; $i < strlen($data); $i++) {
      $char = $data[$i];
      if (ctype_alpha($char)) {
          $shifted = ord($char) + $shift;
          if (ctype_lower($char)) {
              if ($shifted > ord('z')) {
                  $shifted -= 26;
              }
          } elseif (ctype_upper($char)) {
              if ($shifted > ord('Z')) {
                  $shifted -= 26;
              }
          }
          $result .= chr($shifted);
      } else {
          $result .= $char;
      }
  }
  return $result;
}

$keyAes = 'makanmakanmakanp';
$ivAes = '12345678abcdefgh';
$chiperAlgo = 'AES-128-CBC';
$options = 0;
$caesarShift = 3; // Misalnya geser 3 untuk Caesar Cipher


// Super enkripsi (AES + Caesar Cipher)
function superEncrypt($data, $key, $iv, $chiperAlgo, $options, $caesarShift) {
  // Enkripsi pertama dengan AES
  $encryptedAES = encryptAES($data, $key, $iv, $chiperAlgo, $options);
  
  // Enkripsi kedua dengan Caesar Cipher
  return caesarEncrypt($encryptedAES, $caesarShift);
}

$file_laporan = uploadFileLaporan();
$status = $_POST['status'];
// Enkripsi data
$encryptedStatus = superEncrypt($status, $keyAes, $ivAes, $chiperAlgo, $options, $caesarShift);
echo $encryptedStatus;
$query = "UPDATE tugas SET
status = '$encryptedStatus',
file_laporan = '$file_laporan'
WHERE kode = '$kode'";

// Eksekusi query
mysqli_query($conn, $query);

header('Location: halamanTugasAgen.php');
}


?>




<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Unggah Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  </head>
  <body>
<!-- Judul -->
<div class="container mt-2 mb-2">
<div class="card shadow-sm bg-dark">
  <div class="card-body">
  <div class="row">
    <div class="col-4">
        <a href="halamanTugasAgen.php">
        <button type="button" class="btn btn-outline-light content-start"><i class="bi bi-arrow-left"></i> Kembali</button>
        </a>
    </div>
  
    <div class="col-4">

      <h3 class="text-light text-center">Form Laporan</h3>

    </div>
    <div class="col-4">

      </div>
 </div>

    
  </div>
</div>

</div>
<!-- Akhir Judul -->
<!-- form Permohonan -->
<div class="container mt-2 mb-3">

      <div class="card shadow-sm">
        <div class="card-body">


        <form action="" method="post" enctype="multipart/form-data">


    <div class="mb-3 row">
      <label for="status" class="col-sm-2 col-form-label">Status</label>
      <div class="col-sm-10">
        <select class="form-select" aria-label="Default select example" name="status" required>
          <option selected>Pilih Status</option>
          <option value="Belum Mulai">Belum Mulai</option>
          <option value="Proses">Proses</option>
          <option value="Sukses">Sukses</option>
          <option value="Gagal">Gagal</option>
        </select>
      </div>
    </div>

  
  <div class="mb-3 row">
  <label for="file_laporan" class=" col-sm-2 form-label">File Laporan</label>
  <div class="col-sm-10">
     <input class="form-control" type="file" id="file_laporan" name="file_laporan" required> 
    </div>
  </div>

  <div class="mt-4">
      <button type="submit" name="submit" class="btn btn-primary">Submit</button>
      <button type="reset"  name="reset" class="btn btn-danger">Reset</button>
    </div>
  </form>

        </div>
      </div>
  </div>
<!-- Akhir Form Permohonan -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>