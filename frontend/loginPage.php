<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    session_unset();
    require_once("../common/connection.php");
    include("../common/modalErrore.php");
    include("../common/errorModal.php");
?>

<div class="d-flex justify-content-center px-lg-3">
    <div class="p-3 p-md-4 border rounded-3 bg-white">
        <div class="pb-3 text-center">
        <img class="d-block mx-auto mb-3" src="assets/brand/duck.png" alt="" width="72" height="57">
        <h2 class="text-blue mb-3">Login</h2>    
        <p class="lead">Welcome back to <span class="text-yellow fw-bold">Duck</span></p>
        <p class="lead mb-3">Login to <span class="text-blue fw-bold">Quack</span> with your friends!</p>
    </div>
    <form method="POST" id="loginForm" enctype="text/plain" class="needs-validation" novalidate>
        <div class="form-floating">
            <input type="text" name="Login_Id" class="form-control mb-1" id="Login_Id" autocomplete="email" placeholder="name@example.com" required>
            <label for="Login_Id" class="pb-4">Email address</label>
        </div>
        <div class="form-floating">
            <input type="password" name="Login_Password" class="form-control" id="Login_Password" autocomplete="current-password" placeholder="Password" required>
            <label for="Login_Password">Password</label>
        </div>  
        <button class="btn btn-primary w-100 py-1 mt-4" type="submit" name= "Login" value= "Login">Login</button>
        <p class="mt-5 mb-3 text-body-secondary">&copy; Duck–2024</p>
    </form>
</div>

<script src="js/loginRegister.js"></script>