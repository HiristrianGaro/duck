<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 shadow-sm bg-blue">
  <div class="col-md-3 mb-2 mb-md-0">
    <a href="<?= SITE_HOME ?>"><img class="mx-md-5" src="<?= CONTENT_DIR ?>/duck.png" alt="" width="30" height="30"></a>
  </div>


  <?php if (isset($_SESSION["Status"])) { ?>
    <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
      <li><a href="#" class="nav-load nav-link text-body" data-target="#"><i class="bi bi-water h4"></i></a></li>
      <li><a href="#" class="nav-load nav-link text-body" data-target="#"><i class="bi bi-egg h4"></i></a></li>
      <li><a href="#" class="nav-load nav-link text-body" data-target="#"><i class="bi bi-search h4"></i></a></li>
      <li><a href="#" class="nav-load nav-link text-body" data-target="frontend/friends.php"><i class="bi bi-activity h4"></i></a></li>
      <li><a href="#" class="nav-load nav-link text-body" data-target="frontend/profilePage.php"><i class="bi bi-feather h4"></i></a></li>
    </ul>

    <div class="col-md-3 text-end">
      <a href="<?= SITE_DOMAIN?>/backend/logout.php"><button type="button" class="btn btn-primary btn-round mr-md-1">Logout</button></a>
    </div>

  <?php } else { ?>

      <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
    </ul>

    <div class="col-md-3 ml-1 text-end">
      <a href="#" data-target="frontend/loginPage.php" class="nav-load" id="login-button"><button type="button" class="btn btn-primary btn-round mr-md-1">Login</button></a>
      <a href="#" data-target="frontend/registerPage.php" class="nav-load" id="register-button"><button type="button" class="btn btn-primary btn-round mr-md-1">Register</button></a>
    </div>


    <?php } ?>

  </div>
</header>


<!-- end navbar -->