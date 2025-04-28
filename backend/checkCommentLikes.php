<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../errorLogging.php';
include '../common/connection.php';
include '../common/funzioni.php';

$IdCommento = isset($_GET['IdCommento']) ? $_GET['IdCommento'] : '';
$Utente = $_SESSION['IndirizzoEmail'] ?? '';

error_log('Checking comment likes...');

if ($IdCommento && $Utente) {
    try {
        $sql = "SELECT
            CASE WHEN EXISTS (
                SELECT * FROM CommentLikes WHERE IdCommento = ? AND UtenteLikeC = ?)
            THEN 'true'
            ELSE 'false'
            END AS LikeStatus";
            
        $params = [$IdCommento, $Utente];
        $types = "ss";

        list($result, $data) = getQuery($cid, $sql, $params, $types);
        if (!$result) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to get comment like status']);
            error_log("Failed to get like status for comment: " . $IdCommento);
            exit();
        }

        echo toJson($data);
        exit();
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    error_log("Invalid request: Invalid comment ID or user");
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>