const formResetMail = document.getElementById('form-mail-reset')
const formSubmitReset = document.getElementById('form-submit-send-reset')

formResetMail.addEventListener('submit', (event) => {
    event.preventDefault()
    formSubmitReset.disabled = true

    const emailInput = document.querySelector('input[name=email]')
    const formDataResetMail = new FormData()
    formDataResetMail.append('email', emailInput.value)

    fetch('/send-reset-password', {
        method: 'POST',
        body: formDataResetMail
    })
        .then(response => {
            if (response.ok) {
                return response.json()
            } else {
                throw new Error('Erreur de connexion')
            }
        })
        .then(data => {
            if (data.isSend) {
                showFlashMessage('login-flash', data.message, 'success')
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