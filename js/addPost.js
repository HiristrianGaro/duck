console.log('addPost.js loaded');


function fileSelect(event) {
    if (window.File && window.FileReader && window.FileList && window.Blob) {
        const files = event.target.files;
        const result = Array.from(files)
            .map(file => `<li>${file.name} (${file.size} bytes)</li>`)
            .join('');
        console.log('Selected files:', result);
    } else {
        alert('The File APIs are not fully supported in this browser.');
    }
}


function displayErrors(response) {
    const alertElement = document.getElementById('addPostAlertP');
    alertElement.classList.add('text-danger');
    alertElement.innerHTML = '';

    try {
        const data = JSON.parse(response);
        console.log('Error response data:', data);

        if (!Array.isArray(data)) {
            console.log('Single error message:', data.message);
            alertElement.innerHTML = data.message;
        } else {
            data.forEach((error, index) => {
                console.log(`Error ${index}:`, error);
                alertElement.innerHTML += `${error.name}: ${error.message}<br>`;
            });
        }
    } catch (err) {
        console.error('Failed to parse response:', err);
        alertElement.innerHTML = 'An unexpected error occurred. Please try again.';
    }
}


$('#addPostForm').on('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    var region = $('#region').find(":selected").text();
    var province = $('#province').find(":selected").text();
    var city = $('#city').find(":selected").text();

    formData.set('region', region);
    formData.set('province', province);
    formData.set('city', city);

    $.ajax({
        url: 'backend/createPost.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log('Server response:', response);

            if ($.isArray(response) || response.length > 2) {
                console.error('Form submission errors:', response);
                displayErrors(response);
            } else {
                console.log('Form submitted successfully!');
                history.pushprovince({ targetFile: 'frontend/pond.php' }, '', '?page=frontend/pond.php');
                loadPage('frontend/pond.php');
            }
        },
        error: function () {
            alert('An error occurred while submitting the form. Please try again.');
        }
    });
});


document.getElementById('filesToUpload').addEventListener('change', fileSelect, false);
