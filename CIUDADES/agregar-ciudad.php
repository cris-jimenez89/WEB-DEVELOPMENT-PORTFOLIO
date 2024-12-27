<?php
// Incluir el archivo de funciones de búsqueda
include 'db-functions.php';

// Conexión a la base de datos
$pdo = conectarBaseDatos();

// Obtener el código del país desde la URL
$countryCode = $_GET['country'];

// Obtener el nombre del país seleccionado
$paisSeleccionado = obtenerNombrePais($pdo, $countryCode);

// Procesar la adición de una nueva ciudad
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreCiudad = $_POST['nombre'];
    $distrito = $_POST['distrito'];
    $poblacion = $_POST['poblacion'];
    $countryCode = $_POST['countryCode'];

    agregarCiudad($pdo, $nombreCiudad, $countryCode, $distrito, $poblacion);
    header("Location: ciudades-de-un-pais-mejor-ampliacion-2.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Ciudad</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Agregar Ciudad a <?= htmlspecialchars($paisSeleccionado) ?></h1>
        <form method="post" action="agregar-ciudad.php">
            <input type="hidden" name="countryCode" value="<?= $countryCode ?>">
            <p><strong>Nombre de la Ciudad:</strong> <input type="text" name="nombre" required></p>
            <p><strong>Distrito:</strong> <input type="text" name="distrito" required></p>
            <p><strong>Población:</strong> <input type="number" name="poblacion" required></p>
            <button type="submit">Agregar Ciudad</button>
        </form>
        <p><a href="ciudades-de-un-pais-mejor-ampliacion-2.php">Regresar al listado</a></p>
    </div>
</body>
</html>