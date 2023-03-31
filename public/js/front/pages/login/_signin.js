const loginField = document.getElementById('is-available-login')
const usernameField = document.getElementById('is-available-username')

loginField.addEventListener('input', () => testDisponibilityOfField(loginField, 'login', 'sign-in-submit'))
usernameField.addEventListener('input', () => testDisponibilityOfField(usernameField, 'username', 'sign-in-submit'))

testAvailabilityOfPassword('password-signin-field', 'password-signin-availability', 'sign-in-submit')


