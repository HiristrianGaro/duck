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

function postProfileData(username) {
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

async function displayProfileData(data) {
    if (data.length === 0) {
        console.log(typeof data);
        console.log('No user data found.');
        return;
    }
    const user = data[0];
    console.log('Displaying profile data:', user.Username);

    const profileForm = document.getElementById('EditForm');
    console.log(user.PosizioneFileSystemFotoProf);
    document.getElementById('CurrentProfileImage').src = user.PosizioneFileSystemFotoProf;
    profileForm.elements['EditUsername'].value = user.Username;
    profileForm.elements['EditName'].value = user.Nome;
    profileForm.elements['EditSurname'].value = user.Cognome;
    console.log(profileForm.elements['EditDoB']);
    profileForm.elements['EditDoB'].value = user.DataDiNascita;

    

}

function previewImage(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        document.getElementById('CurrentProfileImage').src = e.target.result;
        document.getElementById('imgstatus').innerText = 'New image selected';
    };

    if (file) {
        reader.readAsDataURL(file);
    }
}

document.getElementById("EditForm").onsubmit = function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    console.log('Form data:', formData);

    fetch('backend/edit-profile.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        console.log('Profile updated successfully:', data);
        alert('Profile updated successfully!');
        window.location.reload();
    })
    .catch(error => {
        console.error('Error updating profile:', error);
        alert('Error updating profile. Please try again.');
    });
}