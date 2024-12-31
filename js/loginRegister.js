// Example starter JavaScript for disabling form submissions if there are invalid fields
(() => {
    'use strict';

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation');

    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
        form.addEventListener(
            'submit',
            event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            },
            false
        );
    });
})();

function checkIfEmailExists(){
    const email = document.querySelector('#RegisterEmail').value.trim();

    $.ajax({
        url: 'common/funzioni.php',
        method: 'POST',
        data: { email: email },
        success: function (response) {
            console.log('Server response:', response);

            if (response === 'true') {
                alert('Email already exists. Please use a different email address.');
            }
        },
        error: function () {
            alert('An error occurred while checking the email. Please try again.');
        }
    });
}

function validateRegisterForm() {
    const alert = document.getElementById('registerAlert');
    const password = document.querySelector('#RegisterPassword').value.trim();
    const confirmPassword = document.querySelector('#ConfirmRegisterPassword').value.trim();

    if (!password || !confirmPassword) {
        alert.classList.add('text-danger');
        alert.innerHTML = 'Please fill in both Password and Confirm Password fields.';
        return false;
    }

    if (password !== confirmPassword) {
        alert.classList.add('text-danger');
        alert.innerHTML = 'Passwords do not match.';
        return false;
    }

    alert.innerHTML = '';
    return true;
}


$('#loginForm').on('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    $.ajax({
        url: 'backend/login.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            try {
                const data = JSON.parse(response);
                const obj = data[0];

                if (obj.status === 'Success') {
                    console.log('Login successful!');
                    history.pushState({ targetFile: obj.redirect }, '', `?page=${obj.redirect}`);
                    window.location.reload();
                } else {
                    console.error('Login failed:', obj.message || 'Unknown error');
                }
            } catch (err) {
                console.error('Failed to parse login response:', err);
            }
        },
        error: function () {
            alert('Something went wrong. Please try again.');
        }
    });
});


$('#registerForm').on('submit', function (e) {
    e.preventDefault();

    if (!validateRegisterForm()) {
        console.log('Registration form validation failed.');
        return;
    }

    const formData = new FormData(this);

    $.ajax({
        url: 'backend/register.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            try {
                const data = JSON.parse(response);
                console.log('Registration response data:', data);

                const obj = data[0];
                if (obj.status === 'Success') {
                    console.log('Registration successful!');
                    history.pushState({ targetFile: obj.redirect }, '', `?page=${obj.redirect}`);
                    window.location.reload();
                } else {
                    console.error('Registration failed:', obj.message || 'Unknown error');
                }
            } catch (err) {
                console.error('Failed to parse registration response:', err);
            }
        },
        error: function () {
            alert('Something went wrong. Please try again.');
        }
    });
});
