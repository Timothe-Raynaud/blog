const formResetPassword = document.getElementById('form-reset-password')
const formSubmitResetPassword = document.getElementById('form-submit-reset-password')

formResetPassword.addEventListener('submit', (event) => {
    event.preventDefault()
    formSubmitResetPassword.disabled = true

    const firstPassword = document.querySelector('input[name=first-password]')
    const secondPassword = document.querySelector('input[name=second-password]')

    const formDataResetPassword = new FormData()
    formDataResetPassword.append('firstPassword', firstPassword.value)
    formDataResetPassword.append('secondPassword', secondPassword.value)
    formDataResetPassword.append('userId', '{{ user.user_id }}')
    formDataResetPassword.append('token', '{{ token }}')

    fetch('/reset-password', {
        method: 'POST',
        body: formDataResetPassword
    })
        .then(response => {
            if (response.ok) {
                return response.json()
            } else {
                throw new Error('Erreur de connexion')
            }
        })
        .then(data => {
            if (data.isReset) {
                showFlashMessage('reset-flash', data.message, 'success')
                setTimeout(() => {
                    window.location.href = '/login'
                }, 2000)
            } else {
                showFlashMessage('reset-flash', data.message, 'danger')
            }
        })
        .catch(error => console.error(error))
        .finally(() => {
            setTimeout(() => {
                formSubmitResetPassword.disabled = false
            }, 2000)
        })
})