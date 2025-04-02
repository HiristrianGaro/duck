<?php

include '../errorLogging.php';
include '../common/connection.php';
include '../common/funzioni.php';
if(session_status() !== PHP_SESSION_ACTIVE) session_start();

$IdPost = isset($_GET['IdPost']) ? $_GET['IdPost'] : '';
$Utente = $_SESSION['IndirizzoEmail'] ?? '';

error_log('checking likes...');


if ($IdPost) {
    
    try {

        $sql = "SELECT
            CASE WHEN EXISTS (
            SELECT * FROM PostLikes WHERE IdPost = ? AND UtenteLikeP = ?)
            THEN 'true'
            ELSE 'false'
            END AS LikeStatus";


        $params = [$IdPost, $Utente];
        $types = "ss";


    } catch (Exception $e) {

        error_log("Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

} else {
    error_log("Invalid request: Action or session email missing");
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}


list($result, $data) = getQuery($cid, $sql, $params, $types);
if (!$result) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to get Comments']);
    error_log("Failed to get comments for post: " . $IdPost);
    exit();
}

echo toJson($data);
exit();
?>


