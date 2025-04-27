<?php

include "../common/connection.php";
include "../common/funzioni.php";

$Email = $_SESSION['IndirizzoEmail'];

if (checkDB($cid)["status"] != "ko") {
    $stmt = $cid->prepare("SELECT 
    CASE 
        WHEN EXISTS (
        SELECT * FROM UTENTE WHERE IndirizzoEmail = '$Email' AND AdminBool = '1'
        ) 
        THEN 1 
        ELSE 0 
    END AS IsAdmin;");
    if ($stmt) {
        $stmt->execute();
        $res = $stmt->get_result();
        echo toJson($res->fetch_assoc());
        $stmt->close();
    }
}