console.log('addPost.js loaded');

function fileSelect(event) {
    if (window.File && window.FileReader && window.FileList && window.Blob) {
        var files = event.target.files;
        var result = '';
        var file;
    for (var i = 0; file = files[i]; i++) {
        result += '<li>' + file.name + ' ' + file.size + ' bytes</li>';
        }
    } else {
    alert('The File APIs are not fully supported in this browser.');
    }
}

function checkErrors(response) {
    var alert = document.getElementById('addPostAlertP');
    alert.classList.add('text-danger');
    alert.innerHTML = '';
    data = JSON.parse(response);
    console.log('Data:', data);
    if (data.length == null){
        console.log('Data is empty');
        alert.innerHTML = data.message;
    } else {
        for (var i = 0; i < data.length; i++){
            console.log('Array Index:', i);
            var obj = data[i];
            var name = obj['name'];
            var message = obj['message'];
            alert.innerHTML += name + ': ' + message + '<br>';
        }
    }
}

$('#addPostForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form reload

    const formData = new FormData(this);
    $.ajax({
        url: 'backend/addPost.php', // Your PHP backend script
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if( $.isArray(response) ||  response.length > 2) {
                console.log('Error:', response);
                checkErrors(response);
            } else {
                console.log('Success!');
                history.pushState({ targetFile: 'frontend/pond.php' }, '', `?page=${'frontend/pond.php'}`);
                loadPage('frontend/pond.php');
            }
            
        },
        error: function () {
            alert('Something went wrong. Please try again.');
        }
    });
});

document.getElementById('filesToUpload').addEventListener('change', fileSelect, false);