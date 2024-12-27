<?php
include 'db-funciones.php';

$pdo = conectarBaseDatos();

$clientes = obtenerClientes($pdo); 
$mensaje = '';
$cliente = null; // Variable para almacenar el cliente que se está editando

// Crear un nuevo cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $data = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'address_id' => $_POST['address_id'],
        'store_id' => $_POST['store_id'],
        'active' => $_POST['active'],
    ];
    crearCliente($pdo, $data); // Llamamos a la función para crear el cliente
    $mensaje = "Cliente creado con éxito.";
    $clientes = obtenerClientes($pdo); // Actualizamos la lista de clientes
}


// búsqueda, LO ARREGLAMOS EN ARCHIVO, HABIAMOS HECHO MAL EL POST!!!!
// COMO EN FILM, PRIMERO los nombres únicos de los clientes
$nombresStmt = $pdo->query("SELECT DISTINCT first_name FROM customer ORDER BY first_name");
$nombres = $nombresStmt->fetchAll(PDO::FETCH_COLUMN);

// LUEGO los apellidos
$apellidosStmt = $pdo->query("SELECT DISTINCT last_name FROM customer ORDER BY last_name");
$apellidos = $apellidosStmt->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $clientes = buscarClientes($pdo, $first_name,$last_name); // Llamamos a la función de búsqueda
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Clientes</h1>
        
        <?php if ($mensaje): ?>
            <p><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <!-- Formulario para crear nuevo cliente, EL ERROR ME SALE EN STORE_ID PARECE-->
        <h2>Crear Nuevo Cliente</h2>
        <form method="post" action="">
            <label>Nombre:</label>
            <input type="text" name="first_name" required>

            <label>Apellido:</label>
            <input type="text" name="last_name" required>

            <label>Correo Electrónico:</label>
            <input type="email" name="email" required>

            <label>ID de Dirección:</label>
            <input type="number" name="address_id" required>

            <label>ID de Tienda:</label>
            <input type="number" name="store_id" required>

            <label>Estado (Activo):</label>
            <select name="active" required>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
            <button type="submit" name="crear">Crear Cliente</button>
        </form>

        <p><a href="dashboard.php">Regresar al inicio</a></p>

        <!-- Formulario para buscar clientes -->
        <h2>Buscar Clientes</h2>
        <form method="post" action="">
        <label>Buscar por Nombre:</label>
        <select name="first_name">
        <option value="">Seleccionar Nombre</option>
        <?php foreach ($nombres as $nombre): ?>
            <option value="<?= htmlspecialchars($nombre) ?>"><?= htmlspecialchars($nombre) ?></option>
        <?php endforeach; ?>
        </select>

        <label>Buscar por Apellido:</label>
        <select name="last_name">
        <option value="">Seleccionar Apellido</option>
        <?php foreach ($apellidos as $apellido): ?>
            <option value="<?= htmlspecialchars($apellido) ?>"><?= htmlspecialchars($apellido) ?></option>
        <?php endforeach; ?>
        </select>

        <button type="submit" name="buscar">Buscar</button>
    </form>


        <h2>Lista de Clientes</h2>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Dirección</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td><?= htmlspecialchars($cliente['first_name']) ?></td>
                <td><?= htmlspecialchars($cliente['last_name']) ?></td>
                <td><?= htmlspecialchars($cliente['email']) ?></td>
                <td><?= htmlspecialchars($cliente['address']) ?></td>
                <td><?= htmlspecialchars($cliente['active'] == 1 ? 'Activo' : 'Inactivo') ?></td>
                <td>
                    <a href="editar-cliente.php?id=<?= $cliente['customer_id'] ?>">Editar</a>
                    <a href="borrar-cliente.php?id=<?= $cliente['customer_id'] ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este cliente?');">Eliminar</a>
                    <a href="historial-alquileres.php?customer_id=<?= urlencode($cliente['customer_id']) ?>">Ver Historial de Alquileres</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
