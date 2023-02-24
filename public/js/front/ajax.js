const signInSubmit = document.getElementById('sign-in-submit')

const loginField = document.getElementById('is-available-login')
loginField.addEventListener('input', function (){
    const value = loginField.value
    const xmlHttpRequest = new XMLHttpRequest()
    xmlHttpRequest.addEventListener('load', function () {
        const availability = document.getElementById('login-availability')
        const response = JSON.parse(xmlHttpRequest.responseText.trim())
        if(value === ''){
            loginField.style.backgroundColor = '#fff'
            availability.innerText = ''
        } else if(response.exist === 'true'){
            loginField.style.backgroundColor = 'rgba(159, 0, 0, 0.13)'
            availability.innerText = 'Non disponible'
        } else {
            loginField.style.backgroundColor = 'rgba(0, 169, 96, 0.13)'
            availability.innerText = 'Disponible'
        }
    })

    xmlHttpRequest.open('GET', window.location.origin + '/is-login-exist?' + encodeURIComponent(value))
    xmlHttpRequest.responseType = 'text'

    xmlHttpRequest.send()
})

const usernameField = document.getElementById('is-available-username')
usernameField.addEventListener('input', function (){
    const value = usernameField.value
    const xmlHttpRequest = new XMLHttpRequest()
    xmlHttpRequest.addEventListener('load', function () {
        const availability = document.getElementById('username-availability')
        const response = JSON.parse(xmlHttpRequest.responseText.trim())
        if(value === ''){
            usernameField.style.backgroundColor = '#fff'
            availability.innerText = ''
        } else if(response.exist === 'true'){
            usernameField.style.backgroundColor = 'rgba(159, 0, 0, 0.13)'
            availability.innerText = 'Non disponible'
        } else {
            usernameField.style.backgroundColor = 'rgba(0, 169, 96, 0.13)'
            availability.innerText = 'Disponible'
        }
    })

    xmlHttpRequest.open('GET', window.location.origin + '/is-username-exist?' + encodeURIComponent(value))
    xmlHttpRequest.responseType = 'text'

    xmlHttpRequest.send()
})