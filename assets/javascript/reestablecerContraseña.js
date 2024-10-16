document.getElementById('resetForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const nuevaContraseña = document.getElementById('nuevaContraseña').value;
    const confirmarContraseña = document.getElementById('confirmarContraseña').value;
    const letraYNumero = /^(?=.*[a-zA-Z])(?=.*\d)/;
    if (!nuevaContraseña || !confirmarContraseña) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Ambos campos de contraseña son obligatorios.',
        });
        return;
    }
    if (nuevaContraseña !== confirmarContraseña) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Las contraseñas no coinciden.',
        });
        return;
    }
    if (nuevaContraseña.length < 8) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'La contraseña debe tener al menos 8 caracteres.',
        });
        return;
    }
    if (!letraYNumero.test(nuevaContraseña)) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'La contraseña debe contener al menos una letra y un número.',
        });
        return;
    }
    this.submit();
});