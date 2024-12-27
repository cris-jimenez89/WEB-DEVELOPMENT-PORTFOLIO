<?php
include 'db-funciones.php';

$pdo = conectarBaseDatos();

if (isset($_GET['id'])) {
    $customerId = $_GET['id']; // Obtener el ID del cliente desde la URL
    // Obtener los detalles del cliente por el ID
    $cliente = obtenerClientePorId($pdo, $customerId); // Cambia esto para obtener el cliente desde la base de datos

    // Si el formulario se ha enviado, actualizar el cliente
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
        $data = [
            'customer_id' => $_POST['customer_id'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'email' => $_POST['email'],
            'address_id' => $_POST['address_id'],
            'active' => $_POST['active'],
        ];
        // Llamada a la función de actualización
        actualizarCliente($pdo, $data);
        $mensaje = "Cliente actualizado con éxito.";
        header("Location: customer.php"); // Redirigir a la lista de clientes
        exit;
    }
} else {
    echo "ID de cliente no proporcionado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Editar Cliente</h1>

        <?php if (isset($mensaje)): ?>
            <p><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <!-- Formulario para editar el cliente -->
        <?php if ($cliente): ?>
            <form method="post" action="">
                <input type="hidden" name="customer_id" value="<?= htmlspecialchars($cliente['customer_id']) ?>">
                <label>Nombre:</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($cliente['first_name']) ?>" required>

                <label>Apellido:</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($cliente['last_name']) ?>" required>

                <label>Correo Electrónico:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($cliente['email']) ?>" required>

                <label>ID de Dirección:</label>
                <input type="number" name="address_id" value="<?= htmlspecialchars($cliente['address_id']) ?>" required>

                <label>Estado (Activo):</label>
                <select name="active" required>
                    <option value="1" <?= $cliente['active'] == 1 ? 'selected' : '' ?>>Activo</option>
                    <option value="0" <?= $cliente['active'] == 0 ? 'selected' : '' ?>>Inactivo</option>
                </select>

                <button type="submit" name="actualizar">Actualizar Cliente</button>
            </form>
        <?php else: ?>
            <p>No se encontró el cliente.</p>
        <?php endif; ?>

    </div>
</body>
</html>
