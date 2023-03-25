// Posts Modal
const postModal = document.getElementById('post-modal')
postModal.addEventListener('show.bs.modal', function (event) {
    let button = event.relatedTarget

    let title = button.getAttribute('data-bs-title')
    let chapo = button.getAttribute('data-bs-chapo')
    let content = button.getAttribute('data-bs-content')
    let id = button.getAttribute('data-bs-id')

    let modalTitle = postModal.querySelector('.modal-title')
    let modalContent = postModal.querySelector('.post-modal-content')
    let modalChapo = postModal.querySelector('.post-modal-chapo')

    modalTitle.textContent = title
    modalChapo.innerText = chapo
    modalContent.textContent = content

})
