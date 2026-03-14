// DNI Lookup Integration with apiperu.dev
const APIPERU_TOKEN = '78291d771ba286fabfd992954dd6630cb6a0324cb643b7b372abed2cbb00ee0f';

// Function that accepts DOM elements directly
function lookupDNIFromElement(dniElement, nameElement) {
    const dni = dniElement.value.trim();

    // Validate DNI format
    if (!/^\d{8}$/.test(dni)) {
        alert('Por favor ingresa un DNI válido (8 dígitos)');
        nameElement.value = '';
        return;
    }

    // Show loading state
    const button = event.target;
    const originalText = button.innerText;
    button.disabled = true;
    button.innerText = 'Consultando...';

    // Build URL with query parameters (GET method)
    const apiUrl = `https://apiperu.dev/api/dni/${dni}?api_token=${APIPERU_TOKEN}`;

    fetch(apiUrl, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data && data.data) {
            // Try multiple possible field names for the full name
            const fullName = data.data.nombre_completo 
                || (data.data.nombres && data.data.apellido_paterno 
                    ? `${data.data.nombres} ${data.data.apellido_paterno}`.trim()
                    : data.data.nombre 
                    ? data.data.nombre 
                    : '');

            if (fullName) {
                nameElement.value = fullName;
                console.log('DNI encontrado:', fullName);
                console.log('Datos completos:', data.data);
            } else {
                alert('No se encontraron datos para este DNI');
                nameElement.value = '';
            }
        } else {
            alert('DNI no encontrado. Verifica el número e intenta de nuevo.');
            nameElement.value = '';
        }
    })
    .catch(error => {
        console.error('Error al consultar DNI:', error);
        alert('Error al consultar el DNI. Intenta de nuevo más tarde.');
        nameElement.value = '';
    })
    .finally(() => {
        button.disabled = false;
        button.innerText = originalText;
    });
}
