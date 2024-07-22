document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

    const eyeIcon = document.getElementById('eyeIcon');
    eyeIcon.alt = type === 'password' ? 'Mostrar' : 'Ocultar';
});
