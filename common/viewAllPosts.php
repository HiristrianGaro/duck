<?php    
    
    if (!empty($Posts)) {
        foreach ($Posts as $Post){

                include("../common/postContent.php");
            }
    }else {
        echo '<div class="text-center text-secondary p-3" id="no-messaggi-msg" >
            <h4>Nessun messaggio da visualizzare...</h4>
        </div>';
    }
?>