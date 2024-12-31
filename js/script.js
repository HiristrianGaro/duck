console.log('Script is loaded.');


$(document).ready(function () {
    console.log('Script Started');
    console.log(window.location.pathname);

    $('.nav-load').on('click', function (event) {
        event.preventDefault();

        const targetFile = $(this).data('target');
        console.log(targetFile, 'clicked');

        if (targetFile === 'frontend/profilepage.php') {

            targetUser = $(this).data('username');
            history.pushState({ targetFile: targetFile }, '', `?page=${targetFile}&username=${targetUser}`);
        }else{

            history.pushState({ targetFile: targetFile }, '', `?page=${targetFile}`);
        }

        if (targetFile === 'home') {
            loadPage(targetFile);
        }

        loadPage(targetFile);

        hideSearchBar();

    });

    const params = new URLSearchParams(window.location.search);
    const page = params.get('page');
    if (page) {
        loadPage(page);
    }

    $(document).on('click', function (event) {
        if (!$(event.target).closest('.searchCollapse, #search-input').length) {
            hideSearchBar();
        }
    });

    function hideSearchBar() {
        $('#SeachCollapse').collapse('hide');
    }
    
    $('.noEnterSubmit').keypress(function(e){
        if ( e.which == 13 ) return false;
        if ( e.which == 13 ) e.preventDefault();
    });
});



window.addEventListener('popstate', function (event) {
    if (event.state && event.state.targetFile) {
        const targetFile = event.state.targetFile;

        loadPage(targetFile);
    }
});

function hideSearchBar() { $('#SeachCollapse').collapse('hide'); }

$(document).on('click', function (event) { 
    if (!$(event.target).closest('.SearchCollapse, #search-input').length) { 
        hideSearchBar(); 
    } 
});

function loadPage(targetFile) {
    $.ajax({
        url: targetFile,
        method: 'GET',
        success: function (data) {
            $('#main-page').html(data);
            toggleButtons(targetFile);
            console.log('Page loaded:', targetFile);
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
function showLoadingIndicator(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = '<p class="text-center">Loading...</p>';
    }
}

function formatTimestamp(timestamp) {
    const now = new Date();
    const postTime = new Date(timestamp);
    const diffInSeconds = Math.floor((now - postTime) / 1000);

    if (diffInSeconds < 60) {
        return `${diffInSeconds}s`;
    } else if (diffInSeconds < 3600) {
        const minutes = Math.floor(diffInSeconds / 60);
        return `${minutes}m`;
    } else if (diffInSeconds < 86400) {
        const hours = Math.floor(diffInSeconds / 3600);
        return `${hours}h`;
    } else {
        const days = Math.floor(diffInSeconds / 86400);
        return `${days}d`;
    }
}