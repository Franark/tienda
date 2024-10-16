<h1>Registro de Usuario</h1>
<form id="signupForm" action="controller/registroControlador.php" method="POST">
    <label for="nickname">Nickname:</label>
    <input type="text" name="nickname" id="nickname" placeholder="Nombre de usuario">
    <br>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" placeholder="Correo Electronico">
    <br>

    <label for="password">Contraseña:</label>
    <input type="password" name="password" id="password" placeholder="Contraseña">
    <br>

    <label for="passwordConf">Confirmar Contraseña:</label>
    <input type="password" name="passwordConf" id="passwordConf" placeholder="Repetir Contraseña">
    <br>

    <button type="submit">Registrar</button>
</form>
<script src="assets/javascript/signup.js"></script>