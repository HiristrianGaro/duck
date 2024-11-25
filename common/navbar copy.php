<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 shadow-sm bg-blue">
  <div class="col-md-3 mb-2 mb-md-0">
    <a href="./index.php"><img class="me-3" src="assets/brand/duck.png" alt="" width="30" height="30" style="margin-left: 30px;"></a>
  </div>




  <?php if (!isset($_SESSION["Email"])) { ?>
    <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
    </ul>

    <div class="col-md-3 text-end">
    <a href="index.php?op=loginPage"><button type="button" class="btn btn-primary" style="margin-right: 10px;">Login</button></a>
      <a href="index.php?op=registerPage"><button type="button" class="btn btn-primary btn-login-up" style="margin-right: 30px;">Register</button></a>
    </div>

  <?php } elseif (isset($_GET["op"]) and $_GET["op"] == 'registerPage') { ?>

    <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
    </ul>

    <div class="col-md-3 text-end">
      <a href="index.php?op=loginPage"><button type="button" class="btn btn-primary btn-login-up" style="margin-right: 30px;">Login</button></a>
    </div>


    <?php } elseif (isset($_GET["op"]) and $_GET["op"] == 'loginPage'){ ?>

    <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
    </ul>

    <div class="col-md-3 text-end">
      <a href="index.php?op=registerPage"><button type="button" class="btn btn-primary btn-login-up" style="margin-right: 30px;">Register</button></a>
    </div>


    <?php } else { ?>

    
    <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
      <li><a href="#" class="nav-link text-body"><i class="bi bi-water h4"></i></a></li>
      <li><a href="#" class="nav-link text-body"><i class="bi bi-egg h4"></i></a></li>
      <li><a href="#" class="nav-link text-body"><i class="bi bi-search h4"></i></a></li>
      <li><a href="#" class="nav-link text-body"><i class="bi bi-activity h4"></i></a></li>
      <li><a href="#" class="nav-link text-body"><i class="bi bi-feather h4"></i></a></li>
    </ul>

    <div class="col-md-3 text-end">
      <a href="backend/logout.php"><button type="button" class="btn btn-primary btn-login-up" style="margin-right: 30px;">Logout</button></a>
    </div>



  <?php } ?>

  </div>
</header>
<!-- end navbar -->