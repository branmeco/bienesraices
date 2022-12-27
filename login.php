<?php

//Conexión de la base de datos
require 'includes/config/database.php';
$db = conectarDB();

//Autenticar el usuario
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (!$email) {
        $errores[] = "El email es obligatorio o no es válido";
    }
    if (!$password) {
        $errores[] = "El password es válido";
    }
}

//Incluye el header
require 'includes/funciones.php';
incluirTemplate('header');
?>

<main class="contenedor seccion contenido-centrado">
    <h1>Iniciar sección</h1>
    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form method="POST" class="formulario" novalidate>
        <fieldset>
            <legend>Email y password</legend>

            <label for="email">E-mail</label>
            <input type="email" name="email" placeholder="Tu E-mail" id="email">

            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Tu password" id="password">
        </fieldset>
        <input type="submit" value="Iniciar Sesión" class="boton boton-verde">
    </form>
</main>

<?php
incluirTemplate('footer');
?>