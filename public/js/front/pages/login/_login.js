const formConnection = document.getElementById('form-connection')
const formSubmitConnection = document.getElementById('form-submit-connection')

formConnection.addEventListener('submit', (event) => {
    event.preventDefault()
    formSubmitConnection.disabled = true

    const loginInput = document.querySelector('input[name=login]')
    const passwordInput = document.querySelector('input[name=password]')

    const formDataConnection = new FormData()
    formDataConnection.append('login', loginInput.value)
    formDataConnection.append('password', passwordInput.value)

    fetch('/connexion', {
        method: 'POST',
        body: formDataConnection
    })
        .then(response => {
            if (response.ok) {
                return response.json()
            } else {
                throw new Error('Erreur de connexion')
            }
        })
        .then(data => {
            if (data.isConnecting) {
                window.location.href = '/my-account'
            } else {
                showFlashMessage('login-flash', data.message, 'danger')
            }
        })
        .catch(error => console.error(error))
        .finally(() => {
            setTimeout(() => {
                formSubmitReset.disabled = false
            }, 2000)
        })
})