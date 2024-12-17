<?php
include('../config.php');
include('imagecrop.php');
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_FILES['filesToUpload'])) {
        $error = false;
        $files_to_upload = $_FILES['filesToUpload'];
        
        foreach($files_to_upload['error'] as $error_message) {
            if( $error ) {
                error_log("Error: " . $error_message . "<br />");
                $error = true;
            }
        }

        if ($files_to_upload['name'][0] === '') {
            error_log("No files uploaded");
            echo json_encode([
                'status' => 'error',
                'message' => 'No files uploaded!',
            ]);
            exit();
        }

        if ( !$error ) {
            $errorArray = array();
            
            for( $i = 0; $i < count( $files_to_upload['name'] ); $i++ ) {
                $oldName = $_FILES['filesToUpload']['name'][$i];
                $newName = time() . '_' . $_FILES['filesToUpload']['name'][$i];
                $destination = POST_DIR . '/' . $newName;
                if (is_uploaded_file($_FILES['filesToUpload']['tmp_name'][$i])) {
                    $mime_type = mime_content_type($_FILES['filesToUpload']['tmp_name'][$i]);
                    error_log($mime_type);
                    $allowed_file_types = ['image/jpeg', 'image/gif', 'image/png'];
                    if (in_array($mime_type, $allowed_file_types)) {
                        // File type is NOT allowed.
                        cropImageToAspectRatioGD($_FILES['filesToUpload']['tmp_name'][$i], $destination, 4, 5);
                    } else {
                        $response = [
                            'name' => $_FILES['filesToUpload']['name'][$i],
                            'status' => 'error',
                            'message' => 'File type not allowed!',
                        ];
                        error_log('File type not allowed');
                        array_push($errorArray, $response);
                    }
                }
            }
            error_log(json_encode($errorArray));
            echo json_encode($errorArray);

        } else {
            error_log("Upload not set");
            exit();
        }
    }
}

?>
