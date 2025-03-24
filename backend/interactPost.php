<?php
include '../errorLogging.php';

$type = isset($_GET['type']) ? $_GET['type'] : '';
$IdPost = isset($_GET['IdPost']) ? $_GET['IdPost'] : '';
$IndirizzoEmail = $_SESSION['IndirizzoEmail'];

//Non ammiamo ancora implementato il frontend per la parte like/dislike.
//Verra aggiunta anche per i commenti

if (!$IdPost) {
    error_log("Session parameter 'IndirizzoEmail' is missing");
    echo json_encode(['status' => 'error', 'message' => 'Invalid session data']);
    exit;
}

if ($querySelect && $Richiedente) {
    try {
        $sql = '';
        $params = [];
        $types = '';

        switch ($querySelect) {
            case 'Add':
                $sql = "INSERT INTO Likes (IdPost, IndirizzoEmail)
                VALUES (?, ?)";

                $params = [$IdPost, $IndirizzoEmail];
                $types = 'ss';
            case 'Remove':
                $sql = "DELETE FROM Likes
                        WHERE IdPost = ? AND IndirizzoEmail = ?;";

                $params = [$IdPost, $IndirizzoEmail];
                $types = 'ss';


            default:
            throw new Exception("Invalid action: $querySelect");
        }

    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    
} else {
        error_log("Invalid request: Action or session email missing");
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
 

$stmt = $cid->prepare($sql);

$stmt->bind_params('ss', $IdPost, $IndirizzoEmail);

if (!$stmt->execute()) {
    throw new Exception("Failed to execute SQL statement: " . $stmt->error);
}

$stmt->close();