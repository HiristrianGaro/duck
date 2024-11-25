<?php
session_start();
include "common/connection.php";
include "common/funzioni.php";
?>


<!DOCTYPE html>
<html lang="en">
<?php include "common/header.php"; ?>

<body>

  <main class="main" style="height: 100% !important;">


    <?php include "common/navbar.php"; ?>

    <div class="container">





      <!-- Main component for a primary marketing message or call to action -->
      <!-- include "backend/modal.php"; -->
      <!-- include "backend/" . $_GET["op"] . ".php"; -->
      <div class="well">


        <?php
        if (isset($_SESSION["Email"])) {

          if (isset($_GET["op"])) {
            include "common/" . $_GET["op"] . ".php";
          }
        } else {
          if (isset($_GET["op"])) {
            include "common/" . $_GET["op"] . ".php";
          } elseif (!isset($_GET["status"])) {
            echo "
          <section class='py-5 text-center container'>
            <div class='row py-lg-5'>
              <div class='col-lg-6 col-md-8 mx-auto'>
                <h1 class='fw-light'>Benvenuto</h1>
                <p class='lead text-body-secondary'>Per accedere ai contenuti di Duck esegui il login.</p>
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

        if (isset($_GET["status"])) {
          if ($_GET["status"] == 'ok')
            echo "<div class=\"alert alert-success\"><strong>" . urldecode($_GET["msg"]) . "</strong></div>";
          elseif ($_GET["status"] != 'ok'){
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
  <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/c-s-c.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  
 
  
</body>

</html>