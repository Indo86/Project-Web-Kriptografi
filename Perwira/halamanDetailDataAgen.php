<?php 
session_start();
include("../connect.php");
$id = $_SESSION['id'];
$id_agen = $_GET['id'];

if(!isset($_SESSION["loginPerwira"])){
  header("Location: ../loginPerwira.php");
  exit;
}

$keyAes = 'makanmakanmakanp';
$ivAes = '12345678abcdefgh';;
$chiperAlgo= 'AES-128-CBC';
$options = 0;

$queriAgen = "SELECT * FROM agen WHERE id = '$id_agen'";
$result = mysqli_query($conn, $queriAgen);
$agen = mysqli_fetch_assoc($result);


?>



<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Agen</title>
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
            <a href="halamanDataAgen.php">
            <button type="button" class="btn btn-outline-light content-start"><i class="bi bi-arrow-left"></i> Kembali</button>
            </a>
        </div>

        <div class="col-4">
        <h4 class="text-light text-center">Profil Agen <?= openssl_decrypt($agen['nama_alias'],$chiperAlgo,$keyAes, $options, $ivAes)?></h4>
        </div>
        <div class="col-4">

        </div>
       </div>
      </div>
          <div class="card-body">
            <div class="col-12">
                  <div class="img-profile d-flex justify-content-center mb-3">
                      <div class="card shadow-sm" style="width: 20rem;">
                          <img src="../Assets/img/<?= openssl_decrypt($agen['gambar'],$chiperAlgo,$keyAes, $options, $ivAes)?>" class="card-img-top" alt="...">
                      </div>
                  </div>
            
                   <div class="row">
                      <div class=" col-5 grid gap-4" >
                        <p class="fw-bold">ID</p>
                        <p class="fw-bold">Nama Asli</p>
                        <p class="fw-bold">Nama Samaran</p>
                        <p class="fw-bold">Jabatan</p>
                        <p class="fw-bold">Jenis Kelamin</p>
                        <p class="fw-bold">Wilayah Tugas</p>

                      </div>
                      <div class="col-7">
                        <p class="fw">: <?= openssl_decrypt($agen['id2'],$chiperAlgo,$keyAes, $options, $ivAes)?></p>
                        <p class="fw">: <?= openssl_decrypt( $agen["nama_asli"],$chiperAlgo,$keyAes, $options, $ivAes)?></p>
                        <p class="fw">: <?= openssl_decrypt($agen['nama_alias'],$chiperAlgo,$keyAes, $options, $ivAes) ?></p>
                        <p class="fw">: <?= openssl_decrypt($agen['jabatan'] ,$chiperAlgo,$keyAes, $options, $ivAes)?></p>
                        <p class="fw">: <?= openssl_decrypt($agen['jenis_kelamin'],$chiperAlgo,$keyAes, $options, $ivAes)?></p>
                        <p class="fw">: <?= openssl_decrypt($agen['penempatan'],$chiperAlgo,$keyAes, $options, $ivAes)?> </p>
                      </div>
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