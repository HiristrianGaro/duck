<?php
  session_start();
  include "config.php";
  include "common/connection.php";
  include "common/funzioni.php";
  include "backend/login.php";
  include "backend/register.php";
?>


<!DOCTYPE html>
<html lang="en">
<?php include "common/header.php"; ?>

<body>

  <main class="main" style="height: 100% !important;">


    <?php include "common/navbar.php"; ?>
    <?php include "common/searchCollapse.php"; ?>
    <div class="container" id="main-page">

      
      <?php if(!isset($_SESSION['Status'])) {include 'frontend/landingPage.html';}?>
        
    </div>
    
  </main>
  <?php include "common/footer.php"; ?>
  <?php include("common/modalErrore.php")?>
  <?php include("common/errorModal.php")?>
</body>

</html>