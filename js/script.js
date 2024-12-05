console.log('Script is loaded.');

let myDictionary = {
	"frontend/loginPage.php": "Login",
  	"frontend/registerPage.php": "Register",
	"frontend/pond.php": "Pond",
    "frontend/friends.php": "Friends",
    "frontend/profilePage.php": "Profile"
};

function convertToNiceLink(internalLink) {
    return myDictionary[internalLink] || internalLink; // Fallback to the original if not found
}

// Function to convert a nicer-looking link back to the internal one
function convertToInternalLink(niceLink) {
    return Object.keys(myDictionary).find(key => myDictionary[key] === niceLink) || niceLink;
}


$(document).ready(function () {
    console.log('Script is loaded.');
    console.log(window.location.pathname);

    // Check if the current page is index.php
    if (window.location.pathname === 'duck/index.php') {
        console.log('index state');
        // Clear the browser's history for back navigation
        history.replaceState(null, '', '/duck/index.php');
        console.log('Back button cleared for index.php');
    }

    // Handle navigation clicks
    $('.nav-load').on('click', function (event) {
        event.preventDefault(); // Prevent default link behavior

        const targetFile = $(this).data('target'); // Get the target file from the data attribute
        console.log(targetFile);

        history.pushState({ targetFile: targetFile }, '', `?page=${targetFile}`);

        // Make the AJAX call
        loadPage(targetFile);
    });

    // Handle refresh or direct access
    const params = new URLSearchParams(window.location.search);
    const page = params.get('page');
    if (page) {
        // Load the page specified in the URL
        loadPage(page);
    }
});

window.addEventListener('popstate', function (event) {
    if (event.state && event.state.targetFile) {
        const targetFile = event.state.targetFile;

        // Load the page from the state
       loadPage(targetFile);
    }
});

function loadPage(targetFile) {
    $.ajax({
        url: targetFile,
        method: 'GET',
        success: function (data) {
            $('#main-page').html(data); // Inject the content into #main-page
            toggleButtons(targetFile); // Update buttons or UI if needed
        },
        error: function () {
            $('#main-page').html('<p>Error loading content. Please try again later.</p>');
        }
    });
}


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