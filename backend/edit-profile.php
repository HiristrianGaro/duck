<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
session_unset();
include '../errorLogging.php';
include("../common/connection.php");
include("../common/funzioni.php");
include("../common/verifyuserpsw.php");

error_log("Registering...");
$result = array();
if($_SERVER["REQUEST_METHOD"]== "POST"){
    error_log('User is trying to edit profile!');

    $profileImage = filter_input(INPUT_POST, "CurrentProfileImage", FILTER_SANITIZE_SPECIAL_CHARS);
    $Username = filter_input(INPUT_POST, "EditUsername", FILTER_SANITIZE_SPECIAL_CHARS);
    $Nome = filter_input(INPUT_POST, "EditName", FILTER_SANITIZE_SPECIAL_CHARS);
    $Cognome = filter_input(INPUT_POST, "EditSurname", FILTER_SANITIZE_SPECIAL_CHARS);
    $CurrentPassword = filter_input(INPUT_POST, "CurrentEditPassword", FILTER_SANITIZE_SPECIAL_CHARS);
    $NewPassword = filter_input(INPUT_POST, "NewEditPassword", FILTER_SANITIZE_SPECIAL_CHARS);
    $ConfirmPassword = filter_input(INPUT_POST, "ConfNewEditPassword", FILTER_SANITIZE_SPECIAL_CHARS);

    $hashed = password_hash($Password, PASSWORD_DEFAULT);

    error_log("Checking if email already exists...");

    if(!checkUsernameExist($cid, $Username)) {
        if (verifyUserPsw($cid, $_SESSION['IndirizzoEmail'], $CurrentPassword)) {
            error_log("Current password is correct.");
            if ($NewPassword === $ConfirmPassword) {
                error_log("New password matches confirmation.");
                $hashed = password_hash($NewPassword, PASSWORD_DEFAULT);
            } else {
                error_log("New password does not match confirmation.");
                $response = [
                    'status' => 'error',
                    'Message' => 'New password does not match confirmation.',
                ];
                array_push($result, $response);
                echo json_encode($result);
                exit();
            }
        } else {
            error_log("Current password is incorrect.");
            $response = [
                'status' => 'error',
                'Message' => 'Current password is incorrect.',
            ];
            array_push($result, $response);
            echo json_encode($result);
            exit();
        }
        error_log("Username does not exist. Proceeding with insertion...");
        $registration = "INSERT INTO utente (Username, IndirizzoEmail, Nome, Cognome, Password)
                        VALUES ('$Username','$Email','$Nome','$Cognome','$hashed')";
        if (mysqli_query($cid, $registration)) {
            $_SESSION["Username"] = $Username;
            $_SESSION["IndirizzoEmail"] = $Email;
            $_SESSION["Status"] = 'ok';
            $response = [
                'status' => 'Success',
                'redirect' => 'frontend/pond.php', //Deve diventare la pagina di setup profilo
            ];
            array_push($result, $response);
            error_log("Registration successful.\n");
        } else {
            error_log("Error during registration: " . mysqli_error($cid));
        }
    } else {
        $response = [
            'status' => 'error',
            'Message' => 'Username already exists, Please use another username',
        ];
        array_push($result, $response);
        error_log("Username already exists, Please use another username");
    }
         json_encode($result);
    error_log(json_encode($result));
}
?>