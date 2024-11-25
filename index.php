<?php
session_start();
include "common/connection.php";
include "common/funzioni.php";
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<?php include "common/header.php"; ?>
<?php include "common/errorModal.php"; ?>

<body>
  <main class="main" style="height: 100% !important;">

    <?php include "common/navbar.php"; ?>

    <div class="container">
      <div class="well">


        <?php
        if (isset($_SESSION["Email"]) && $_SESSION["Status"] == 'ok') {
          echo "<div class=\"alert alert-success\"><strong>Benvenuto " . $_POST["Username"] . "</strong></div>";
        } else {
          if (isset($_GET["op"])) {
            if ($_GET["op"] == 'loginPage') {
              include "common/loginPage.php";
            } elseif ($_GET["op"] == 'registerPage') {
              include "common/registerPage.php";
                }
            } else {
                echo "
            <section class='py-5 text-center container'>
                <div class='row py-lg-5'>
                <div class='col-lg-6 col-md-8 mx-auto'>
                    <h1 class='fw-light'>Benvenuto</h1>
                    <p class='lead'>Per accedere ai contenuti di Duck esegui il login.</p>
                </div>
            </div>
            </section> 
            ";
            }
         }
        
        ?>
      </div>
      <div class="container">
        <?php

        if (isset($_GET["Status"])) {
          if ($_GET["Status"] == 'ok')
            echo "<div class=\"alert alert-success\"><strong>" . urldecode($_GET["msg"]) . "</strong></div>";
          elseif ($_GET["Status"] != 'ok'){
            echo "<div class=\"position-relative\"><div class=\"position-absolute top-0 start-50 translate-middle-x\">
            <div class=\"alert alert-danger\"><strong>Errore! </strong>" . urldecode($_GET["msg"]) . "</div>
            </div></div>";
          }
        }


        ?>
      </div> <!-- /container -->


    </div> <!-- /container -->






  </main>
  <?php include "common/footer.php"; ?>


</body>

</html>