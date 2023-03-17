var userModal = document.getElementById('user-modal')
userModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget

    var username = button.getAttribute('data-bs-username')
    var login = button.getAttribute('data-bs-login')
    var role = button.getAttribute('data-bs-role')

    var modalTitle = userModal.querySelector('.modal-user-title')
    var modalLogin = userModal.querySelector('.modal-user-login')
    var modalUsername = userModal.querySelector('.modal-user-username')
    var modalRole = userModal.querySelector('.modal-user-role')

    modalTitle.textContent = username
    modalLogin.value = login
    modalUsername.value = username
})