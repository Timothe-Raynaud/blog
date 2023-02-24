function showFlashMessage(type, id) {
    let flashContainer = document.getElementById(id);
    flashContainer.className = "flash alert-" + type;

    setTimeout(function() {
        flashContainer.style.display = "none";
    }, 5000);
}

function hideAndShow(hideId, showId){
    let hideTarget = document.getElementById(hideId)
    let showTarget = document.getElementById(showId)
    hideTarget.classList.add('d-none')
    showTarget.classList.remove('d-none')
}
