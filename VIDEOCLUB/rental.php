<?php
include 'db-funciones.php';

$pdo = conectarBaseDatos();

$alquileres = obtenerAlquileres($pdo);
$mensaje = '';


// PARA CREAR ALQUILER SIN SCRIPT EXTRA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_alquiler'])) {
    // Extraemos los datos del formulario
    $data = [
        'customer_id' => $_POST['customer_id'],
        'inventory_id' => $_POST['inventory_id'],
        'rental_date' => $_POST['rental_date'],
        'staff_id' => $_POST['staff_id'],
    ];

    if (registrarAlquiler($pdo, $data)) {
        $mensaje = "Alquiler registrado con éxito.";
    } else {
        $mensaje = "Error al registrar el alquiler.";
    }

    $alquileres = obtenerAlquileres($pdo);
}

// para la búsqueda
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar'])) {
    $customer_id = $_POST['customer_id'];
    $estado = $_POST['estado'];
    $alquileres = buscarAlquileres($pdo, $customer_id, $estado); 
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Alquileres</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Alquileres</h1>
        <p><a href="dashboard.php">Regresar al inicio</a></p>

       <!-- LO HE PUESTO EN COMENTARIO PARA NO PERDERLO, era la llamada al script inicial, pero me ha fallado, asi que cambiamos
        de estrategia y lo hacemos en el mismo script <p><a href="registrar-alquiler.php?customer_id=<?= $cliente['customer_id'] ?>&inventory_id=<?= $inventario['inventory_id'] ?>&rental_date=<?= urlencode(date('Y-m-d H:i:s')) ?>&staff_id=<?= $empleado['staff_id'] ?>" onclick="return confirm('¿Estás seguro de que quieres registrar este alquiler?');">Registrar Alquiler</a> -->

        <h2>Registrar un Nuevo Alquiler</h2>
        <?= $mensaje ?>
            <!-- formulario para registrar un alquiler (nuevo) -->
        <form method="post" action="">
            <label for="customer_id">Cliente ID:</label>
            <input type="text" id="customer_id" name="customer_id" required>

            <label for="inventory_id">Inventario ID:</label>
            <input type="text" id="inventory_id" name="inventory_id" required>

            <label for="rental_date">Fecha de Alquiler:</label>
            <input type="datetime-local" id="rental_date" name="rental_date" value="<?= date('Y-m-d\TH:i') ?>" required>

            <label for="staff_id">Empleado ID:</label>
            <input type="text" id="staff_id" name="staff_id" required>

            <button type="submit" name="registrar_alquiler" onclick="return confirm('¿Estás seguro de que quieres registrar este alquiler?');">Registrar Alquiler</button>

        </form>

        <!-- Formulario para buscar alquileres -->
        <h2>Buscar Alquileres</h2>
        <form method="post" action="">
            <label>Seleccionar Cliente:</label>
            <select name="customer_id">
        <option value="">Seleccionar Cliente</option>
        <?php
        // Obtener todos los clientes para el filtro
        $clientes = obtenerClientes($pdo); 
        foreach ($clientes as $cliente) {
            echo "<option value='" . $cliente['customer_id'] . "'>" . htmlspecialchars($cliente['first_name'] . " " . $cliente['last_name']) . "</option>";
        }
        ?>
        </select>

        <label>Estado de Alquiler:</label>
        <select name="estado">
        <option value="">Seleccionar Estado</option>
        <option value="1">Activo</option>
        <option value="0">Devuelto</option>
        </select>

        <button type="submit" name="buscar">Buscar</button>
        </form>

        <!-- Tabla para mostrar alquileres -->
        <table>
            <tr>
                <th>Película</th>
                <th>Cliente</th>
                <th>Fecha de Alquiler</th>
                <th>Fecha de Devolución</th>
                <th>Empleado</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($alquileres as $alquiler): ?>
            <tr>
                <td><?= htmlspecialchars($alquiler['title']) ?></td>
                <td><?= htmlspecialchars($alquiler['first_name'] . " " . $alquiler['last_name']) ?></td>
                <td><?= htmlspecialchars($alquiler['rental_date']) ?></td>
                <td><?= htmlspecialchars($alquiler['return_date'] ?? 'No devuelto') ?></td>
                <td><?= htmlspecialchars($alquiler['staff_id']) ?></td>
                <td>
                    <!-- pàra devolucion (otro script)-->
                    <a href="devolver-alquiler.php?id=<?= $alquiler['rental_id'] ?>">Registrar Devolución</a>
                    <!--para eliminar alquiler (otro script) ESTE FUNCIONA -->
                    <a href="borrar-alquiler.php?rental_id=<?= $alquiler['rental_id'] ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este alquiler?');">Eliminar Alquiler</a>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
