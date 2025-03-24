<?php
include '../errorLogging.php';
include '../common/connection.php';

//Script ancora da far funzionare. Vogliamo capire come interfacciarci con l'API per prelevare le informazioni necessarie
//per la ricerca dei nomi delle citta (non codici)

function checkCity($Country, $State, $City) {
    global $cid;
    error_log($Country . $State . $City);
    $sql = "SELECT COUNT(*) count FROM Location 
            WHERE Regione = ? AND Provincia =  ? AND Citta = ?";

    $stmt = $cid->prepare($sql);

    $stmt->bind_param('sss', $Country, $State, $City);

    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        return false;
    }

    $result = $stmt->get_result();


    if (!$result->num_rows > 0) {
        $sql = "INSERT INTO LOCATION (Regione, Provincia, Citta) VALUES (?, ?, ?)";

        $stmt = $cid->prepare($sql);

        $stmt->bind_param('sss', $Country, $State, $City);


        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }
    }
    
    return true;
}


?>

