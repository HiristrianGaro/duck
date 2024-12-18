// Function to fetch cities from the API
async function getCities(city) {
    const url = `https://andruxnet-world-cities-v1.p.rapidapi.com/?query=${city}&searchby=city`;
    const options = {
        method: 'GET',
        headers: {
            'x-rapidapi-key': '1efadd8caamsh3e28043c8ab614cp172224jsn6aa2662a3ee0',
            'x-rapidapi-host': 'andruxnet-world-cities-v1.p.rapidapi.com'
        }
    };

    try {
        const response = await fetch(url, options);
        const result = await response.json(); // Parse the JSON response
        return result;
    } catch (error) {
        console.error('Error fetching cities:', error);
    }
}

// Function to populate the dropdown based on city data
function populateDropdown(data) {
    const dropdown = document.getElementById('myDropdown');

    // Clear any existing options
    dropdown.innerHTML = '';

    // Loop through the city data and create option elements
    data.forEach(item => {
        const option = document.createElement('option');
        option.value = item.City;
        option.text = item.State;
        dropdown.appendChild(option);
    });
}

// Event listener for the input field
document.getElementById('searchInput').addEventListener('input', async function() {
    const searchTerm = this.value;
    if (searchTerm.length >= 3) { // Fetch cities only if the input is at least 3 characters long
        const cityData = await getCities(searchTerm);
        if (cityData && cityData.length > 0) {
            populateDropdown(cityData);
        } else {
            console.log('No cities found');
        }
    } else {
        // Clear the dropdown if input is too short
        document.getElementById('myDropdown').innerHTML = '';
    }
});
