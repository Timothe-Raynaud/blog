const formUpdateRole = document.getElementById('form-update-role')
const formSubmitUpdateRole = document.getElementById('form-submit-update-role')

formUpdateRole.addEventListener('submit', (event) => {
    event.preventDefault()
    formSubmitUpdateRole.disabled = true


    const role = document.querySelector('select[name=role]')
    const login = document.querySelector('input[name=login]')

    const formDataRole = new FormData()
    formDataRole.append('role', role.value)
    formDataRole.append('login', login.value)

    fetch('/update-role', {
        method: 'POST',
        body: formDataRole
    })
        .then(response => {
            if (response.ok) {
                return response.json()
            } else {
                throw new Error('Erreur de connexion')
            }
        })
        .then(data => {
            if (data.isUpdate) {
                const selectRole = role.options[role.selectedIndex]
                const displayRole = document.getElementById('role-' + login.value)
                displayRole.innerText = selectRole.textContent
                showFlashMessage('users-back-flash', data.message, 'success')
            } else {
                showFlashMessage('users-back-flash', data.message, 'danger')
            }
        })
        .catch(error => console.error(error))
        .finally(() => {
            setTimeout(() => {
                formSubmitUpdateRole.disabled = false
            }, 2000)
        })
})