<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../errorLogging.php';
include '../config.php';
include 'imagecrop.php';
include '../common/connection.php';
include 'addCity.php';

$files_to_upload = $_FILES['filesToUpload'];

if (empty($files_to_upload['name'][0])) {
    error_log("No files uploaded");
    echo json_encode([
        'status' => 'error',
        'message' => 'No files uploaded!',
    ]);
    exit();
}
error_log(POST_DIR);

    global $cid; // Ensure $cid is accessible

    error_log('Creating Post');
    $Utente = $_SESSION['IndirizzoEmail'];
    $Description = isset($_POST['Description']) ? $_POST['Description'] : '';
    $timestamp = date('Y-m-d H:i:s');
    $city = isset($_POST['city']) ? $_POST['city'] : '';
    $region = isset($_POST['region']) ? $_POST['region'] : '';
    $province = isset($_POST['province']) ? $_POST['province'] : '';
    error_log(print_r($_POST, true));


    

    $sql = "INSERT INTO post (IdPost, Utente, TimestampPubblicazione, Testo, PostCitta, PostProvincia, PostRegione)
    VALUES (UUID(), ?, ?, ?, ?, ?, ?);";
    $stmt = $cid->prepare($sql);

    if (!$stmt) {
        error_log("Prepare failed: " . $cid->error);
        die('Database error');
    }

    $stmt->bind_param('ssssss', $Utente, $timestamp, $Description, $city, $province, $region);

    error_log('Executing Query');
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        die('Failed to execute query');
    }

    $stmt->close();
    error_log('Post Created');
    error_log('Adding Foto to Post');
    addFotoPost($Utente, $timestamp);


//Verrà utilizzata per ottenere l'Id del post appena creato, sostituendo TimestampPubblicazione e Utente
//quando cambieremo la struttura di foto rimuovendo questi ultimi

function getPostId($Utente, $timestamp) {
    global $cid;

    $sql = "SELECT IdPost FROM Post WHERE Utente = ? AND TimestampPubblicazione = ?";
    $stmt = $cid->prepare($sql);

    if (!$stmt) {
        error_log("Prepare failed: " . $cid->error);
        return false;
    }

    $stmt->bind_param('ss', $Utente, $timestamp);

    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        return false;
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        error_log($row['IdPost']);
        return $row['IdPost'];
    }

    return false;
}

function addFotoPost($Utente, $timestamp) {
    $IdPost = getPostId($Utente, $timestamp);
    global $cid;

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_FILES['filesToUpload'])) {
            $files_to_upload = $_FILES['filesToUpload'];

            $errorArray = [];
            error_log('Images Number: ' . count($files_to_upload['name']));
            for ($i = 0; $i < count($files_to_upload['name']); $i++) {
                error_log('Loop Number: ' . $i);
                $originalName = basename($files_to_upload['name'][$i]);
                $sanitizedFileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
                $DBdestination = LOCAL_POST_DIR . '/' . $sanitizedFileName;
                $destination = POST_DIR . '/' . $sanitizedFileName;

                if (is_uploaded_file($files_to_upload['tmp_name'][$i])) {
                    $mime_type = mime_content_type($files_to_upload['tmp_name'][$i]);
                    $allowed_file_types = ['image/jpeg', 'image/gif', 'image/png'];

                    if (in_array($mime_type, $allowed_file_types)) {
                        cropImageToAspectRatioGD($files_to_upload['tmp_name'][$i], $destination, 5, 4);

                        $sql = "INSERT INTO Foto (IdPost, PosizioneFileSystem, Ordine) VALUES (?, ?, ?)";
                        $stmt = $cid->prepare($sql);

                        if (!$stmt) {
                            error_log("Prepare failed: " . $cid->error);
                            continue;
                        }

                        $stmt->bind_param('ssi', $IdPost, $DBdestination, $i);

                        if (!$stmt->execute()) {
                            error_log("Execute failed: " . $stmt->error);
                            $response = [
                                'name' => $originalName,
                                'status' => 'error',
                                'message' => 'Database insertion failed',
                            ];
                            array_push($errorArray, $response);
                        }

                        $stmt->close();
                    } else {
                        $response = [
                            'name' => $originalName,
                            'status' => 'error',
                            'message' => 'File type not allowed!',
                        ];
                        error_log('File type not allowed');
                        array_push($errorArray, $response);
                    }
                }
            }

            if (!empty($errorArray)) {
                error_log(json_encode($errorArray));
                echo json_encode($errorArray);
            } else {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Files uploaded successfully!',
                ]);
            }
        } else {
            error_log("Upload not set");
            echo json_encode([
                'status' => 'error',
                'message' => 'No files uploaded!',
            ]);
            exit();
        }
    }
}
