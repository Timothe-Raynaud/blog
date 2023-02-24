const loginField = document.getElementById('isAvailableLogin')
loginField.addEventListener('input', function (){
    const value = loginField.value
    const xmlHttpRequest = new XMLHttpRequest()
    xmlHttpRequest.addEventListener('load', function () {
        const response = JSON.parse(xmlHttpRequest.responseText.trim())
        console.log(response)
        if(response.exist == 'true'){
            loginField.style.backgroundColor = 'rgba(168, 55, 55, 0.28)'
        } else {
            loginField.style.backgroundColor = 'rgba(53, 159, 53, 0.28)'
        }
    })

    xmlHttpRequest.open('GET', window.location.origin + '/is-login-exist?' + encodeURIComponent(value))
    xmlHttpRequest.responseType = 'text'

    xmlHttpRequest.send()
})

const usernameField = document.getElementById('isAvailableUsername')
usernameField.addEventListener('input', function (){
    const value = usernameField.value
    const xmlHttpRequest = new XMLHttpRequest()
    xmlHttpRequest.addEventListener('load', function () {
        const response = JSON.parse(xmlHttpRequest.responseText.trim())
        console.log(response)
        if(response.exist == 'true'){
            usernameField.style.backgroundColor = 'rgba(168, 55, 55, 0.28)'
        } else {
            usernameField.style.backgroundColor = 'rgba(53, 159, 53, 0.28)'
        }
    })

    xmlHttpRequest.open('GET', window.location.origin + '/is-username-exist?' + encodeURIComponent(value))
    xmlHttpRequest.responseType = 'text'

    xmlHttpRequest.send()
})