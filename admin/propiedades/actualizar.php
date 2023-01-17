<?php

use App\Propiedad;

require '../../includes/app.php';

estaAutenticado();

// Validar la URL por ID válido
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /admin');
}

//Validar la URL por Id válido
$propiedad = Propiedad::find($id);

// Consultar para obtener los vendedores
$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

// Arreglo con mensajes de errores
$errores = [];

// Ejecutar el código después de que el usuario envia el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";

    // echo "<pre>";
    // var_dump($_FILES);
    // echo "</pre>";


    $titulo = mysqli_real_escape_string($db,  $_POST['titulo']);
    $precio = mysqli_real_escape_string($db,  $_POST['precio']);
    $descripcion = mysqli_real_escape_string($db,  $_POST['descripcion']);
    $habitaciones = mysqli_real_escape_string($db,  $_POST['habitaciones']);
    $wc = mysqli_real_escape_string($db,  $_POST['wc']);
    $estacionamiento = mysqli_real_escape_string($db,  $_POST['estacionamiento']);
    $vendedorId = mysqli_real_escape_string($db,  $_POST['vendedor']);
    $creado = date('Y/m/d');

    // Asignar files hacia una variable
    $imagen = $_FILES['imagen'];

    if (!$titulo) {
        $errores[] = "Debes añadir un titulo";
    }

    if (!$precio) {
        $errores[] = 'El Precio es Obligatorio';
    }

    if (strlen($descripcion) < 50) {
        $errores[] = 'La descripción es obligatoria y debe tener al menos 50 caracteres';
    }

    if (!$habitaciones) {
        $errores[] = 'El Número de habitaciones es obligatorio';
    }

    if (!$wc) {
        $errores[] = 'El Número de Baños es obligatorio';
    }

    if (!$estacionamiento) {
        $errores[] = 'El Número de lugares de Estacionamiento es obligatorio';
    }

    if (!$vendedorId) {
        $errores[] = 'Elige un vendedor';
    }

    // Validar por tamaño (1mb máximo)
    $medida = 1000 * 1000;
    if ($imagen['size'] > $medida) {
        $errores[] = 'La Imagen es muy pesada';
    }


    // echo "<pre>";
    // var_dump($errores);
    // echo "</pre>";


    // Revisar que el array de errores este vacio

    if (empty($errores)) {

        // Crear carpeta
        $carpetaImagenes = '../../imagenes/';

        if (!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes);
        }

        $nombreImagen = '';

        /** SUBIDA DE ARCHIVOS */

        if ($imagen['name']) {
            // Eliminar la imagen previa

            unlink($carpetaImagenes . $propiedad['imagen']);

            // // Generar un nombre único
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

            // // Subir la imagen
            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);
        } else {
            $nombreImagen = $propiedad['imagen'];
        }

        // Insertar en la base de datos
        $query = " UPDATE propiedades SET titulo = '${titulo}', precio = '${precio}', imagen = '${nombreImagen}', descripcion = '${descripcion}', habitaciones = ${habitaciones}, wc = ${wc}, estacionamiento = ${estacionamiento}, vendedorId = ${vendedorId} WHERE id = ${id} ";

        $resultado = mysqli_query($db, $query);

        if ($resultado) {
            // Redireccionar al usuario.
            header('Location: /admin?resultado=2');
        }
    }
}



incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Actualizar Propiedad</h1>

    <a href="/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" enctype="multipart/form-data">
        <?php include '../../includes/templates/formulario_propiedades.php'; ?>
        <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
    </form>

</main>

<?php
incluirTemplate('footer');
?>