<?php 
session_start();
include("../connect.php");
$id = $_SESSION['id'];

if(!isset($_SESSION["loginPerwira"])){
  header("Location: ../loginPerwira.php");
  exit;
}



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

// tanggal mulai
// target selesai
// judul
// anggen pelaksana


?>




<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tugas Perwira</title>
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
        <a href="#" class="list-group-item list-group-item-action py-2 ripple active" aria-current="true">
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
      <h1>Tugas Perwira</h1>
    </div>
    <div class="page-content">
    <div class="button-tambah d-flex justify-content-end mb-3">
          <a href="halamanTambahDataTugas.php" style="text-decoration:none">
                <button type="button" class="btn btn-outline-secondary"><i class="bi bi-file-earmark-plus ms-0 me-2"></i>Buat Tugas</button>
            </a>
    </div>
    <table class="table">
        <thead class="table-primary">
          <tr>
            <th scope="col">No</th>
            <th scope="col">Tanggal Mulai</th>
            <th scope="col">Target Selesai</th>
            <th scope="col">Judul</th>
            <th scope="col">Agent Pelaksana</th>
            <th scope="col">Status</th>
            <th scope="col">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $no = 1;
          $queriTugas = "SELECT * FROM tugas WHERE id_perwira = '$id'";
          $resultTugas = mysqli_query($conn, $queriTugas);


          while( $tugas = mysqli_fetch_assoc($resultTugas)){
            // digunakan untuk mencari agen yang menerima tugas
            $idPenerimaTugas = $tugas['id_agen'];
            $queriAgen = "SELECT * FROM agen WHERE id = '$idPenerimaTugas'";
            $resultAgen = mysqli_query($conn, $queriAgen);
            $agen = mysqli_fetch_assoc($resultAgen);
          
          ?>
          <tr>
            <th scope="row"><?= $no++ ?></th>
            <td><?= $tugas['tanggal_mulai'] ?></td>
            <td><?= $tugas['tanggal_selesai'] ?></td>
            <td><?= superDecrypt( $tugas['judul'], $keyAes, $ivAes, $chiperAlgo, $options, $caesarShift) ?></td>
            <td>
            <?= openssl_decrypt($agen['nama_alias'],$chiperAlgo,$keyAes, $options, $ivAes)?>
            </td>
            <td>
              <span class="badge bg-primary"> <?=         $tugas['status'] = superDecrypt($tugas['status'], $keyAes, $ivAes, $chiperAlgo, $options, $caesarShift); ?> </span>
            </td>
            <td>
            <a href="halamanDetailDataTugas.php?kode=<?= $tugas['kode'] ?>" style="text-decoration:none">
                <button type="button" class="btn btn-outline-primary">Detail</button>
            </a>
            <a href="hapusDataTugas.php?kode=<?= $tugas['kode']?>" style="text-decoration:none">
                <button type="button" class="btn btn-outline-danger">Hapus</button>
            </a>
            </td>
          </tr>
            <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  <!-- End Main Layout -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>