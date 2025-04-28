<?php    
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    if(isset($_SESSION['Status'])) {?>




<div class="row d-flex justify-content-center">	
    <div class="col-lg leftsplit">

    </div>
    <div class="col-8 col-lg centralsplit mx-auto" id="LetTheEggsSwim"></div>
    <div class="col-lg rightsplit" id="LeftContainer">

    </div>
    
</div>
</div>



<?php
    } else {
        include("../frontend/items/UnauthorizedAllert.html");
    }
?>


