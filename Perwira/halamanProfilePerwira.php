<?php 
session_start();
include("../connect.php");
$id = $_SESSION['id'];

if(!isset($_SESSION["loginPerwira"])){
  header("Location: ../loginPerwira.php");
  exit;
}


$keyAes = 'makanmakanmakanp';
$ivAes = '12345678abcdefgh';;
$chiperAlgo= 'AES-128-CBC';
$options = 0;

$queriPerwira = "SELECT * FROM perwira WHERE id = '$id'";
$result = mysqli_query($conn, $queriPerwira);
$perwira = mysqli_fetch_assoc($result);




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
    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      padding: 58px 0 0; /* Height of navbar */
      box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%), 0 2px 10px 0 rgb(0 0 0 / 5%);
      width: 240px;
      z-index: 600;
    }

    nav.nav-top{
      z-index: 601;
    }
    
    /* At screen widths below 992px, sidebar becomes offcanvas */
    @media (max-width: 991.98px) {
      .sidebar {
        width: 100%;
      }
    }

    #main {
      margin-left: 240px;
      padding: 15px;
    }

    @media (max-width: 991.98px) {
      #main {
        margin-left: 0;
        padding: 20px;
      }
      .offcanvas-backdrop.show {
        z-index: 1040;
      }

      .sidebar.offcanvas-lg {
        z-index: 1050; /* Pastikan sidebar lebih tinggi dari backdrop overlay */
      }
    }

    
  </style>
</head>
<body>

<!-- Main Navigation -->
<header>
  <!-- Sidebar / Offcanvas -->
  <div class="offcanvas-lg offcanvas-start sidebar bg-white" tabindex="1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
    <div class="offcanvas-header d-lg-none">
      <h5 class="offcanvas-title" id="offcanvasSidebarLabel">Menu</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasSidebar" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="list-group list-group-flush ms-4 mt-lg-4">
        <a href="halamanDashboardPerwira.php" class="list-group-item list-group-item-action py-2 ripple ">
          <i class="bi bi-speedometer me-3"></i><span>Dashboard</span>
        </a>
        <a href="halamanTugasPerwira.php" class="list-group-item list-group-item-action py-2 ripple">
          <i class="bi bi-list-task me-3"></i><span>Tugas</span>
        </a>
        <a href="halamanDataPerwira.php" class="list-group-item list-group-item-action py-2 ripple">
        <i class="bi bi-bookmark-star me-3"></i><span>Data Perwira</span>
        </a>
        <a href="halamanDataAgen.php" class="list-group-item list-group-item-action py-2 ripple">
          <i class="bi bi-people-fill me-3"></i><span>Data Agen</span>
        </a>
        <a href="#" class="list-group-item list-group-item-action py-2 ripple active" aria-current="true">
          <i class="bi bi-person-bounding-box me-3"></i><span>Profil</span>
        </a>
        <a href="logoutPerwira.php" style="text-decoration:none;" class="d-grid gap-2 col-10 mx-auto mt-5">
          <button class="btn btn-danger text-light" type="button">Log Out</button>
        </a>
      </div>
    </div>
  </div>
  <!-- End Sidebar / Offcanvas -->

  <!-- Navbar -->
  <nav class="navbar bg-dark navbar-dark nav-top">
    <div class="container-fluid">
      <!-- Toggle button for offcanvas, visible only on small screens -->
      <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="#">
        <img src="../Assets/img/mask.png" alt="Logo" width="35" height="35" class="d-inline-block align-text-center">
        Asosiasi Intelijen Negara Barat Daya
      </a>
    </div>
  </nav>
</header>
  <!-- Main Layout -->
  <div id="main">
    <div class="page-heading mb-4">
      <h1>Profil Perwira</h1>
    </div>
    <div class="page-content d-flex justify-content-center">
    <!-- <div class="row"> -->
  <!-- <div class="col-3">
     
  </div> -->
  <div class="col-8">
  <div class="card shadow-sm">
  <div class="card-header bg-dark text-center">
    <h4 class="text-light">Profil Perwira</h4>
      </div>
          <div class="card-body">
            <div class="col-12">
            <div class="img-profile d-flex justify-content-center mb-3">
                      <div class="card shadow-sm" style="width: 20rem;">
                          <img src="../Assets/img/<?= openssl_decrypt($perwira['gambar'],$chiperAlgo,$keyAes, $options, $ivAes)?>" class="card-img-top" alt="...">
                      </div>
                  </div>
                   <div class="row">
                      <div class=" col-5 grid gap-4" >
                        <p class="fw-bold">ID</p>
                        <p class="fw-bold">Nama</p>
                        <p class="fw-bold">Jabatan</p>
                        <p class="fw-bold">Jenis Kelamin</p>
                        <p class="fw-bold">Wilayah Tugas</p>
                        <!-- <p class="fw-bold ms-3">RT/RW</p>
                        <p class="fw-bold ms-3">Kel/Desa</p>
                        <p class="fw-bold ms-3">Kecamatan</p>
                        <p class="fw-bold">Setatus Perkawinan</p>
                        <p class="fw-bold">Pekerjaan</p>
                        <p class="fw-bold">Kewarganegaraan</p>
                        <p class="fw-bold">Gol Darah</p> -->
                      </div>
                      <div class="col-7">
                        <p class="fw">: <?=  openssl_decrypt($perwira['id2'],$chiperAlgo,$keyAes, $options, $ivAes)?></p>
                        <p class="fw">: <?=  openssl_decrypt($perwira['nama'] ,$chiperAlgo,$keyAes, $options, $ivAes)?></p>
                        <p class="fw">: <?=  openssl_decrypt($perwira['jabatan'] ,$chiperAlgo,$keyAes, $options, $ivAes)?></p>
                        <p class="fw">: <?=  openssl_decrypt($perwira['jenis_kelamin'] ,$chiperAlgo,$keyAes, $options, $ivAes)?></p>
                        <p class="fw">: <?=  openssl_decrypt($perwira['wilayah_tugas'] ,$chiperAlgo,$keyAes, $options, $ivAes)?> </p>
                        <!-- <p class="fw">: </p>
                        <p class="fw">: </p>
                        <p class="fw">: </p>
                        <p class="fw">: </p>
                        <p class="fw">: </p>
                        <p class="fw">: </p>
                        <p class="fw">: </p> -->
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