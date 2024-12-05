<?php    
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    if(isset($_SESSION['Status'])) {
        if (!empty($Posts)) {
            foreach ($Posts as $Post){

                    include("../common/postContent.php");
                }
        }else {
            echo '<div class="text-center text-secondary p-3" id="no-messaggi-msg" >
                <h4>Nessun messaggio da visualizzare...</h4>
            </div>';
        }
    } else {
        include("../common/UnauthorizedAllert.html");
    }
?>