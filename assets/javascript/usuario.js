document.getElementById('signupForm').addEventListener('submit', function(event) {
    const nickname = document.getElementById('nickname').value;
    const email = document.getElementById('email').value;

    if (!nickname || !email) {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Todos los campos son obligatorios.',
        });
        return;
    }

    const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
    if (!emailPattern.test(email)) {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'El formato del correo electrónico no es válido.',
        });
        return;
    }
});

const urlParams = new URLSearchParams(window.location.search);
const error = urlParams.get('error');
const success = urlParams.get('success');

if (error) {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: error,
    });
}

if (success) {
    Swal.fire({
        icon: 'success',
        title: 'Éxito!',
        text: success,
    });
}