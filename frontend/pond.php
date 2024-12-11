<?php    
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    if(isset($_SESSION['Status'])) {
?>
<div class="container" id="LetTheEggsSwim">

</div>

<?php
    } else {
        include("../common/UnauthorizedAllert.html");
    }
?>