<?php session_start();
// Redirect ke halaman login jika pengguna belum login
if (!isset($_SESSION["username"])) {
  header("Location: php/login.php");
  exit;
}

// Mendapatkan ID tim dari sesi, jika tidak ada, diatur ke null
$id_tim = $_SESSION["id_tim"] ?? null;

// Tentukan apakah pengguna memiliki tim atau tidak
$user_has_team = !is_null($id_tim); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Turnamen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
    <!-- script font awesome kit-->
    <script
      src="https://kit.fontawesome.com/e18581a144.js"
      crossorigin="anonymous"
    ></script>
    <style> p.privasykanan {
    margin-left: auto !important;
  }</style>
  </head>
  <body>
    <!-- header hero -->
    <section id="headhero1">
    <div class="container headhero">
      <div class="row">
        <div class="col-12">
          <div class="header-text py-2">
            <!-- <h1 >Lihat</h1> -->
            <button type="button" class="btn btn-dark mx-4">
              HERE <i class="fa-solid fa-arrow-right"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
    <!-- header hero -->
   <?php include ('./NavigationBar/nav.php') ?>

    <!-- section event -->
    <section id="sec1" class="switchV1">
      <div class="container py-2">
        <div class="row">
          <div class="col-lg-6 align-self-center text-justify">
            <h2>Bracket Pertandingan Yang Ada</h2>
            <p>
                    Lihat tim apa yang akan bertanding selanjutnya
            </p>
            <a href="./php/turnamenBracket.php" class="btn btn-dark mx-4">     
                <button type="button" class="btn btn-dark mx-4">
              Disini <i class="fa-solid fa-arrow-right"></i>
            </button></a>
          </div>

          <div class="col-lg-6">
            <div class="px-4 py-5 align-self-center">
              <img
                src="https://i.pinimg.com/474x/25/56/63/255663d75c00f9872a19b98dfca0641c.jpg"
                alt=""
                class="py-3 w-100"
                style="border-radius: 50px"
              />
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- section event end -->
\
    <section id="sec2" class="switchV2">
      <div class="container py-5">
        <div class="row">
          <div class="col-lg-6">
            <div class="px-4 py-5 align-self-center">
              <img
                src="https://i.pinimg.com/474x/b7/b2/02/b7b202fe927501a02291279b26b59dea.jpg"
                alt=""
                style="border-radius: 50px"
                class="py-3 w-50"
              />
            </div>
          </div>
          <div class="col-lg-6 align-self-center text-justify">
            <h2>Konten Kami</h2>
            <p>
             Scene epik turnamen konten lain tersedia 
            </p>
            <a href="./php/content.php" class="btn btn-dark mx-4">     
                <button type="button" class="btn btn-dark mx-4">
              Disini <i class="fa-solid fa-arrow-right"></i>
            </button></a>
       
          </div>
        </div>
      </div>
    </section>
    <!-- section whats's hot -->
  <?php include ('./php/footer.php') ?> 
    

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
    crossorigin="anonymous"
  ></script>
  <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
</html>
