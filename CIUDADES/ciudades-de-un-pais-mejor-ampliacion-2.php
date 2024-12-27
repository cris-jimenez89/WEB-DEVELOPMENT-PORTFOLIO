<?php
// Incluir el archivo de funciones de búsqueda
include 'db-functions.php';

// Conexión a la base de datos
$pdo = conectarBaseDatos();

// Obtener la lista de países
$paises = obtenerPaises($pdo);

// Inicializar variables
$ciudades = [];
$paisSeleccionado = '';
$countryCodeSeleccionado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el código del país seleccionado
    $countryCode = $_POST['country'];
    $countryCodeSeleccionado = $countryCode;

    // Obtener el nombre del país seleccionado
    foreach ($paises as $pais) {
        if ($pais['Code'] === $countryCode) {
            $paisSeleccionado = $pais['Name'];
            break;
        }
    }

    // Obtener las ciudades del país seleccionado
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function confirmarBorrado(id, nombre) {
            if (confirm('¿Estás seguro de que quieres eliminar la ciudad "' + nombre + '"?')) {
                window.location.href = 'borrar-ciudad.php?id=' + id;
            }
        }
    </script>
</head>

<body>
    <div class="container">
        <h1>Listado de Ciudades por País</h1>
        <form method="post" action="">
            <label for="country">Selecciona un país:</label>
            <select name="country" id="country">
                <?php foreach ($paises as $pais) : ?>
                    <option value="<?= $pais['Code'] ?>"><?= $pais['Name'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Mostrar Ciudades</button>
        </form>

        <?php if (!empty($ciudades)) : ?>
            <h2>Ciudades de <?= htmlspecialchars($paisSeleccionado) ?>:</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Distrito</th>
                    <th>Población</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($ciudades as $ciudad) : ?>
                    <tr>
                        <td><a href="detalle-ciudad.php?id=<?= $ciudad['ID'] ?>"><?= $ciudad['ID'] ?></a></td>
                        <td><?= $ciudad['Name'] ?></td>
                        <td><?= $ciudad['District'] ?></td>
                        <td><?= $ciudad['Population'] ?></td>
                        <td class="acciones">
                            <a href="editar-ciudad.php?id=<?= $ciudad['ID'] ?>" title="Editar"><i class="fas fa-edit"></i></a>
                            <a href="#" title="Eliminar" onclick="confirmarBorrado(<?= $ciudad['ID'] ?>, '<?= htmlspecialchars($ciudad['Name'], ENT_QUOTES) ?>')"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <p><a href="ciudades-de-un-pais-mejor-ampliacion-2.php">Limpiar Listado</a></p>
            <p><a href="agregar-ciudad.php?country=<?= $countryCodeSeleccionado ?>">Agregar Ciudad</a></p>
        <?php endif; ?>
    </div>
</body>

</html>