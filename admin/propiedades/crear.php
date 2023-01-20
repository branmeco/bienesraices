<?php

require '../../includes/app.php';

//Importar Intervention Image
use Intervention\Image\ImageManagerStatic as Image;
use App\Propiedad;

/* estaAutenticado(); */

$propiedad = new Propiedad;

// Consultar para obtener los vendedores
$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

// Arreglo con mensajes de errores
$errores = Propiedad::getErrores();

// Ejecutar el código después de que el usuario envia el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**Crea una nueva instancia */
    $propiedad = new Propiedad($_POST['propiedad']);

    // Generar un nombre único
    $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

    //Setar la nueva imagen
    // Realiza un resize a la imagen con intervetion
    if ($_FILES['propiedad']['tmp_name']['imagen']) {
        $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
        $propiedad->setImagen($nombreImagen);
    }

    //Validar
    $errores = $propiedad->validar();

    // Revisar que el array de errores este vacio

    if (empty($errores)) {

        /** SUBIDA DE ARCHIVOS */
        // Crear carpeta
        if (!is_dir(CARPETAS_IMAGENES)) {
            mkdir(CARPETAS_IMAGENES);
        }

        //Guarda la imagen en el servidor
        $image->save(CARPETAS_IMAGENES . $nombreImagen);

        //Guarda en la base de datos
        $resultado = $propiedad->guardar();

        //Mensaje de exito y error
        if ($resultado) {
            // Redireccionar al usuario.
            header('Location: /admin?resultado=1');
        }
    }
}

incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Crear</h1>
    <a href="/admin" class="boton boton-verde">Volver</a>
    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>
    <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
        <?php include '../../includes/templates/formulario_propiedades.php'; ?>
        <input type="submit" value="Crear Propiedad" class="boton boton-verde">
    </form>
</main>
<?php
incluirTemplate('footer');
?>