<?php
  session_start();
  require_once "config.php";
  include "common/connection.php";
  include "common/funzioni.php";
?>


<!DOCTYPE html>
<html lang="en">
<?php include "frontend/header.php"; ?>

<body>
  <main class="main content">


    <?php include "frontend/navbar.php"; ?>
    <?php include "frontend/items/searchCollapse.html"; ?>

    <div class="container-fluid container-z-index" id="main-page">
      <?php if(!isset($_SESSION['Status'])) {include 'frontend/items/landingPage.html';}?>
        
    </div>
    
  </main>
  <?php include "frontend/footer.php"; ?>
</body>

</html>