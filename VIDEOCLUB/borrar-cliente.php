<?php
include 'db-funciones.php';

$pdo = conectarBaseDatos();

// Obtener el ID de la película desde la URL
$customerId = $_GET['id']; // El ID del cliente se pasa por la URL

if (isset($customerId)) {
    // Llamamos a la función para eliminar EL CLIENTE
    if (eliminarCliente($pdo, $customerId)) {
        // Si se elimina correctamente, redirigir a la página de gestión de CLIENTES
        header("Location: customer.php");
        exit;
    } else {
        // Si no se puede eliminar, mostrar un mensaje de error
        echo "<h1>Error al borrar el cliente.</h1>";
    }
} else {
    // Si no se pasa el ID de la película , ESTE ES EL ERROR EN ESTE CASO, REVISAR ES COMO SI NO SE ENCONTRARA CUSTOMER ID
    echo "<h1>ID de cliente no válido.</h1>";
}
