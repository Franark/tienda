
        <header>
            <h1>Crear Detalle de Domicilio</h1>
        </header>
        <main>
            <?php if (isset($_GET['error'])): ?>
                <p style="color: red;"><?php echo $_GET['error']; ?></p>
            <?php endif; ?>
            <form action="../controller/domicilioDetalleControlador.php" method="POST">
                <input type="hidden" name="domicilio_idDomicilio" value="<?php echo $_GET['idDomicilio']; ?>">
                <div>
                    <label for="valor">Valor:</label>
                    <input type="text" id="valor" name="valor" required>
                </div>
                <!-- Aquí podrías añadir más campos según los atributos de domicilio que desees manejar -->
                <div>
                    <button type="submit" name="crear">Crear Detalle de Domicilio</button>
                </div>
            </form>
        </main>