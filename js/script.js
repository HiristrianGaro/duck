console.log('Script is loaded.');

$(document).ready(function () {
    console.log('Script Started');
    console.log(window.location.pathname);

    // Handle navigation clicks
    $('.nav-load').on('click', function (event) {
        event.preventDefault(); // Prevent default link behavior

        const targetFile = $(this).data('target'); // Get the target file from the data attribute
        console.log(targetFile, 'clicked'); // Log the clicked target

        if (targetFile === 'frontend/profilepage.php') {

            targetUser = $(this).data('username');
            history.pushState({ targetFile: targetFile }, '', `?page=${targetFile}&username=${targetUser}`);
        }else{

            history.pushState({ targetFile: targetFile }, '', `?page=${targetFile}`);
        }

        loadPage(targetFile);

        hideSearchBar();

    });

    // Handle refresh or direct access
    const params = new URLSearchParams(window.location.search);
    const page = params.get('page');
    if (page) {
        // Load the page specified in the URL
        loadPage(page);
    }

    // Restore the profile page state

    // Hide search bar when clicking outside
    $(document).on('click', function (event) {
        if (!$(event.target).closest('.searchCollapse, #search-input').length) {
            hideSearchBar();
        }
    });

    // Hide search bar function
    function hideSearchBar() {
        $('#SeachCollapse').collapse('hide');
    }
});

// Other existing functions...


window.addEventListener('popstate', function (event) {
    if (event.state && event.state.targetFile) {
        const targetFile = event.state.targetFile;

        // Load the page from the state
        loadPage(targetFile);
    }
});

function hideSearchBar() { $('#SeachCollapse').collapse('hide'); }

$(document).on('click', function (event) { 
    if (!$(event.target).closest('.searchCollapse, #search-input').length) { 
        hideSearchBar(); 
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

async function fetchTemplate(url) {
    const response = await fetch(url);
    return response.text();
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
