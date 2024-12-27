<?php
// Incluir el archivo de funciones de búsqueda
include 'db-functions.php';

// Conexión a la base de datos, AQUI LO HACE DIRECTAMENTE, LA FUNCION YA ESTA CONFIGURADA
//DESDE DB FUNNCTION COMO GLOBAL (REVISAR QUE ES ESTO)
$pdo = conectarBaseDatos();

// Obtener el ID de la ciudad desde la URL
$cityId = $_GET['id'];

// Obtener los detalles de la ciudad
$ciudad = obtenerDetalleCiudad($pdo, $cityId);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Ciudad</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Detalles de la Ciudad</h1>
        <?php if ($ciudad): ?>
            <p><strong>ID:</strong> <?= $ciudad['ID'] ?></p>
            <p><strong>Nombre:</strong> <?= $ciudad['Name'] ?></p>
            <p><strong>País:</strong> <?= $ciudad['CountryName'] ?></p>
            <p><strong>Distrito:</strong> <?= $ciudad['District'] ?></p>
            <p><strong>Población:</strong> <?= $ciudad['Population'] ?></p>
        <?php else: ?>
            <p>Ciudad no encontrada.</p>
        <?php endif; ?>
        <p><a href="ciudades-de-un-pais-mejor-ampliacion-1.php">Regresar al listado</a></p>
    </div>
</body>
</html>