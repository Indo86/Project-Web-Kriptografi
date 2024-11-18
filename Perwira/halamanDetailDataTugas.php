<?php 
session_start();
include("../connect.php");
$id = $_SESSION['id'];
$kode = $_GET['kode'];

if (!isset($_SESSION["loginPerwira"])) {
    header("Location: ../loginPerwira.php");
    exit;
}

// Fetch data
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

// Fungsi dekripsi Caesar Cipher
function decryptCaesar($data, $shift) {
    $result = "";
    for ($i = 0; $i < strlen($data); $i++) {
        $char = $data[$i];
        if (ctype_alpha($char)) {
            $shifted = ord($char) - $shift;
            if (ctype_lower($char)) {
                if ($shifted < ord('a')) {
                    $shifted += 26;
                }
            } elseif (ctype_upper($char)) {
                if ($shifted < ord('A')) {
                    $shifted += 26;
                }
            }
            $result .= chr($shifted);
        } else {
            $result .= $char;
        }
    }
    return $result;
}

// Super dekripsi (AES + Caesar Cipher)
function superDecrypt($data, $key, $iv, $chiperAlgo, $options, $caesarShift) {
    $decryptedCaesar = decryptCaesar($data, $caesarShift);
    return decryptAES($decryptedCaesar, $key, $iv, $chiperAlgo, $options);
}

// Steganografi untuk mendekripsi pesan dalam gambar
function decryptImage($imagePath)
{
    $img = imagecreatefromstring(file_get_contents($imagePath));
    if (!$img) {
        return "Gagal membaca gambar!";
    }

    $width = imagesx($img);
    $height = imagesy($img);
    $hiddenMessage = "";
    $charBuffer = 0;
    $bitCount = 0;

    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $rgb = imagecolorat($img, $x, $y);
            $r = ($rgb >> 16) & 1; // Bit LSB dari Red
            $g = ($rgb >> 8) & 1;  // Bit LSB dari Green
            $b = $rgb & 1;         // Bit LSB dari Blue

            // Proses bit Red
            $charBuffer = ($charBuffer << 1) | $r;
            $bitCount++;
            if ($bitCount == 8) {
                $hiddenMessage .= chr($charBuffer);
                if (substr($hiddenMessage, -1) === "|") {
                    imagedestroy($img);
                    return rtrim($hiddenMessage, "|");
                }
                $charBuffer = 0;
                $bitCount = 0;
            }

            // Proses bit Green
            if ($bitCount < 8) {
                $charBuffer = ($charBuffer << 1) | $g;
                $bitCount++;
                if ($bitCount == 8) {
                    $hiddenMessage .= chr($charBuffer);
                    if (substr($hiddenMessage, -1) === "|") {
                        imagedestroy($img);
                        return rtrim($hiddenMessage, "|");
                    }
                    $charBuffer = 0;
                    $bitCount = 0;
                }
            }

            // Proses bit Blue
            if ($bitCount < 8) {
                $charBuffer = ($charBuffer << 1) | $b;
                $bitCount++;
                if ($bitCount == 8) {
                    $hiddenMessage .= chr($charBuffer);
                    if (substr($hiddenMessage, -1) === "|") {
                        imagedestroy($img);
                        return rtrim($hiddenMessage, "|");
                    }
                    $charBuffer = 0;
                    $bitCount = 0;
                }
            }
        }
    }

    imagedestroy($img);
    return "Pesan tidak ditemukan!";
}


// Kunci untuk dekripsi
$keyAes = 'makanmakanmakanp';
$ivAes = '12345678abcdefgh';
$chiperAlgo = 'AES-128-CBC';
$options = 0;
$caesarShift = 3;
$verifikasi = false;

if (isset($_POST['submit'])) {
    $kodeUnik = $_POST['kodeUnik'];
   
    if ($kodeUnik === $perwira['unik']) { // Cek kode unik
        $tugas['kode'] = superDecrypt($tugas['kode'], $keyAes, $ivAes, $chiperAlgo, $options, $caesarShift);
        $tugas['judul'] = superDecrypt($tugas['judul'], $keyAes, $ivAes, $chiperAlgo, $options, $caesarShift);
        $tugas['pesan'] = superDecrypt($tugas['pesan'], $keyAes, $ivAes, $chiperAlgo, $options, $caesarShift);
        $agen['nama_alias'] = decryptAES($agen['nama_alias'], $keyAes, $ivAes, $chiperAlgo, $options);
        $perwira['nama_alias'] = decryptAES($perwira['nama_alias'], $keyAes, $ivAes, $chiperAlgo, $options);
        $pesan_rahasia = decryptImage("../Assets/img/" . $tugas['gambar']);
        $verifikasi = true;
    } else {
        echo "<script>alert('Kode unik salah!');</script>";
    }
}
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
  <div id="main" class="mt-3 mb-4">
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
        <div class="col-4 d-flex justify-content-end">
     
        </div>
       </div>
      </div>
          <div class="card-body">
            <div class="col-12">
                  <div class="img-profile d-flex flex-row justify-content-center mb-3 gap-2">
                      <div class="card shadow-sm" style="width: 20rem;">
                          <img src="../Assets/img/<?= $tugas['gambar']?>" class="card-img-top" alt="...">
                      </div>
                      <?php if($verifikasi){ ?>
                        <div class="mb-3 col">
                            <label for="pesan" class="col-sm-2 col-form-label">Pesan Tersembunyi</label>
                            <div class="col-sm-10">
                              <textarea 
                                class="form-control" 
                                id="pesan" 
                                name="pesan" 
                                rows="5" 
                                style="resize: none; height: 10rem; width: 20rem;" value="<?= $pesan_rahasia; ?>" readonly>
                                <?= $pesan_rahasia; ?>
                              </textarea>
                            </div>
                          </div>
                        <?php } ?>
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
                <?php if($tugas['file_kasus'] !== '' && !$verifikasi) { ?>
                  <a href="downloadFile.php?namaFile=<?= $tugas['file_kasus']; ?>">
                  <button type="button" class="btn btn-outline-primary mt-5"><i class="bi bi-file-earmark-arrow-down-fill"></i> Download Dokumen Kasus</button>
                  </a>
                <?php }else if($verifikasi){ ?>
                  <a href="downloadFileDecrypt.php?namaFile=<?= $tugas['file_kasus']; ?>">
                  <button type="button" class="btn btn-outline-primary mt-5"><i class="bi bi-file-earmark-arrow-down-fill"></i> Download Dokumen Kasus</button>
                  </a>
                <?php  }?>
                </div>
                <!-- form verifikasi -->
                    <form action="" method="post" enctype="multipart/form-data" class="mt-3">
                        <div class="mb-3">
                            <label for="kodeUnik" class="form-label">Keunikan anda apa?</label>
                            <input type="password" class="form-control" id="kodeUnik" name="kodeUnik" required>
                        </div>
                        <button type="submit" class="btn btn-outline-warning" name="submit">Dekripsi <i class="bi bi-file-earmark-break-fill"></i> </button>
                    </form>
              
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