<?php
// Avvia la sessione
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once("../common/connection.php");
$emailUtente = $_SESSION['Username'];

// Controlla la connessione
if ($cid->connect_error) {
    die("Connessione fallita: " . $cid->connect_error);
}
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);