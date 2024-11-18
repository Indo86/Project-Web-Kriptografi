<?php 
session_start();
include("connect.php");


// login Agen
if(isset($_POST["loginAgen"])){

  $id = hash('sha256', $_POST["id"]);
  $password = hash('sha256',$_POST['password'] );
  
  $queri = "SELECT * FROM agen WHERE id = '$id' AND password = '$password'";
  $result = mysqli_query($conn, $queri);

 
  if(mysqli_num_rows($result) > 0){

    $_SESSION["loginAgen"] = true;
    $_SESSION["id"] = $id;
  
    header("Location: Agen/halamanDashboardAgen.php");
    exit;
  }
  // echo $id;
  // echo $password;
  // echo $queri;
  // echo mysqli_num_rows($result);
  $error = true;
}


?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  </head>
  <style>
     .divider:after,
    .divider:before {
      content: "";
      flex: 1;
      height: 1px;
      background: #eee;
      }
      .h-custom {
      height: calc(100% - 73px);
      }
      @media (max-width: 450px) {
      .h-custom {
      height: 100%;
      }
      }

      .bg-foto{
        background-image: url(img/sawah.jpg);
        background-size: cover;
      }

      body {

        overflow-y: hidden; 
        }

   @keyframes animasiColor {
      0%{
        background-position:  left;
      }
      100%{
        background-position:  right;
      }
    }

    button.login{
      cursor: pointer;
      background-image: linear-gradient(to left, black 10%, blue 100%);
      animation: animasiColor 2s infinite alternate;
      box-shadow: rgba(2, 8, 20, 0.1) 0px 0.35em 1.175em, rgba(2, 8, 20, 0.08) 0px 0.175em 0.5em;
    }

      body{
        height: 100vh;
        background-size: 200%;
        background-color: black;
        background-image: linear-gradient(160deg, black 0%, blue 100%);  
        background-repeat: no-repeat;
        animation: animasiColor 2s infinite alternate;
        transition: all ease;
        overflow-y: hidden; 
      }

    </style>
  <body>
  <section class="vh-100 bg-foto">
    
  <form action="" method="post">
  <div class="container py-5 h-100 ">
    <div class="row d-flex justify-content-center align-items-center h-100 ">
      <div class="col-lg-6 mb-5 mb-lg-0">
          <div class="d-flex justify-content-center">
          <img src="Assets/img/james-bond-007.png" alt="" class="img-fluid" height="200px" width="200px">
          </div>
          <h1 class="my-4 display-3 fw-bold text-light text-center">
            Asosiasi Intelijen <br />
            Negara Barat Daya
          </h1>
        </div>

      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class=" card shadow-2-strong" style="border-radius: 1rem;">
          <div class="card-header">
            <ul class="nav nav-pills card-header-pills">
              <li class="nav-item">
                <a class="nav-link active" href="#">Agen</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="loginPerwira.php">Perwira</a>
              </li>
            </ul>
          </div>
          <div class="card-body p-5">
            <h3 class="mb-4 text-center">Masuk</h3>
              <?php  if(isset($error)){ ?>
                <p style="color: red; font-style: itaitalic ; "> ID / Password salah!! </p>
                <?php } ?>

            <div class="form-outline mb-4">
            <label class="form-label" for="id">ID</label>
              <input type="text" id="id" name="id" class="form-control form-control-lg" />
            </div>

            <div class="form-outline mb-5">
              <label class="form-label" for="password">Password</label>
              <input type="password" id="password" name="password" class="form-control form-control-lg" />
            </div>

            <!-- Checkbox -->
            <div class="text-center">
            <button class="btn login btn-primary btn-lg btn-block d-grip col-12 mx-auto text-center-round" name="loginAgen">Login</button>
            
            <hr class="my-4">
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
 </form>
</section>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>