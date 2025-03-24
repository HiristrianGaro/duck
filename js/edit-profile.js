$(document).ready(() => {
    const params = new URLSearchParams(window.location.search);
    const username = params.get('username');
    if (username) {
        fetchProfileData('username');
    } else {
        console.error('No username provided.');
    }
});


function fetchProfileData(username) {
    fetch(`backend/edit-profile.php?term=${encodeURIComponent(username)}`)
        .then(response => response.json())
        .then(userData => {
            console.log('Received profile data:', userData);
            displayProfileData(userData);
        })
        .catch(error => {
            console.error('Error fetching profile data:', error);
        });
}

async function displayProfileData(userData) {
    if (userData.length === 0) {
        console.log(typeof userData);
        console.log('No user data found.');
        return;
    }
    const user = userData[0];
    console.log('Displaying profile data:', user.Username);

    const profileForm = document.getElementById('EditForm');
    console.log(user.Fotoprofilo);
    document.getElementById['CurrentProfileImage'].src = user.Fotoprofilo;
    profileForm.elements['EditUsername'].value = user.Username;
    profileForm.elements['EditName'].value = user.Nome;
    profileForm.elements['EditSurname'].value = user.Cognome;
    profileForm.elements['EditDob'].value = user.DataDiNascita;

    

}