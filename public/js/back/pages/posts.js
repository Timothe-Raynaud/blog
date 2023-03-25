// Posts Modal
const postModal = document.getElementById('post-modal')
postModal.addEventListener('show.bs.modal', function (event) {
    let button = event.relatedTarget

    let title = button.getAttribute('data-bs-title')
    let content = button.getAttribute('data-bs-content')
    let id = button.getAttribute('data-bs-id')

    let modalTitle = postModal.querySelector('.modal-title')
    let modalContent = postModal.querySelector('.post-modal-content')

    modalTitle.textContent = title
    modalContent.textContent = content

})