<?php
include 'db-funciones.php';

$pdo = conectarBaseDatos();

// Obtener el ID del alquiler desde la URL
$rental_id = $_GET['rental_id'] ?? null;

if ($rental_id) {
    // Llamamos a la función para eliminar el alquiler
    if (eliminarAlquiler($pdo, $rental_id)) {
        echo "<p>Alquiler eliminado con éxito.</p>";
        header("Location: rental.php"); // Redirigir a la página de alquileres
        exit;
    } else {
        echo "<h1>Error al eliminar el alquiler.</h1>";
    }
} else {
    echo "<h1>ID de alquiler no válido.</h1>";
}
?>
