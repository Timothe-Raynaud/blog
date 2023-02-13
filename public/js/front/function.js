function showFlashMessage(message, type) {
    let flashContainer = document.createElement("div");
    flashContainer.className = "flash alert-" + type;
    flashContainer.innerHTML = message;
    document.body.append(flashContainer);

    setTimeout(function() {
        flashContainer.style.display = "none";
    }, 5000);
}