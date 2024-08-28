<h1 class="singup-titulo">Iniciar Sesion</h1>
<form action="controller/loginControlador.php" method="post">
    <i><img src="assets/images/icons8-user-24.png" alt="user" class="icon user"></i>
    <input type="text" name="nickname" id="nickname" placeholder="Nombre">
    <br>
    <i><img src="assets/images/icons8-eye-30.png" alt="eye" class="icon password" id="pass"></i>            
    <input type="password" name="password" id="password" placeholder="Contraseña"><br>
    <a href="./signup.php" class="parrafo">¿Olvidaste tu contraseña?</a>
    <button type="submit" name="submit" onclick="validar()" class="boton">Acceder</button>
    <p class="parrafo">¿No tienes una cuenta? <a href="./?page=signup"><strong>Regístrate</strong></a></p>
</form>
<script src="assets/javascript/login.js"></script>