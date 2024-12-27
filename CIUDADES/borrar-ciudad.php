<?php
// Incluir el archivo de funciones de búsqueda
include 'db-functions.php';

// Conexión a la base de datos
$pdo = conectarBaseDatos();

// Obtener el ID de la ciudad desde la URL
$cityId = $_GET['id'];

// Borrar la ciudad y redirigir
if (borrarCiudad($pdo, $cityId)) {
    header("Location: ciudades-de-un-pais-mejor-ampliacion-2.php");
    exit;
} else {
    echo "<h1>Error al borrar la ciudad.</h1>";
}

?>