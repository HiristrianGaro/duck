<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
header('Content-Type: application/json');
include '../errorLogging.php';
include '../common/connection.php';
include '../common/funzioni.php';

// Verifica che l'utente sia loggato
if (!isset($_SESSION['IndirizzoEmail'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utente non autenticato']);
    exit();
}

// Ottieni i dati dalla richiesta JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['IdPost']) || !isset($data['commentText'])) {
    echo json_encode(['status' => 'error', 'message' => 'Dati mancanti']);
    exit();
}

$IdPost = $data['IdPost'];
$commentText = trim($data['commentText']);
$userEmail = $_SESSION['IndirizzoEmail'];

if (empty($commentText)) {
    echo json_encode(['status' => 'error', 'message' => 'Il commento non può essere vuoto']);
    exit();
}

try {
    // Genera un ID univoco per il commento (opzionale, se necessario)
    $idCommento = uniqid('CMT_');
    
    // SQL per l'inserimento
    $sql = "INSERT INTO COMMENTO (IdCommento, IdPost, UtenteCommento, Testo, TimestampCommento) 
            VALUES (?, ?, ?, ?, NOW())";
    $params = [$idCommento, $IdPost, $userEmail, $commentText];
    $types = "ssss"; // Tutti i parametri sono stringhe
    
    $stmt = $cid->prepare($sql);
    if (!$stmt) {
        throw new Exception("Errore di preparazione SQL: " . $cid->error);
    }
    
    $stmt->bind_param($types, ...$params);
    
    $result = $stmt->execute();
    
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Commento aggiunto con successo']);
    } else {
        error_log("Errore SQL: " . $stmt->error);
        echo json_encode(['status' => 'error', 'message' => 'Errore nell\'aggiunta del commento: ' . $stmt->error]);
    }
} catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>