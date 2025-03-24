<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../errorLogging.php';
include '../common/connection.php';
$searchTerm = isset($_GET['term']) ? $_GET['term'] : '';

//Per la ricerca tramite la searchbar

    if ($searchTerm !== '') {
        $sql = "SELECT username, IndirizzoEmail, Nome, Cognome, PosizioneFileSystemFotoProf
                FROM utente 
                WHERE username LIKE ? 
                OR IndirizzoEmail LIKE ? 
                OR Nome LIKE ? 
                OR Cognome LIKE ?";

        $stmt = $cid->prepare($sql);

        $searchParam = '%' . $searchTerm . '%';

        $stmt->bind_param('ssss', $searchParam, $searchParam, $searchParam, $searchParam);

        $stmt->execute();

        $result = $stmt->get_result();

        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($data);

        $stmt->close();
    } else {
        echo json_encode([]);
    }

$cid->close();
?>
