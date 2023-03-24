const formMail = document.getElementById('form-sendmail')
const formSubmitContact = document.getElementById('form-submit-contact')

formMail.addEventListener('submit', (event) => {
    event.preventDefault()
    formSubmitContact.disabled = true


    const emailInput = document.querySelector('input[name=email]')
    const subjectInput = document.querySelector('input[name=subject]')
    const messageInput = document.querySelector('textarea[name=message]')

    const formDataMail = new FormData()
    formDataMail.append('email', emailInput.value)
    formDataMail.append('subject', subjectInput.value)
    formDataMail.append('message', messageInput.value)

    fetch('/sendmail', {
        method: 'POST',
        body: formDataMail
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
                showFlashMessage('home-flash', data.message, 'success')
            } else {
                showFlashMessage('home-flash', data.message, 'danger')
            }
        })
        .catch(error => console.error(error))
        .finally(() => {
            setTimeout(() => {
                formSubmitContact.disabled = false
            }, 2000)
        })
})