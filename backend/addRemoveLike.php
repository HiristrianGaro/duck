<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../errorLogging.php';
include '../common/funzioni.php';
include '../common/connection.php';

$querySelect = $_GET['action'] ?? '';
$Username = $_SESSION['IndirizzoEmail'] ?? '';
$PostId = $_GET['IdPost'] ?? '';

error_log("querySelect: $querySelect");
error_log("Username: $Username");
error_log("PostId: $PostId");


if ($querySelect) {
    $sql = '';
    $params = [];
    $types = '';

    switch ($querySelect) {
        
        case 'AddLike':
        $sql = "INSERT INTO PostLikes (IdPost, UtenteLikeP) VALUES (?, ?);";
        $types = 'ss';
        $params = [$PostId, $Username];
        break;

        case 'RemoveLike':
        $sql = "
            DELETE FROM PostLikes
            WHERE IdPost = ? AND UtenteLikeP = ?;
        ";
        $types = 'ss';
        $params = [$PostId, $Username];
        break;

        default:
        error_log("Invalid action: $querySelect");
        echo json_encode(['status' => 'error', 'message' => "Invalid action: $querySelect"]);
        exit;
    }

} else {
    // No valid action or missing Richiedente
    error_log("Invalid request: Action or session Username missing");
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

list($result, $data) = getQuery($cid, $sql, $params, $types);
echo json_encode(['status' => 'success', 'message' => 'Successfully added or removed like!']);
// Close the connection
$cid->close();
?>
