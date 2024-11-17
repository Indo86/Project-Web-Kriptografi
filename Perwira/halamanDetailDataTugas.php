<?php 
session_start();
include("../connect.php");
$id = $_SESSION['id'];
$kode = $_GET['kode'];

if(!isset($_SESSION["loginPerwira"])){
  header("Location: ../loginPerwira.php");
  exit;
}

$queriTugas = "SELECT * FROM tugas WHERE kode = '$kode'";
$result = mysqli_query($conn, $queriTugas);
$tugas = mysqli_fetch_assoc($result);

$queriPerwira = "SELECT * FROM perwira WHERE id = '$id'";
$resultPerwira = mysqli_query($conn, $queriPerwira);
$perwira = mysqli_fetch_assoc($resultPerwira);

$id_agen = $tugas['id_agen'];
$queriAgen = "SELECT * FROM agen WHERE id = '$id_agen'";
$resultAgen = mysqli_query($conn, $queriAgen);
$agen = mysqli_fetch_assoc($resultAgen);



// Fungsi dekripsi AES
function decryptAES($data, $key, $iv, $chiperAlgo, $options) {
  return openssl_decrypt($data, $chiperAlgo, $key, $options, $iv);
}


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

// // Fungsi dekripsi Caesar Cipher
function decryptCaesar($data, $shift) {
  return caesarEncrypt($data, 26 - $shift);  // Dekripsi dengan menggeser terbalik
}

// Super dekripsi (AES + Caesar Cipher)
function superDecrypt($data, $key, $iv, $chiperAlgo, $options, $caesarShift) {
  // Dekripsi pertama dengan Caesar Cipher
  $decryptedCaesar = decryptCaesar($data, $caesarShift);
  
  // Dekripsi kedua dengan AES
  return decryptAES($decryptedCaesar, $key, $iv, $chiperAlgo, $options);
}



$keyAes = 'makanmakanmakanp';
$ivAes = '12345678abcdefgh';
$chiperAlgo = 'AES-128-CBC';
$options = 0;
$caesarShift = 3; // Misalnya geser 3 untuk Caesar Cipher



?>



<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman Detail Data Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" />
  <!-- Google Fonts Roboto -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
  <!-- MDB -->
  <link rel="stylesheet" href="css/bootstrap-side-navbar.min.css" />
  </head>
  <style>

  </style>
</head>
<body>

  <!-- Main Layout -->
  <div id="main" class="mt-3">
    <div class="page-content d-flex justify-content-center">
    <!-- <div class="row"> -->
  <!-- <div class="col-3">
     
  </div> -->
  <div class="col-8">
  <div class="card shadow-sm ">
      <div class="card-header bg-dark">
       <div class="row">
       <div class="col-4">
            <a href="halamanTugasPerwira.php">
            <button type="button" class="btn btn-outline-light content-start"><i class="bi bi-arrow-left"></i> Kembali</button>
            </a>
        </div>

        <div class="col-4">
        <h4 class="text-light text-center">Tugas Operasi <?= $tugas['judul']; ?></h4>
        </div>
        <div class="col-4">

        </div>
       </div>
      </div>
          <div class="card-body">
            <div class="col-12">
                  <div class="img-profile d-flex justify-content-center mb-3">
                      <div class="card shadow-sm" style="width: 20rem;">
                          <img src="../Assets/img/<?= $tugas['gambar']?>" class="card-img-top" alt="...">
                      </div>
                  </div>
            
                   <div class="row">
                      <div class=" col-5 grid gap-4" >
                        <p class="fw-bold">Kode</p>
                        <p class="fw-bold">Judul</p>
                        <p class="fw-bold">Tanggal Mulai</p>
                        <p class="fw-bold">Target Selesai</p>
                        <p class="fw-bold">Penanggung Jawab Tugas</p>
                        <p class="fw-bold">Pelaksana Tugas</p>
                        <p class="fw-bold">Status</p>
                        <p class="fw-bold">Pesan</p>
                        

                      </div>
                      <div class="col-7">
                        <p class="fw">: <?= $tugas['kode'] ?></p>
                        <p class="fw">: <?= $tugas['judul'] ?></p>
                        <p class="fw">: <?= $tugas['tanggal_mulai'] ?></p>
                        <p class="fw">: <?= $tugas['tanggal_selesai'] ?></p>
                        <p class="fw">: <?= $perwira['nama_alias'] ?></p>
                        <p class="fw">: <?= $agen['nama_alias'] ?> </p>
                        <p class="fw">: <?= $tugas['status'] ?></p>
                        <p class="fw">: <?= $tugas['pesan'] ?></p>
                  </div>
                <div class="row unduh">
                <?php if($tugas['file_kasus'] !== '') { ?>
                  <a href="downloadFile.php?namaFile=<?= $tugas['file_kasus']; ?>">
                  <button type="button" class="btn btn-outline-primary mt-5"><i class="bi bi-file-earmark-arrow-down-fill"></i> Download Dokumen Kasus</button>
                  </a>
                <?php }else{ ?>

                <?php  }?>
                </div>
                </div>
              </div>
            </div>
        </div>
      </div>
  </div>
</div>
<!-- Akhir Profil User -->

    </div>
  </div>
  <!-- End Main Layout -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>