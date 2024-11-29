console.log('Script is loaded.');

$(document).ready(function () {
    // Check if the current page is index.php
    console.log(window.location.pathname);
    if (window.location.pathname === 'duck/index.php') {
        console.log('index state');
        // Clear the browser's history for back navigation
        history.replaceState(null, '', '/duck/index.php');
        console.log('Back button cleared for index.php');
    }
});

$(document).ready(function () {
    $('.nav-load').on('click', function (event) {
        event.preventDefault(); // Prevent default link behavior

        const targetFile = $(this).data('target'); // Get the target file from the data attribute
        console.log(targetFile);

        history.pushState({ targetFile: targetFile }, '', `?page=${targetFile}`);

        // Make the AJAX call
        $.ajax({
            url: targetFile,
            method: 'GET',
            success: function (data) {
                $('#main-page').html(data); // Load the response into the #content element
                window.history.pushState

                toggleButtons(targetFile);
            },
            error: function () {
                $('#main-page').html('<p>Error loading content. Please try again later.</p>');
            }
        });
    });
});

window.addEventListener('popstate', function (event) {
    if (event.state && event.state.targetFile) {
        const targetFile = event.state.targetFile;

        // Load the page from the state
        $.ajax({
            url: targetFile,
            method: 'GET',
            success: function (data) {
                $('#main-page').html(data); // Load the response into the #main-page element
                toggleButtons(targetFile);
            },
            error: function () {
                $('#main-page').html('<p>Error loading content. Please try again later.</p>');
            }
        });
    }
});

$(document).ready(function () {
    const params = new URLSearchParams(window.location.search);
    const page = params.get('page');

    if (page) {
        // Load the page specified in the URL
        $.ajax({
            url: page,
            method: 'GET',
            success: function (data) {
                $('#main-page').html(data); // Load the response into the #main-page element
                toggleButtons(page);
            },
            error: function () {
                $('#main-page').html('<p>Error loading content. Please try again later.</p>');
            }
        });
    }
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