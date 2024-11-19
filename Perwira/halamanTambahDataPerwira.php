<?php 
session_start();
include("../connect.php");
$id = $_SESSION['id'];

if(!isset($_SESSION["loginPerwira"])){
  header("Location: ../loginPerwira.php");
  exit;
}


if(isset($_POST["submit"])){
function upload(){
  $namaFile = $_FILES['gambar']['name'];
  $ukuranFile = $_FILES['gambar']['size'];
  $error = $_FILES['gambar']['error'];
  $tmpName = $_FILES['gambar']['tmp_name'];

  // mengecek apakah yang diupload itu adalah gambar
  $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
  $ekstensiGambar = explode('.', $namaFile);
  $ekstensiGambar = strtolower(end($ekstensiGambar));
   
 //cek ekstensi gambar
  if(!in_array($ekstensiGambar, $ekstensiGambarValid)){

    echo "
    <script>
      alert('File yang anda upload bukan gambar!');
    </script>
    ";
    return false;
  }

  // cek ukuran file
  if($ukuranFile > 1000000){
    echo "
    <script>
      alert('Ukuran file terlalu besar!');
    </script>
    ";

    return false;
  }

  $namaFileBaru = uniqid();
  $namaFileBaru .='.';
  $namaFileBaru .= $ekstensiGambar;

  move_uploaded_file($tmpName, '../Assets/img/'.$namaFileBaru);

  return $namaFileBaru;
}
$keyAes = 'makanmakanmakanp';
$ivAes = '12345678abcdefgh';;
$chiperAlgo= 'AES-128-CBC';
$options = 0;
   
      $id = hash('sha256', $_POST['id']); // hash
      $nama_asli = openssl_encrypt($_POST['nama_asli'], $chiperAlgo, $keyAes, $options, $ivAes);
      $nama_alias = openssl_encrypt($_POST['nama_alias'], $chiperAlgo, $keyAes, $options, $ivAes);
      $jenis_kelamin = openssl_encrypt($_POST['jenis_kelamin'], $chiperAlgo, $keyAes, $options, $ivAes);
      $jabatan = openssl_encrypt($_POST['jabatan'], $chiperAlgo, $keyAes, $options, $ivAes);;
      $penempatan_tugas = openssl_encrypt($_POST['penempatan'], $chiperAlgo, $keyAes, $options, $ivAes);
      $unik = hash('sha256', $_POST['unik']); // hash
      $password = hash('sha256', $_POST['password']); // hash
      $gambar = openssl_encrypt(upload(),$chiperAlgo, $keyAes, $options, $ivAes );
      $id2 = openssl_encrypt($_POST['id'], $chiperAlgo, $keyAes, $options, $ivAes );


      $query = "INSERT INTO perwira VALUES
       ('$id',' $id2','$nama_asli', '$nama_alias',' $jenis_kelamin',
       '$jabatan','$penempatan_tugas','$unik','$gambar', '$password')";


mysqli_query($conn, $query);

header('Location: halamanDataPerwira.php');
}


?>




<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Tambah Data Perwira</title>
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
        <a href="halamanDataAgen.php">
        <button type="button" class="btn btn-outline-light content-start"><i class="bi bi-arrow-left"></i> Kembali</button>
        </a>
    </div>
  
    <div class="col-4">

      <h3 class="text-light text-center">Form Rekrut Perwira</h3>

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
    <label for="id" class="col-sm-2 col-form-label">ID</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="id" name="id">
    </div>
  </div>

  <div class="mb-3 row">
    <label for="nama_asli" class="col-sm-2 col-form-label">Nama Asli</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="nama_asli" name="nama_asli">
    </div>
  </div>

  <div class="mb-3 row">
    <label for="nama_alias" class="col-sm-2 col-form-label">Nama Alias</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="nama_alias" name="nama_alias">
    </div>
  </div>

  <div class="mb-3 row">
    <label for="jenis_kelamin" class="col-sm-2 col-form-label">Jenis Kelamin</label>
    <div class="col-sm-10">
    <select class="form-select" aria-label="Default select example" name="jenis_kelamin">
          <option selected>Pilih Jenis Kelamin</option>
          <option value="Laki-laki">Laki-laki</option>
          <option value="Perempuan">Perempuan</option>
      </select>
    </div>
  </div>

  <div class="mb-3 row">
    <label for="jabatan" class="col-sm-2 col-form-label">Jabatan</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="jabatan" name="jabatan">
    </div>
  </div>

  <div class="mb-3 row">
    <label for="penempatan" class="col-sm-2 col-form-label">Penempatan Tugas</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="penempatan" name="penempatan">
    </div>
  </div>


  <div class="mb-3 row">
    <label for="unik" class="col-sm-2 col-form-label">Unik</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="unik" name="unik">
    </div>
  </div>
  

  <div class="mb-3 row">
    <label for="password" class="col-sm-2 col-form-label">Password </label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="password" name="password">
    </div>
  </div>

  <div class="mb-3 row">
  <label for="gambar" class=" col-sm-2 form-label">Foto</label>
  <div class="col-sm-10">
     <input class="form-control" type="file" id="gambar" name="gambar" required> 
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