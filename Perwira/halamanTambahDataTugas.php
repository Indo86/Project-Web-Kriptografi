<?php 
session_start();
include("../connect.php");
$id = $_SESSION['id'];

if(!isset($_SESSION["loginPerwira"])){
  header("Location: ../loginPerwira.php");
  exit;
}

// Ambil data agen dari database
$query_agen = "SELECT id, nama_alias FROM agen";
$result_agen = mysqli_query($conn, $query_agen);

// kunci aes
$keyAes = 'makanmakanmakanp';
$ivAes = '12345678abcdefgh';;
$chiperAlgo= 'AES-128-CBC';
$options = 0;


// data perwira
$queriPerwira = "SELECT * FROM perwira WHERE id = '$id'";
$result = mysqli_query($conn, $queriPerwira);
$perwira = mysqli_fetch_assoc($result);


if(isset($_POST["submit"])){
  $pesan_tersembunyi = $_POST['pesan_tersembunyi'];

  function uploadGambar($pesan_tersembunyi)
{
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // Validasi ekstensi gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>alert('File yang diupload bukan gambar!');</script>";
        return false;
    }

    // Validasi ukuran file
    if ($ukuranFile > 10000000) {
        echo "<script>alert('Ukuran file terlalu besar!');</script>";
        return false;
    }

    // Proses steganografi: Sisipkan pesan ke dalam gambar
    $img = imagecreatefromstring(file_get_contents($tmpName));
    if (!$img) {
        echo "<script>alert('Gagal memproses gambar!');</script>";
        return false;
    }

    // Sisipkan pesan (setiap bit pesan tersembunyi ke pixel)
    $pesan_tersembunyi .= '|'; // Tandai akhir pesan
    $pesanBits = [];
    for ($i = 0; $i < strlen($pesan_tersembunyi); $i++) {
        $char = ord($pesan_tersembunyi[$i]);
        for ($j = 7; $j >= 0; $j--) {
            $pesanBits[] = ($char >> $j) & 1; // Ekstrak setiap bit dari karakter
        }
    }

    $bitIndex = 0;
    $width = imagesx($img);
    $height = imagesy($img);

    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            if ($bitIndex < count($pesanBits)) {
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                $r = ($r & 0xFE) | $pesanBits[$bitIndex++];
                if ($bitIndex < count($pesanBits)) {
                    $g = ($g & 0xFE) | $pesanBits[$bitIndex++];
                }
                if ($bitIndex < count($pesanBits)) {
                    $b = ($b & 0xFE) | $pesanBits[$bitIndex++];
                }

                $color = imagecolorallocate($img, $r, $g, $b);
                imagesetpixel($img, $x, $y, $color);
            }
        }
    }

    // Simpan gambar baru
    $namaFileBaru = uniqid() . '.' . $ekstensiGambar;
    $path = '../Assets/img/' . $namaFileBaru;

    if ($ekstensiGambar === 'png') {
        imagepng($img, $path);
    } else {
        imagejpeg($img, $path);
    }

    imagedestroy($img);
    return $namaFileBaru;
}


  function uploadFileKasus()
  {
      $namaFile = $_FILES['file_kasus']['name']; // Nama file asli
      $tmpName = $_FILES['file_kasus']['tmp_name']; // Lokasi file sementara
      $ukuranFile = $_FILES['file_kasus']['size'];
  
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
// // Fungsi dekripsi Caesar Cipher
// function decryptCaesar($data, $shift) {
//   return encryptCaesar($data, 26 - $shift); // Dekripsi dengan menggeser terbalik
// }

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

// // Super dekripsi (AES + Caesar Cipher)
// function superDecrypt($data, $key, $iv, $chiperAlgo, $options, $caesarShift) {
//   // Dekripsi pertama dengan Caesar Cipher
//   $decryptedCaesar = decryptCaesar($data, $caesarShift);
  
//   // Dekripsi kedua dengan AES
//   return decryptAES($decryptedCaesar, $key, $iv, $chiperAlgo, $options);
// }

// Contoh implementasi

// Mengambil input form
$kode = $_POST['kode'];
$judul = $_POST['judul'];
$pesan = $_POST['pesan'];
// $id_perwira = $perwira['id']; 
$id_agen = $_POST['id_agen']; 
$fileKasus = uploadFileKasus();
$gambar = uploadGambar($pesan_tersembunyi);
$tanggal_mulai = $_POST['tanggal_mulai'];
$target_selesai = $_POST['target_selesai'];
$status = 'Belum Mulai';
// Enkripsi data
$encryptedKode = superEncrypt($kode, $keyAes, $ivAes, $chiperAlgo, $options, $caesarShift);
$encryptedJudul = superEncrypt($judul, $keyAes, $ivAes, $chiperAlgo, $options, $caesarShift);
$encryptedPesan = superEncrypt($pesan, $keyAes, $ivAes, $chiperAlgo, $options, $caesarShift);
// $encryptedTanggalMulai = superEncrypt($tanggalMulai, $keyAes, $ivAes, $chiperAlgo, $options, $caesarShift);
// $encryptedTargetSelesai = superEncrypt($targetSelesai, $keyAes, $ivAes, $chiperAlgo, $options, $caesarShift);

$query = "INSERT INTO tugas (kode, judul, pesan, file_kasus, gambar, id_perwira, id_agen, tanggal_mulai, tanggal_selesai, status, file_laporan) 
          VALUES ('$encryptedKode', '$encryptedJudul', '$encryptedPesan', '$fileKasus', '$gambar', '$id', '$id_agen', '$tanggal_mulai', '$target_selesai', '$status', '')";


// Eksekusi query
mysqli_query($conn, $query);

header('Location: halamanTugasPerwira.php');
}


?>




<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Tambah Data Agen</title>
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
        <a href="halamanTugasPerwira.php">
        <button type="button" class="btn btn-outline-light content-start"><i class="bi bi-arrow-left"></i> Kembali</button>
        </a>
    </div>
  
    <div class="col-4">

      <h3 class="text-light text-center">Form Tugas</h3>

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
    <label for="kode" class="col-sm-2 col-form-label">Kode Tugas</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="kode" name="kode">
    </div>
  </div>

  <div class="mb-3 row">
    <label for="judul" class="col-sm-2 col-form-label">Judul</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="judul" name="judul">
    </div>
  </div>

  <div class="mb-3 row">
  <label for="pesan" class="col-sm-2 col-form-label">Pesan</label>
  <div class="col-sm-10">
    <textarea 
      class="form-control" 
      id="pesan" 
      name="pesan" 
      rows="5" 
      style="resize: none; height: 150px; width: 100%;">
    </textarea>
  </div>
</div>

  <div class="mb-3 row">
  <label for="nama_perwira" class="col-sm-2 col-form-label">Perwira Penanggung Jawab</label>
  <div class="col-sm-10">
    <input type="text" class="form-control" id="nama_perwira" name="nama_perwira" value="<?= $perwira['nama_alias']
     // openssl_decrypt($perwira['nama_alias'], $chiperAlgo, $keyAes, $options, $ivAes); ?>" readonly>
    <input type="hidden" id="id_perwira" name="id_perwira" value="<?= $perwira['id']?>">
  </div>
</div>

  <div class="mb-3 row">
    <label for="id_agen" class="col-sm-2 col-form-label">Pilih Agen</label>
    <div class="col-sm-10">
      <select class="form-select" name="id_agen" required>
        <option value="" selected disabled>Pilih Agen</option>
        <?php while ($row = mysqli_fetch_assoc($result_agen)) : ?>
          <option value="<?= $row['id'] ?>"><?=  openssl_decrypt($row['nama_alias'], $chiperAlgo, $keyAes, $options, $ivAes); ?></option>
        <?php endwhile; ?>
      </select>
    </div>
  </div>

  <div class="mb-3 row">
  <div class="col-md-6">
    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai">
  </div>
  <div class="col-md-6">
    <label for="target_selesai" class="form-label">Target Selesai</label>
    <input type="date" class="form-control" id="target_selesai" name="target_selesai">
  </div>
</div>


  <div class="mb-3 row">
  <label for="gambar" class=" col-sm-2 form-label">Gambar</label>
  <div class="col-sm-10">
     <input class="form-control" type="file" id="gambar" name="gambar" required> 
    </div>
  </div>

  <div class="mb-3 row">
    <label for="pesan_tersembunyi" class="col-sm-2 col-form-label">Pesan Tersembunyi</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="pesan_tersembunyi" name="pesan_tersembunyi">
    </div>
  </div>
  
  <div class="mb-3 row">
  <label for="file_kasus" class=" col-sm-2 form-label">File Kasus</label>
  <div class="col-sm-10">
     <input class="form-control" type="file" id="file_kasus" name="file_kasus" required> 
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