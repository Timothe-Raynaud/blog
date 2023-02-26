function showFlashMessage(id, message, type) {
    let flashContainer = document.getElementById(id);
    flashContainer.innerHTML = message;
    flashContainer.classList.remove('d-none');
    flashContainer.className = "flash alert-" + type;

    setTimeout(function() {
        flashContainer.classList.add('d-none');
    }, 5000);
}

function hideAndShow(hideId, showId){
    let hideTarget = document.getElementById(hideId)
    let showTarget = document.getElementById(showId)
    hideTarget.classList.add('d-none')
    showTarget.classList.remove('d-none')
}

function logout(){
    fetch('/logout')
        .then(response => {
            if (response.ok) {
                window.location.href = '/';
            } else {
                throw new Error('Erreur de connexion');
            }
        })
        .catch(error => console.error(error))
}