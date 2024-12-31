const countrySelect = document.getElementById('country');
const stateSelect = document.getElementById('state');
const citySelect = document.getElementById('city');

const API_KEY = 'NHhvOEcyWk50N2Vna3VFTE00bFp3MjFKR0ZEOUhkZlg4RTk1MlJlaA==';
const BASE_URL = 'https://api.countrystatecity.in/v1';

/**
 * Fetch data from the API.
 * @param {string} endpoint - The API endpoint.
 * @returns {Promise<Array>} - A promise that resolves to the data array.
 */
const fetchData = async (endpoint) => {
    const response = await fetch(`${BASE_URL}${endpoint}`, {
        headers: { 'X-CSCAPI-KEY': API_KEY },
    });
    return await response.json();
};

/**
 * Populate a select element with options.
 * @param {HTMLElement} selectElement - The select element to populate.
 * @param {Array} data - The data array to populate options from.
 * @param {string} valueKey - The key for the option value.
 * @param {string} textKey - The key for the option text.
 */
const populateSelect = (selectElement, data, valueKey, textKey) => {
    selectElement.innerHTML = '';
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.disabled = true;
    defaultOption.selected = true;
    defaultOption.textContent = 'Select a country';
    selectElement.appendChild(defaultOption);

    data.forEach((item) => {
        const option = document.createElement('option');
        option.value = item[valueKey];
        option.textContent = item[textKey];
        selectElement.appendChild(option);
    });

    selectElement.disabled = false;
};


fetchData('/countries')
    .then((countries) => populateSelect(countrySelect, countries, 'iso2', 'name'))
    .catch((error) => console.error('Error loading countries:', error));


countrySelect.addEventListener('change', function () {
    const countryCode = this.value;
    stateSelect.innerHTML = '<option value="" disabled selected>Select a state</option>';
    citySelect.innerHTML = '<option value="" disabled selected>Select a city</option>';
    stateSelect.disabled = true;
    citySelect.disabled = true;

    if (countryCode) {
        fetchData(`/countries/${countryCode}/states`)
            .then((states) => populateSelect(stateSelect, states, 'iso2', 'name'))
            .catch((error) => console.error('Error loading states:', error));
    }
});


stateSelect.addEventListener('change', function () {
    const countryCode = countrySelect.value;
    const stateCode = this.value;
    citySelect.innerHTML = '<option value="" disabled selected>Select a city</option>';
    citySelect.disabled = true;

    if (stateCode) {
        fetchData(`/countries/${countryCode}/states/${stateCode}/cities`)
            .then((cities) => populateSelect(citySelect, cities, 'name', 'name'))
            .catch((error) => console.error('Error loading cities:', error));
    }
});
