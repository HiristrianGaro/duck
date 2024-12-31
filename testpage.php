<?php
  session_start();
  require_once "config.php";
  include "common/connection.php";
  include "common/funzioni.php";
?>


<!DOCTYPE html>
<html lang="en">
<?php include "frontend/header.html"; ?>

<body>
  <main class="main content">


    <?php include "frontend/navbar.php"; ?>
    <?php include "frontend/items/searchCollapse.html"; ?>

    <div class="container-fluid container-z-index mt-2" id="main-page">
        <?php include './frontend/edit-profile.html'?>
    </div>
    
  </main>
  <?php include "frontend/footer.php"; ?>
</body>

</html>