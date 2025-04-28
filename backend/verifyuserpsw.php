<?php

include '../common/connection.php';
include '../common/funzioni.php';
include '../errorLogging.php';
if(session_status() !== PHP_SESSION_ACTIVE) session_start();

$IndirizzoEmail = isset($_SESSION['IndirizzoEmail']) ? $_SESSION['IndirizzoEmail'] : '';


function verifyUserPsw($cid, $IndirizzoEmail, $Password) {
    $stmt = $cid->prepare("SELECT Password FROM utente WHERE IndirizzoEmail = '$IndirizzoEmail'");
    if ($stmt) {
        $findResults = mysqli_query($cid, $stmt);
        if (!$findResults) {
            error_log("Error executing query: " . mysqli_error($cid));
            return false;
        }
        $row = mysqli_fetch_assoc($findResults);
        $hashed = $row["Password"];

        if (password_verify($Password, $hashed)) {
            return true;
        } else {
            return false;
        }
    } else {
        error_log("Error preparing statement: " . mysqli_error($cid));
        return false;
    }
}