<?php    
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    if(isset($_SESSION['Status'])) { include '../common/postContent.php' ?>
<div class="container">
<div class="row d-flex justify-content-center">	
    <div class="col-lg-6" id="LetTheEggsSwim">

    </div>
</div>


<?php
    } else {
        include("../common/UnauthorizedAllert.html");
    }
?>

<script src="js/pondFunctionality.js"></script>