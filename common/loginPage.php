<?php
    session_start(); 
    session_unset();
    require_once("./common/connection.php");
    require_once("./backend/login.php");
?>
<div class="d-flex justify-content-center">
    <div class="p-3 px-md-5 border rounded-3 bg-white">
        <div class="pb-2 text-center">
        <img class="d-block mx-auto mb-3" src="assets/brand/duck.png" alt="" width="72" height="57">
        <h2 class="text-blue mb-3">Login</h2>    
        <p class="lead">Welcome back to <span class="text-yellow fw-bold">Duck</span></p>
        <p class="lead mb-3">Login to <span class="text-blue fw-bold">Quack</span> with your friends!</p>
    </div>
    <form method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" class="needs-validation">
        <div class="form-floating">
            <input type="email" name="Login_Email" class="form-control mb-1" id="Login_Email" placeholder="name@example.com">
            <label for="Login_Email" class="pb-4">Email address</label>
        </div>
        <div class="form-floating">
            <input type="password" name="Login_Password" class="form-control" id="Login_Password" placeholder="Password">
            <label for="Login_Password">Password</label>
        </div>  
        <button class="btn btn-primary w-100 py-1 mt-4" type="submit" name= "Login" value= "Login">Login</button>
        <p class="mt-5 mb-3 text-body-secondary">&copy; Duck–2024</p>
    </form>
</div>

<?php include("./common/modalErrore.php")?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<?php include("./common/errorModal.php")?>