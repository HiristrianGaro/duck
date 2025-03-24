<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../errorLogging.php';
include '../common/connection.php';
include '../common/funzioni.php';

$IdPost = isset($_GET['IdPost']) ? $_GET['IdPost'] : '';
$querySelect = isset($_GET['term']) ? $_GET['term'] : '';


if ($querySelect && $IdPost) {
    
    try {

        $sql = '';
        $params = [];
        $types = '';

        switch ($querySelect) {
            case 'getComments':
                error_log('Query: getComments for IdPost: ' . $IdPost);
                $sql = "SELECT * FROM commento WHERE IdPost = ?";


                $params = [$IdPost];
                $types = 's';
                break;

            default:
            error_log('Query: Default');
                $data = ['ciaoo'];
                break;
        }
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


