function showFlashMessage(message, type) {
    let flashContainer = document.createElement("div");
    flashContainer.className = "flash alert-" + type;
    flashContainer.innerHTML = message;
    document.body.append(flashContainer);

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
