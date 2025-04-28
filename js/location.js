console.log('hello');

$(document).ready(() => {
    console.log('Initializing location selects...');
    fetchRegions()
        .then(data => populateSelect(regionSelect, data, 'Regione', 'Regione'))
        .catch(error => console.error('Error initializing regions:', error));
    
    provinceSelect.disabled = true;
    citySelect.disabled = true;
});

const regionSelect = document.getElementById('region');
const provinceSelect = document.getElementById('province');
const citySelect = document.getElementById('city');

// Universal populate function
const populateSelect = (selectElement, data, valueKey, textKey) => {
    console.log('Populating select:', selectElement.id, data);
    selectElement.innerHTML = '';
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.disabled = true;
    defaultOption.selected = true;
    defaultOption.textContent = `Select ${selectElement.id}`;
    selectElement.appendChild(defaultOption);

    data.forEach(item => {
        const option = document.createElement('option');
        option.value = item[valueKey];
        option.textContent = item[textKey];
        selectElement.appendChild(option);
    });

    selectElement.disabled = false;
};

// API Fetch Functions
async function fetchRegions() {
    return fetch('backend/getLocation.php?query=Regions')
        .then(response => response.ok ? response.json() : Promise.reject())
        .catch(error => console.error('Error fetching regions:', error));
}

async function fetchProvinces(region) {
    return fetch(`backend/getLocation.php?query=Provinces&Region=${encodeURIComponent(region)}`)
        .then(response => response.ok ? response.json() : Promise.reject())
        .catch(error => console.error('Error fetching provinces:', error));
}

async function fetchCities(region, province) {
    return fetch(`backend/getLocation.php?query=Cities&Region=${encodeURIComponent(region)}&Province=${encodeURIComponent(province)}`)
        .then(response => response.ok ? response.json() : Promise.reject())
        .catch(error => console.error('Error fetching cities:', error));
}

// Event Handlers
regionSelect.addEventListener('change', function() {
    const region = this.value;
    provinceSelect.innerHTML = '<option value="" disabled selected>Select province</option>';
    citySelect.innerHTML = '<option value="" disabled selected>Select city</option>';
    citySelect.disabled = true;
    
    if(region) {
        provinceSelect.disabled = false;
        fetchProvinces(region)
            .then(data => populateSelect(provinceSelect, data, 'Provincia', 'Provincia'))
            .catch(error => console.error('Error loading provinces:', error));
    } else {
        provinceSelect.disabled = true;
    }
});

provinceSelect.addEventListener('change', function() {
    const region = regionSelect.value;
    const province = this.value;
    
    if(region && province) {
        fetchCities(region, province)
            .then(data => populateSelect(citySelect, data, 'Citta', 'Citta'))
            .catch(error => console.error('Error loading cities:', error));
    } else {
        citySelect.disabled = true;
    }
});
