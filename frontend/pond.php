<?php    
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    if(isset($_SESSION['Status'])) { include '../common/postContent.php' ?>




<div class="row d-flex justify-content-center">	
    <div class="col-lg leftsplit">

    </div>
    <div class="col-8 col-lg centralsplit mx-auto" id="LetTheEggsSwim"></div>
    <div class="col-lg rightsplit">

    </div>
    
</div>
</div>



<?php
    } else {
        include("../common/UnauthorizedAllert.html");
    }
?>

<script src="js/pondFunctionality.js"></script>