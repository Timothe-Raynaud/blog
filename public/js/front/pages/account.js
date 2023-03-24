const formUpdateAccount = document.getElementById('form-update-account')
const formSubmitUpdateAccount = document.getElementById('form-submit-update-account')

formUpdateAccount.addEventListener('submit', (event) => {
    event.preventDefault()
    formSubmitUpdateAccount.disabled = true


    const usernameInput = document.querySelector('input[name=username]')
    const emailInput = document.querySelector('input[name=email]')
    const loginInput = document.querySelector('input[name=login]')

    const formDataAccount = new FormData()
    formDataAccount.append('userId', userId)
    formDataAccount.append('username', usernameInput.value)
    formDataAccount.append('email', emailInput.value)
    formDataAccount.append('login', loginInput.value)

    fetch('/update-account', {
        method: 'POST',
        body: formDataAccount
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
                showFlashMessage('update-account-flash', data.message, 'success')
            } else {
                showFlashMessage('update-account-flash', data.message, 'danger')
            }
        })
        .catch(error => console.error(error))
        .finally(() => {
            setTimeout(() => {
                formSubmitUpdateAccount.disabled = false
            }, 2000)
        })
})

const formUpdatePassword = document.getElementById('form-update-password')
const formSubmitUpdatePassword = document.getElementById('form-submit-update-password')

formUpdatePassword.addEventListener('submit', (event) => {
    event.preventDefault()
    formSubmitUpdatePassword.disabled = true


    const password = document.querySelector('input[name=password]')
    const firstNewPassword = document.querySelector('input[name=first_new_password]')
    const secondNewPassword = document.querySelector('input[name=second_new_password]')

    const formDataAccount = new FormData()
    formDataAccount.append('userId', userId)
    formDataAccount.append('password', password.value)
    formDataAccount.append('firstNewPassword', firstNewPassword.value)
    formDataAccount.append('secondNewPassword', secondNewPassword.value)

    fetch('/update-password', {
        method: 'POST',
        body: formDataAccount
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
                showFlashMessage('update-account-flash', data.message, 'success')
            } else {
                showFlashMessage('update-account-flash', data.message, 'danger')
            }
        })
        .catch(error => console.error(error))
        .finally(() => {
            setTimeout(() => {
                formSubmitUpdatePassword.disabled = false
            }, 2000)
        })
})