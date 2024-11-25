<!-- start form -->
<div class="d-flex justify-content-center">
    <form method="POST" action="backend/login.php" class="p-3 p-md-5 border rounded-3 bs-gray">
        <h1 class="mb-3 fw-bold text_blue">Please login</h1>
        <div class="form-floating">
            <input type="email" name="Login_Email" class="form-control" id="Login_Email" placeholder="name@example.com">
            <label for="Login_Email">Email address</label>
        </div>
        <div class="form-floating">
            <input type="password" name="Login_Password" class="form-control" id="Login_Password" placeholder="Password">
            <label for="Login_Password">Password</label>
        </div>  
        <button class="btn btn-primary w-100 py-1 mt-4" type="submit" name= "Login" value= "Login">Login</button>
        <p class="mt-5 mb-3 text-body-secondary">&copy; Duck–2024</p>
    </form>
</div>
<!-- end form -->