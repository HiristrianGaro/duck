<script>
<?php
    include "../common/connection.php";
    include "../common/funzioni.php";

    if($_SERVER["REQUEST_METHOD"]== "POST"){

        if(isset($_POST["Login"])){
            if(empty($_POST["Login_Email"])){
                $errore= "Inserire un indirizzo email";
                $showModal = "true";
            }elseif (empty($_POST["Login_Password"])){
                $errore= "Inserire una password";
                $showModal = "true";
            }else{
                $Email = filter_input(INPUT_POST, "Login_Email", FILTER_SANITIZE_EMAIL);

                $Password = filter_input(INPUT_POST, "Login_Password", FILTER_SANITIZE_SPECIAL_CHARS);

                $findUtente = "SELECT * FROM utente 
                            WHERE IndirizzoEmail = '$Email'";
                $findResults = mysqli_query($cid, $findUtente);
                
                echo 'ciaoooo';

                if($findResults){
                    echo mysqli_num_rows($findResults);
                    if(mysqli_num_rows($findResults)==1){
                        $row = mysqli_fetch_assoc($findResults);
                        $hashed = $row["Password"];
                        if(password_verify($Password,$hashed)){
                            $_SESSION["Email"]= $Email;
                            $_SESSION["Status"]= 'ok';
                            header("Location: ./index.php");
                            exit();
                        }
                        else{
                            $errore= "Password errata";
                            $showModal = "true";
                        }
                    }else{
                        $errore= "Email errata";
                        $showModal = "true";
                    }
                }else{
                    echo ("MySql Query failed:".mysqli_error($cid));
                    exit();
                }
                
            }       
        }
    }
?>
</script>