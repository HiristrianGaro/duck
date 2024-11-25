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
                    <input type="text" class="form-control" id="RegisterUsername" name="username" placeholder="" value="" required>
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



                <div class="col-sm-6">
                    <label for="DoB" class="form-label"> Date Born </label>
                    <input type="date" class="form-control" id="DoB" name="DoB" placeholder="" required>
                </div>


                <div class="col-sm-6">
                    <label for="gender" class="form-label">Gender<span class="text-body-secondary"> (Optional)</span></label>

                    <div></div>
                    <div class="form-check form-check-inline ">
                        <input class="form-check-input" type="radio" name="RegisterGender" id="RegisterGender" value="Maschio">
                        <label class="form-check-label" for="male">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="RegisterGender" id="RegisterGender" value="Femmina">
                        <label class="form-check-label" for="female">Female</label>
                    </div>
                </div>



                <!-- Place Born -->

                <div class="col-md-5">
                    <label for="Stato" class="form-label">Stato</label>
                    <input type="text" class="form-select Stato" aria-label="Default select example" name="RegisterState" id="RegisterState">
                    <div class="invalid-feedback">
                        Please select a valid Stato.
                    </div>
                </div>

                <!-- Funziona solo con le città nel database. Vorremmo implementare una API per le città in futuro -->



                <div class="col-md-4">
                    <label for="città" class="form-label">Città</label>
                    <input type="text" class="form-select città" aria-label="Default select example" name="RegisterCity" id="RegisterCity">
                    <div class="invalid-feedback">
                        Please select a valid città.
                    </div>
                </div>


                <div class="col-md-3">
                    <label for="Provincia" class="form-label">Provincia</label>
                    <input type="text" class="form-select Provincia" aria-label="Default select example" name="RegisterProvince" id="RegisterProvince">
                    <div class="invalid-feedback">
                        Please select a valid city.
                    </div>
                    
                </div>





                <hr class="my-4">

                <button class="w-100 btn btn-primary btn-lg" type="submit">Continue and register</button>
        </form>
    </div>
</div>
<script src="assets/js/checkout.js"></script>