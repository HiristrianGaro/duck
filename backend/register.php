<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
session_unset();
include '../errorLogging.php';
include("../common/connection.php");
include("../common/funzioni.php");

error_log("Registering...");
$result = array();
if($_SERVER["REQUEST_METHOD"]== "POST"){
    error_log('User has Started Registering in!');
    error_log(filter_input(INPUT_POST, "RegisterUsername", FILTER_SANITIZE_SPECIAL_CHARS));
    $Username = filter_input(INPUT_POST, "RegisterUsername", FILTER_SANITIZE_SPECIAL_CHARS);
    $Nome = filter_input(INPUT_POST, "RegisterName", FILTER_SANITIZE_SPECIAL_CHARS);
    $Cognome = filter_input(INPUT_POST, "RegisterSurname", FILTER_SANITIZE_SPECIAL_CHARS);
    $Email = filter_input(INPUT_POST, "RegisterEmail", FILTER_SANITIZE_EMAIL);
    $Password = filter_input(INPUT_POST, "RegisterPassword", FILTER_SANITIZE_SPECIAL_CHARS);
    $ConfirmPassword = filter_input(INPUT_POST, "ConfirmRegisterPassword", FILTER_SANITIZE_SPECIAL_CHARS);

    $hashed = password_hash($Password, PASSWORD_DEFAULT);

    error_log("Checking if email already exists...");

    if (!checkEmailExist($cid, $Email)) {
        error_log("Email does not exist. Proceeding with insertion...");
        if(!checkUsernameExist($cid, $Username)) {
            error_log("Email does not exist. Proceeding with insertion...");
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
    } else {
        $response = [
            'status' => 'error',
            'Message' => 'Email already exists, Please login or use another email',
        ];
        array_push($result, $response);
        error_log("Email already exists, Please login or use another email");
    }
    echo json_encode($result);
    error_log(json_encode($result));
}
?>