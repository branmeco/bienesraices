<?php
function conectarDB(): mysqli{
    $db = mysqli_connect('localhost', 'root', 'Aa8ge0050', 'bienesraices_crud');

    if(!$db){
        echo "Error no se pudo conectar";
        exit;
    }
    return $db; //instacia de la conexión
}