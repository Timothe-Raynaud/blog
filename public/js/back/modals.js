var userModal = document.getElementById('user-modal')
userModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget
    var username = button.getAttribute('data-bs-username')
    var modalTitle = userModal.querySelector('.modal-title')

    modalTitle.textContent = username
})