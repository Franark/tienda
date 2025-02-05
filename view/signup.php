<header>
    <h1>Registro de Usuario</h1>
</header>
<form id="signupForm" action="controller/registroControlador.php" method="POST">
    <input type="text" name="nickname" id="nickname" placeholder="Nombre de usuario">
    <br>
    <input type="email" name="email" id="email" placeholder="Correo Electronico">
    <br>
    <input type="password" name="password" id="password" placeholder="Contraseña">
    <br>
    <input type="password" name="passwordConf" id="passwordConf" placeholder="Repetir Contraseña">
    <br>

    <button type="submit">Registrar</button>
</form>
<script src="assets/javascript/signup.js"></script>