<div class="py-5 text-center">
    <!-- <img class="d-block mx-auto mb-4" src="../assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> -->
    <h2>Singin</h2>
    <p class="lead">Welcome to Duck, please singin</p>
</div>

<div class="row g-5">
    <div class="p-4 p-md-5 border rounded-3 bg-body-tertiary">
        <form method="POST" action="backend/register.php" class="needs-validation" novalidate>
            <div class="row g-3">
                <div class="col-sm-6">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="RegisterUsername" name="RegisterUsername" placeholder="" value="" required>
                    <div class="invalid-feedback">
                        Valid username is required.
                    </div>
                </div>

                <div class="col-sm-6">
                    <label for="RegisterName" class="form-label">First name</label>
                    <input type="text" class="form-control" id="RegisterName" name="RegisterName" placeholder="" value="" required>
                    <div class="invalid-feedback">
                        Valid first name is required.
                    </div>
                </div>

                <div class="col-sm-6">
                    <label for="RegisterSurname" class="form-label">Last name</label>
                    <input type="text" class="form-control" id="RegisterSurname" name="RegisterSurname" placeholder="" value="" required>
                    <div class="invalid-feedback">
                        Valid last name is required.
                    </div>
                </div>


                <div class="col-sm-6">
                    <label for="email-input" class="form-label">Email <span class="text-body-secondary"></span></label>
                    <input type="email" class="form-control" id="RegisterEmail" name="RegisterEmail" placeholder="you@example.com" required>
                    <div class="invalid-feedback">
                        Please enter a valid email address.
                    </div>


                </div>

                <!-- Password -->
                <div class="col-sm-6">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="RegisterPassword" name="RegisterPassword" value="" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                    <div class="invalid-feedback">
                        Password is required.
                    </div>
                </div>

                <div class="col-sm-6">
                    <label for="ConfirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="ConfirmRegisterPassword" name="ConfirmRegisterPassword" value="" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                    <div class="invalid-feedback">
                        Password is required.
                    </div>
                </div>
                    
                <hr class="my-4">

                <button class="w-100 btn btn-primary btn-lg" type="submit">Continue and register</button>
        </form>
    </div>
</div>
<script src="assets/js/checkout.js"></script>