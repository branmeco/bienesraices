<?php
//Importar la conexión
require 'includes/config/database.php';
$db = conectarDB();

//Crear un email y password
$email = 'correo@correo.com';
$password = '12345';

$passwordHash = password_hash($password, PASSWORD_BCRYPT); 

//Query para crear el usuario
$query = "INSERT INTO usuarios(email, password) VALUES('${email}', '${password}')";

echo $query;

//Agregar a la base de datos
mysqli_query($db, $query);

?>