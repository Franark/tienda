function validarFormulario() {
    const nickname = document.getElementById('nickname').value;
    const password = document.getElementById('password').value;

    if (nickname === "" || password === "") {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor complete ambos campos',
            confirmButtonText: 'OK'
        });
        return false;
    }

    return true;
}
