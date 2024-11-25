<?php
    session_start(); 
    session_unset();
    require_once("./common/connection.php");
    require_once("./backend/register.php");
?>
<div class="d-flex justify-content-center px-lg-3">
    <div class="p-3 p-md-4 border rounded-3 bg-white">
        <div class="pb-5 text-center">
        <img class="d-block mx-auto mb-3" src="assets/brand/duck.png" alt="" width="72" height="57">
        <h2 class="text-blue">Register Your Account</h2>    
        <p class="lead">Welcome to <span class="text-yellow fw-bold">Duck</span>, all we need is your information below!</p>
    </div>
        <form method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" class="needs-validation" novalidate>
            <div class="row">
                <div class="form-floating mb-3 col col-sm-6">
                    <input type="text" class="form-control" id="RegisterUsername" name="RegisterUsername" placeholder="" required>
                    <label for="username" class="form-label mx-2">Username</label>
                    <div class="invalid-feedback">
                        Valid username is required.
                    </div>
                </div>

                <div class="form-floating mb-3  col-sm-6">
                    <input type="text" class="form-control" id="RegisterName" name="RegisterName" placeholder="" required>
                    <label for="RegisterName" class="form-label mx-2">First name</label>
                    <div class="invalid-feedback">
                        Valid first name is required.
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-floating mb-3  col-sm-6">
                    <input type="text" class="form-control" id="RegisterSurname" name="RegisterSurname" placeholder="" required>
                    <label for="RegisterSurname" class="form-label mx-2">Last name</label>
                    <div class="invalid-feedback">
                        Valid last name is required.
                    </div>
                </div>


                <div class="form-floating mb-3  col-sm-6">
                    <input type="email" class="form-control" id="RegisterEmail" name="RegisterEmail" placeholder="you@example.com" required>
                    <label for="RegisterEmail" class="form-label mx-2">Email</label>
                    <div class="invalid-feedback">
                        Please enter a valid email address.
                    </div>
                </div>
            </div>
            <div class="row">

                <!-- Password -->
                <div class="form-floating mb-3  col-sm-6">
                    <input type="password" class="form-control" id="RegisterPassword" name="RegisterPassword" placeholder="Password123" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                    <label for="RegisterPassword" class="form-label mx-2">Password</label>
                    <div class="invalid-feedback">
                        Password is required.
                    </div>
                </div>

                <div class="form-floating mb-3  col-sm-6">
                    <input type="password" class="form-control" id="ConfirmRegisterPassword" name="ConfirmRegisterPassword" placeholder="Password123" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                    <label for="ConfirmPassword" class="form-label mx-2">Confirm Password</label>
                    <div class="invalid-feedback">
                        Password is required.
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-3">
                <button class="btn btn-primary" type="submit" value="Register" name="Register">Continue and register</button>
            </div>
        </form>
    </div>
</div>

<?php include("./common/modalErrore.php")?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<?php include("./common/errorModal.php")?>