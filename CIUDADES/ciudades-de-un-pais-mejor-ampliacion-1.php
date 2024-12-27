<?php
include 'db-functions.php';

$pdo = conectarBaseDatos();

// Obtener la lista de países e inicializar variables
$paises = obtenerPaises($pdo);
$ciudades = [];
$paisSeleccionado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el código del país seleccionado
    $countryCode = $_POST['country'];

    // Obtener el nombre del país seleccionado
    foreach ($paises as $pais) {
        if ($pais['Code'] === $countryCode) {
            $paisSeleccionado = $pais['Name'];
            break;
        }
    }

    // Para obtener las ciudades del país seleccionado
    $ciudades = obtenerCiudades($pdo, $countryCode);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Ciudades</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Listado de Ciudades por País</h1>
        <form method="post" action="">
            <label for="country">Selecciona un país:</label>
            <select name="country" id="country">
                <?php foreach ($paises as $pais): ?>
                    <option value="<?= $pais['Code'] ?>"><?= $pais['Name'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Mostrar Ciudades</button>
        </form>

        <?php if (!empty($ciudades)): ?>
            <h2>Ciudades de <?= htmlspecialchars($paisSeleccionado) ?>:</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Distrito</th>
                    <th>Población</th>
                </tr>
                <?php foreach ($ciudades as $ciudad): ?>
                <tr>
                    <td><a href="detalle-ciudad.php?id=<?= $ciudad['ID'] ?>"><?= $ciudad['ID'] ?></a></td>
                    <td><?= $ciudad['Name'] ?></td>
                    <td><?= $ciudad['District'] ?></td>
                    <td><?= $ciudad['Population'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>