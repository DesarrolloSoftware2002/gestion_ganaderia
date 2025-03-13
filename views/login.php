<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Gestion Datos Ganaderia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <div class="container">
        <div class="login-box">
            <h2>Iniciar Sesión</h2>
            <form action="../config/controlador.php" method="post">
                <input type="text" name="user" placeholder="Usuario" required><br>
                <input type="password" name="pass" placeholder="Contraseña" required><br>
                <button type="submit">Login</button>
            </form>
            <?php if (isset($_GET['error']) && $_GET['error'] == 1) : ?>
                <p id="error-message" class="error-message">Datos incorrectos. Por favor, inténtalo de nuevo.</p>
                <script>
                    setTimeout(function() {
                        var errorMessage = document.getElementById('error-message');
                        if (errorMessage) {
                            errorMessage.style.display = 'none';
                        }
                    }, 5000);
                </script>
            <?php endif; ?>
        </div>
        <button class="info-button"><i class="fas fa-info"></i><span class="info-tooltip">Información sobre este sitio</span></button>
    </div>
</body>

</html>
