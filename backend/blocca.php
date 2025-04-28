<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
include "../common/connection.php";
include "../common/funzioni.php";
include "./checkAdmin.php";

$Email = $_SESSION['IndirizzoEmail'];
error_log($_SESSION['IndirizzoEmail']);
error_log("Checking if user is admin..." . $Email);

if (checkDB($cid)["status"] != "ko") {
    $stmt = $cid->prepare("SELECT CASE WHEN EXISTS (
        SELECT * FROM UTENTE WHERE IndirizzoEmail = '$Email' AND AdminBool = '1') 
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