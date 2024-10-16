document.getElementById('olvidoContrasenaForm').addEventListener('submit', function(event) {
    const email = document.getElementById('email').value;
    if (!email) {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'El campo de email es obligatorio.',
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
    event.preventDefault();
    $.ajax({
        url: 'controller/validarEmail.php',
        type: 'POST',
        data: { email: email },
        success: function(response) {
            if (response === 'ok') {
                document.getElementById('olvidoContrasenaForm').submit();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El correo no está registrado en el sistema.',
                });
            }
        }
    });
});