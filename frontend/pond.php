<?php    
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    if(isset($_SESSION['Status'])) { include '../common/postContent.php' ?>
<div class="container" id="LetTheEggsSwim">

</div>

<?php
    } else {
        include("../common/UnauthorizedAllert.html");
    }
?>

<script src="js/pondFunctionality.js"></script>