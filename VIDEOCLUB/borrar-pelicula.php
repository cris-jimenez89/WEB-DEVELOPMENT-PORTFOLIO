<?php
include 'db-funciones.php';

$pdo = conectarBaseDatos();

// Obtener el ID de la película desde la URL
$filmId = $_GET['id']; // El ID de la película se pasa por la URL

// Verificar si el ID está presente
if (isset($filmId)) {
    // Llamar a la función para eliminar la película
    if (eliminarPelicula($pdo, $filmId)) {
        // Si se elimina correctamente, redirigir a la página de gestión de películas
        header("Location: film.php");
        exit;
    } else {
        // Si no se puede eliminar, mostrar un mensaje de error ESTE ES EL QUE MUESTRA
        echo "<h1>Error al borrar la película.</h1>";
    }
} else {
    // Si no se pasa el ID de la película (asi comprobamos el error)
    echo "<h1>ID de película no válido.</h1>";
}

