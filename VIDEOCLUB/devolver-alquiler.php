<?php
include 'db-funciones.php';

$pdo = conectarBaseDatos();

if (isset($_GET['id'])) {
    $rentalId = $_GET['id']; // Obtener el ID del alquiler desde la URL
    // Obtener los detalles del alquiler por el ID
    $alquiler = obtenerAlquilerPorId($pdo, $rentalId); // Cambia esto para obtener el alquiler desde la base de datos

    // Si el formulario se ha enviado, actualizar el alquiler
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
        $data = [
            'rental_id' => $_POST['rental_id'],
            'return_date' => $_POST['return_date'], // La nueva fecha de devolución
        ];
        // Llamada a la función de actualización
        if (actualizarDevolucion($pdo, $data)) {
            $mensaje = "Devolución registrada con éxito.";
            header("Location: rental.php"); // Redirigir a la lista de alquileres
            exit;
        } else {
            $mensaje = "Hubo un error al registrar la devolución.";
        }
    }
} else {
    echo "ID de alquiler no proporcionado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Devolución</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Registrar Devolución de Alquiler</h1>

        <?php if (isset($mensaje)): ?>
            <p><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <!-- Formulario para registrar la devolución -->
        <?php if ($alquiler): ?>
            <form method="post" action="">
                <input type="hidden" name="rental_id" value="<?= htmlspecialchars($alquiler['rental_id']) ?>">

                <label>Fecha de Devolución:</label>
                <input type="datetime-local" name="return_date" value="<?= htmlspecialchars($alquiler['return_date']) ?>" required>

                <button type="submit" name="actualizar">Actualizar Devolución</button>
            </form>
        <?php else: ?>
            <p>No se encontró el alquiler.</p>
        <?php endif; ?>
    </div>
</body>
</html>
