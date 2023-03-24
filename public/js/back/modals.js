var userModal = document.getElementById('user-modal')
userModal.addEventListener('show.bs.modal', function (event) {
    let button = event.relatedTarget

    let username = button.getAttribute('data-bs-username')
    let login = button.getAttribute('data-bs-login')
    let email = button.getAttribute('data-bs-email')
    let role = button.getAttribute('data-bs-role')

    let modalTitle = userModal.querySelector('.modal-title')
    let modalLogin = userModal.querySelector('.modal-login')
    let modalUsername = userModal.querySelector('.modal-username')
    let modalEmail = userModal.querySelector('.modal-email')
    let modalRoles = userModal.querySelector('.modal-role')

    modalTitle.textContent = username
    modalLogin.value = login
    modalUsername.value = username
    modalEmail.value = email


    for (let option of modalRoles.options){
        if ( option.value === role){
            option.setAttribute('selected', 'selected')
        }
    }
})