// Example starter JavaScript for disabling form submissions if there are invalid fields
(() => {
    'use strict'
  
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation')
  
    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
  
        form.classList.add('was-validated')
      }, false)
    })
  })();

  function validateRegisterForm() {
    var alert = document.getElementById('registerAlert');
    const password = document.querySelector('#RegisterPassword').value;
    const confirmPassword = document.querySelector('#ConfirmRegisterPassword').value;

    if (password === '' || confirmPassword === '') {
        alert.classList.add('text-danger');
        alert.innerHTML = 'Please fill in Password and Confirm Password fields.';
        return false;
    }

    if (password !== confirmPassword) {
        var alert = document.getElementById('registerAlert');
        alert.classList.add('text-danger');
        alert.innerHTML = 'Passwords do not match.';
        return false;
    } else {
        alert.innerHTML = '';
    }

    return true;
}


$('#loginForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form reload
    const formData = new FormData(this);

    $.ajax({
        url: 'backend/login.php', // Your PHP backend script
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            data = JSON.parse(response);
            var obj = data[0];
            if (obj['status'] == 'Success') {
                console.log('Success!');
                history.pushState({ targetFile: obj['redirect'] }, '', `?page=${obj['redirect']}`);
                window.location.reload();
            }

        },
        error: function () {
            alert('Something went wrong. Please try again.');
        }
    });
});

$('#registerForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form reload

    if (!validateRegisterForm()) {
        console.log('Validation failed');
        return; // Stop if validation fails
    }

    const formData = new FormData(this);
    console.log('Form Data:', formData);

    $.ajax({
        url: 'backend/register.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            const data = JSON.parse(response);
            console.log('Data:', data);
            const obj = data[0];
            if (obj['status'] === 'Success') {
                console.log('Success!');
                history.pushState({ targetFile: obj['redirect'] }, '', `?page=${obj['redirect']}`);
                window.location.reload();
            }
        },
        error: function () {
            alert('Something went wrong. Please try again.');
        }
    });
});
