<?php

$hostname = 'localhost';
$username = 'root';
$password = '';
$db = 'duck';

try {
    $cid = new mysqli($hostname,$username,$password,$db);
} catch (Exception $e) {
    $cid=null;
}

// questa è una primitiva di php.ini che stabilisce di riportare
// gli errori con l'interazione con il db senza creare la morte
// dell'applicazione. In questo modo riesco a dare adeguato messaggio
// di errore all'utente.
mysqli_report(MYSQLI_REPORT_ERROR);

?>
