<header>
    <h1>Olvido de Contraseña</h1>
</header>
<main>
    <form id="olvidoContrasenaForm" action="controller/olvidoPasswordControlador.php" method="post">
        <label for="email">Ingrese su email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Enviar</button>
    </form>
</main>

<script src="assets/javascript/olvidoPassword.js"></script>