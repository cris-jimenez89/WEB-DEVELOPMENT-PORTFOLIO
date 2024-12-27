<?php
include 'db-funciones.php';

$pdo = conectarBaseDatos();

// Estadísticas del videoclub (para la interfaz principal)
$totalPeliculas = obtenerTotalPeliculas($pdo);
$totalClientes = obtenerTotalClientes($pdo);
$resumenAlquileres = obtenerResumenAlquileres($pdo);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Videoclub</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Dashboard del Videoclub</h1>
        <section>
            <h2>Resumen General</h2>
            <p>Total de películas: <strong><?= htmlspecialchars($totalPeliculas) ?></strong></p>
            <p>Total de clientes: <strong><?= htmlspecialchars($totalClientes) ?></strong></p>
            <p>Alquileres activos: <strong><?= htmlspecialchars($resumenAlquileres['alquileres_activos']) ?></strong></p>
            <p>Alquileres finalizados: <strong><?= htmlspecialchars($resumenAlquileres['alquileres_finalizados']) ?></strong></p>
        </section>

        <!-- Enlaces a las secciones de gestión -->
        <section>
            <h2>Gestión de Videoclub</h2>
            <ul>
                <li><a href="film.php">Películas</a></li>
                <li><a href="customer.php">Clientes</a></li>
                <li><a href="rental.php">Alquileres</a></li>
            </ul>
        </section>
    </div>
</body>
</html>
