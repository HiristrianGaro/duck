$(document).ready(function () {
    $('.nav-load').on('click', function (event) {
        event.preventDefault(); // Prevent default link behavior

        const targetFile = $(this).data('target'); // Get the target file from the data attribute

        // Make the AJAX call
        $.ajax({
            url: targetFile,
            method: 'GET',
            success: function (data) {
                $('#main-page').html(data); // Load the response into the #content element

                toggleButtons(targetFile);
            },
            error: function () {
                $('#main-page').html('<p>Error loading content. Please try again later.</p>');
            }
        });
    });
});

$('.nav-logout').click(function() {
    $.ajax({
      type: "POST",
      url: "backend/logout.php",
    }).done(function( msg ) {
      alert( "Data Saved: " + msg );
    });
  });

function toggleButtons(targetFile) {
    // Example logic: adjust visibility based on the page
        $('#login-button').show();
        $('#signup-button').show();
        $('#logout-button').show();

    if (targetFile === 'frontend/loginPage.php') {
        $('#login-button').hide();
        $('#register-button').show();
        $('#logout-button').hide();
    } else if (targetFile === 'frontend/registerPage.php') {
        $('#login-button').show();
        $('#register-button').hide();
        $('#logout-button').hide();
    } else if (targetFile === 'backend/logout.php') {
        $('#login-button').show();
        $('#register-button').show();
        $('#logout-button').hide();
    }
}