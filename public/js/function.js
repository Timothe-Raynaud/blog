function showFlashMessage(id, message, type) {
    let flashContainer = document.getElementById(id);
    flashContainer.innerHTML = message;
    flashContainer.classList.remove('d-none');
    flashContainer.className = "flash alert-" + type;

    setTimeout(function () {
        flashContainer.classList.add('d-none');
    }, 4000);
}

function hideAndShow(hideId, showId) {
    let hideTarget = document.getElementById(hideId)
    let showTarget = document.getElementById(showId)
    hideTarget.classList.add('d-none')
    showTarget.classList.remove('d-none')
}

function logout() {
    fetch('/logout')
        .then(response => {
            if (response.ok) {
                window.location.href = '/';
            } else {
                throw new Error('Erreur');
            }
        })
        .catch(error => console.error(error))
}

function testDisponibilityOfField(element, name, targetValidationId) {
    const formSubmitReset = document.getElementById(targetValidationId)
    const value = element.value

    fetch(window.location.origin + '/is-' + name + '-exist?' + encodeURIComponent(value))
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Erreur de connexion');
            }
        })
        .then(data => {
            const availability = document.getElementById(name + '-availability')

            if (value === '') {
                element.style.backgroundColor = '#fff'
                availability.innerText = ''
                formSubmitReset.disabled = true
            } else if (data) {
                element.style.backgroundColor = 'rgba(159, 0, 0, 0.13)'
                availability.innerText = 'Non disponible'
                formSubmitReset.disabled = true
            } else {
                element.style.backgroundColor = 'rgba(0, 169, 96, 0.13)'
                availability.innerText = 'Disponible'
                formSubmitReset.disabled = false
            }
        })
        .catch(error => console.error(error))
}

function testAvailabilityOfPassword(fieldName, availabilityName, formSubmitName){

    const passwordField = document.getElementById(fieldName)

    passwordField.addEventListener("input", (event) =>{
        const formSubmit = document.getElementById(formSubmitName)
        const value = passwordField.value
        const availability = document.getElementById(availabilityName)

        if (value === '') {
            passwordField.style.backgroundColor = '#fff'
            availability.innerText = ''
            formSubmit.disabled = true
        } else if (value.length < 8) {
            passwordField.style.backgroundColor = 'rgba(159, 0, 0, 0.13)'
            availability.innerText = 'Votre mots de passe doit contenir au moins 8 character.'
            formSubmit.disabled = true
        } else if (!(/\d/.test(value))){
            passwordField.style.backgroundColor = 'rgba(159, 0, 0, 0.13)'
            availability.innerText = 'Votre mots de passe doit contenir au moins 1 charactere numérique.'
            formSubmit.disabled = true
        } else{
            passwordField.style.backgroundColor = 'rgba(0, 169, 96, 0.13)'
            availability.innerText = ''
            formSubmit.disabled = false
        }
    })

}
