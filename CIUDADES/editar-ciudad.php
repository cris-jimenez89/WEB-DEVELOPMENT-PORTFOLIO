<?php
// Incluir el archivo de funciones de búsqueda
include 'db-functions.php';

// Conexión a la base de datos
$pdo = conectarBaseDatos();

// Obtener el ID de la ciudad desde la URL
$cityId = $_GET['id'];

// Obtener los detalles de la ciudad
$ciudad = obtenerDetalleCiudad($pdo, $cityId);

// Procesar la actualización de la población
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevaPoblacion = $_POST['population'];
    actualizarPoblacionCiudad($pdo, $cityId, $nuevaPoblacion);
    header("Location: ciudades-de-un-pais-mejor-ampliacion-2.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ciudad</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Editar Ciudad</h1>
        <?php if ($ciudad): ?>
            <form method="post" action="editar-ciudad.php?id=<?= $ciudad['ID'] ?>">
                <p><strong>ID:</strong> <?= $ciudad['ID'] ?></p>
                <p><strong>Nombre:</strong> <?= $ciudad['Name'] ?></p>
                <p><strong>País:</strong> <?= $ciudad['CountryName'] ?></p>
                <p><strong>Distrito:</strong> <?= $ciudad['District'] ?></p>
                <p><strong>Población:</strong> <input type="number" name="population" value="<?= $ciudad['Population'] ?>"></p>
                <button type="submit">Actualizar Población</button>
            </form>
        <?php else: ?>
            <p>Ciudad no encontrada.</p>
        <?php endif; ?>
        <p><a href="ciudades-de-un-pais-mejor-ampliacion-2.php">Regresar al listado</a></p>
    </div>
</body>
</html>