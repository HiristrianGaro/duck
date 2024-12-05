console.log('Script is loaded.');
let myDictionary = {
	"frontend/loginPage.php": "Login",
  	"frontend/registerPage.php": "Register",
	"frontend/pond.php": "Pond",
    "frontend/friends.php": "Friends",
    "frontend/profilePage.php": "Profile"
};

$(document).ready(function () {
    // Navigation handler for links
    $('.nav-load').on('click', function (event) {
        event.preventDefault(); // Prevent default link behavior

        const targetFile = $(this).data('target'); // Get the target file
        console.log(targetFile);
        const pageName = myDictionary[targetFile]; // Get the friendly page name

        if (pageName) {
            // Update the URL without reloading the page
            history.pushState(
                { targetFile: targetFile },
                '', // Title (optional)
                `/${pageName}` // Clean URL
            );

            // Dynamically load the content
            loadPage(targetFile);
        }
    });

    // On page load or refresh, handle the URL
    const currentPath = window.location.pathname.slice(1); // Remove leading '/'
    if (currentPath) {
        // Map the clean path to the corresponding file
        const targetFile = Object.keys(myDictionary).find(
            key => myDictionary[key] === currentPath
        );

        if (targetFile) {
            loadPage(targetFile);
        } else {
            $('#main-page').html('<p>Page not found.</p>');
        }
    }

    // Handle back/forward navigation
    window.addEventListener('popstate', function (event) {
        if (event.state && event.state.targetFile) {
            loadPage(event.state.targetFile);
        }
    });
});

// Function to load content via AJAX
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