<div class="navbar-sticky d-flex flex-wrap align-items-center justify-content-center justify-content-md-between shadow-lg py-3 shadow-sm bg-secondary mb-3">
  <div class="col-md-3 mb-2 mb-md-0">
    <ul class="nav col col-md-auto mb-2 mb-md-0">
      <li><a href="<?= SITE_HOME ?>"><img class="duckNavHide ml-md-2" src="<?= CONTENT_DIR ?>/duck.png" alt="" width="30" height="30"></a></li>
      <li><p class="usernameNav text-yellow fw-bold col ml-md-1 mb-0 "><?php if (isset($_SESSION["Username"])) { echo  $_SESSION["Username"];}?></p></li>
    </ul>
  </div>


  <?php if (isset($_SESSION["Status"])) { ?>
    <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
      <li><a href="#" class="nav-load nav-link text-body" data-target="frontend/pond.php"><i class="bi bi-water white h4"></i></a></li>
      <li><a href="#" data-target="frontend/addPost.html" class="nav-load"><button type="button" class="btn btn-primary btn-round"><i class="bi bi-plus-square h4"></i></button></a></li>
      <li><a href="#" class="nav-link text-body" data-bs-toggle="collapse" data-bs-target="#SeachCollapse" aria-expanded="false" aria-controls="SeachCollapse" data-target="#"><i class="bi bi-search white h4"></i></a></li>
      <li><a href="#" class="nav-load nav-link text-body" data-target="frontend/friends.php"><i class="bi bi-activity white h4"></i></a></li>
      <li><a href="#" class="nav-load nav-link text-body" data-target="frontend/profilepage.php" data-username="<?php echo  $_SESSION["Username"];?>" ><i class="bi bi-feather white h4"></i></a></li>

    </ul>

    <div class="col-sm-3 text-sm-end mr-sm-3">
      <a href="<?= SITE_DOMAIN?>/backend/logout.php"><button type="button" class="btn btn-primary btn-round ">Logout</button></a>
    </div>

  <?php } else { ?>

      <ul class="nav col col-md-auto mb-2 justify-content-center mb-md-0">
    </ul>

    <div class="col-md-3 ml-1 text-end">
      <a href="#" data-target="frontend/loginPage.php" class="nav-load" id="login-button"><button type="button" class="btn btn-primary btn-round mr-md-1">Login</button></a>
      <a href="#" data-target="frontend/registerPage.php" class="nav-load" id="register-button"><button type="button" class="btn btn-primary btn-round mr-md-1">Register</button></a>
    </div>


    <?php } ?>

  </div>
  

  </div>
</div>



<!-- end navbar -->