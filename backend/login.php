<?php
    include "../common/connection.php";
    include "../common/funzioni.php";


    if(empty($_POST["Login_Email"])){
        $errore= "Inserire un indirizzo email";
        $showModal = "true";
        echo $errore;
    }elseif (empty($_POST["Login_Password"])){
        $errore= "Inserire una password";
        $showModal = "true";
        echo $errore;
    }else{
        $Email = filter_input(INPUT_POST, "Login_Email", FILTER_SANITIZE_EMAIL);

        $Password = filter_input(INPUT_POST, "Login_Password", FILTER_SANITIZE_SPECIAL_CHARS);

        echo checkDB($cid)['status'];

        $findUtente = "SELECT * FROM UTENTE 
                    WHERE IndirizzoEmail = '$Email'";
        $findResults = mysqli_query($cid, $findUtente);
        

        if($findResults){
            echo "ciao5";
            echo mysqli_num_rows($findResults);
            if(mysqli_num_rows($findResults)==1){
                $row = mysqli_fetch_assoc($findResults);
                $hashed = $row["Password"];
                echo "ciao6";
                if(password_verify($Password,$hashed)){
                    $_SESSION["Email"]= $Email;
                    $_SESSION["Status"]= 'ok';
                    header("Location: ../index.php");
                    echo "ciao7";
                    exit();
                }
                else{
                    $errore= "Password errata";
                    $showModal = "true";
                    echo "ciao8";
                }
            }else{
                $errore= "Email errata";
                $showModal = "true";
                echo "ciao9";
            }
        }else{
            echo ("MySql Query failed:".mysqli_error($cid));
            exit();
            echo "ciao10";
        }
        
    };            
?>