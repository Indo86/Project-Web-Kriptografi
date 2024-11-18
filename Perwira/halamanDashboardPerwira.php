<?php 
session_start();
include("../connect.php");
$id = $_SESSION['id'];

if(!isset($_SESSION["loginPerwira"])){
  header("Location: ../loginPerwira.php");
  exit;
}



$queriPerwira = "SELECT * FROM perwira WHERE id = '$id'";
$result = mysqli_query($conn, $queriPerwira);
$perwira = mysqli_fetch_assoc($result);


// Query untuk menghitung jumlah data
$queriJumlahTugas = "SELECT COUNT(*) AS totalTugas FROM tugas  WHERE id_perwira = '$id'";
$resultJumlahTugas = mysqli_query($conn,$queriJumlahTugas );
$jumlahTugas = mysqli_fetch_assoc($resultJumlahTugas);


$queriJumlahAgen = "SELECT COUNT(*) AS totalAgen FROM agen";
$resultJumlahAgen = mysqli_query($conn,$queriJumlahAgen );
$jumlahAgen = mysqli_fetch_assoc($resultJumlahAgen);

?>



<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Perwira</title>
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
        <a href="#" class="list-group-item list-group-item-action py-2 ripple active" aria-current="true">
          <i class="bi bi-speedometer me-3"></i><span>Dashboard</span>
        </a>
        <a href="halamanTugasPerwira.php" class="list-group-item list-group-item-action py-2 ripple">
          <i class="bi bi-list-task me-3"></i><span>Tugas</span>
        </a>
        <a href="halamanArsipTugasPerwira.php" class="list-group-item list-group-item-action py-2 ripple">
          <i class="bi bi-archive-fill me-3"></i><span>Arsip Tugas</span>
        </a>
        <a href="halamanDataPerwira.php" class="list-group-item list-group-item-action py-2 ripple">
        <i class="bi bi-bookmark-star me-3"></i><span>Data Perwira</span>
        </a>
        <a href="halamanDataAgen.php" class="list-group-item list-group-item-action py-2 ripple">
          <i class="bi bi-people-fill me-3"></i><span>Data Agen</span>
        </a>
        <a href="halamanProfilePerwira.php" class="list-group-item list-group-item-action py-2 ripple">
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
      <h1>Dashboard Komandan <?= $perwira['nama'] ?></h1>
    </div>
    <div class="page-content">
    <div class="row dashboard-card">
      <div class="col-4 card-dashboard">
        <!-- card jumlah tugas -->
        <div class="card text-bg-primary mb-3" style="max-width: 540px;">
          <div class="row g-0">
            <div class="col-md-4 d-flex flex-column align-items-center justify-content-center">
                <h3><?= $jumlahAgen['totalAgen'] ?></h3>
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h3 class="card-title">Agen</h3>
                <p>Secret Service Agent</p>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- Jumlah pengumuman  -->
      <div class="col-4 card-dashboard">
        <!-- card jumlah Pengumuman -->
        <div class="card text-bg-warning mb-3" style="max-width: 540px;">
          <div class="row g-0">
            <div class="col-md-4 d-flex flex-column align-items-center justify-content-center">
                <h3>15</h3>
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h3 class="card-title">Pengumuman</h3>
                <p>Top secret anouncement</p>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- Jumlah Laporan  -->
      <div class="col-4 card-dashboard">
        <!-- card jumlah Laporan -->
        <div class="card text-bg-danger mb-3" style="max-width: 540px;">
          <div class="row g-0">
            <div class="col-md-4 d-flex flex-column align-items-center justify-content-center">
                <h3><?= $jumlahTugas['totalTugas'] ?></h3>
               
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h3 class="card-title">Tugas</h3>
                <p>Top secret tasks</p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
    </div>
  </div>
  <!-- End Main Layout -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>