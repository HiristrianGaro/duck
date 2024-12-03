<?php
session_start();
include "config.php";
include "common/connection.php";
include "common/funzioni.php";
include "backend/login.php";
include "backend/register.php";
if (isset($_SESSION['Status'])) {
  error_log('User is logged in');
} else {
  error_log('User is NOT logged in');
}
?>


<!DOCTYPE html>
<html lang="en">
<?php include "common/header.php"; ?>

<body>

  <main class="main" style="height: 100% !important;">


    <?php include "common/navbar.php"; ?>

      <div class="container" id="main-page">
        <div class="collapse" id="collapseExample">
          <div class="d-flex justify-content-center px-lg-3">
            <div class="p-3 p-md-4 border rounded-3 bg-white">
              <form class="form-inline">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" id="searchInput">
              </form>
            </div>
          </div>
        </div>
      </div>
        <?php if(!isset($_SESSION['Status'])) {include 'frontend/landingPage.html';}?>
        
      </div>
    
  </main>
  <?php include "common/footer.php"; ?>
  <?php include("common/modalErrore.php")?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

  <?php include("common/errorModal.php")?>
</body>

</html>