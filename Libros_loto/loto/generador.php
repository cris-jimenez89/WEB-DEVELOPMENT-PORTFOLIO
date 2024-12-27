<?php
// Función aue usamos en php normal para la loto
function lotoInternacional($bolasBombo, $bolasPremio) {
    $bombo = range(1, $bolasBombo);  // array con numeros de 1 al limite (depende de nuestro pais)
    shuffle($bombo);  // lo mezcla para que sea aleatorio
    $premiadas = array_slice($bombo, 0, $bolasPremio);  // Toma los primeros numeros (depende del pais)
    sort($premiadas);  //para ordenar
    return $premiadas;
}

$resultado = [];
$paisSeleccionado = '';

// Verifica si el parámetro "pais" está presente en la URL
if (isset($_GET['pais'])) {
    $paisSeleccionado = $_GET['pais'];
    
    // parametros para cada pais
    switch ($paisSeleccionado) {
        case 'España':
        case 'Alemania':
            $resultado = lotoInternacional(49, 6);
            break;
        case 'Italia':
            $resultado = lotoInternacional(90, 6);
            break;
        case 'Francia':
            $resultado = lotoInternacional(49, 5);
            break;
        default:
            $paisSeleccionado = ''; // Maneja el caso de un valor no esperado
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados de la Lotería</title>
</head>
<body>
    <h1>Resultados de la Lotería</h1>

    <!-- Verifica si hay un resultado para mostrar -->
    <?php if (!empty($resultado)): ?>
        <h2>Resultado para <?= htmlspecialchars($paisSeleccionado) ?>:</h2>
        <p>Números ganadores: <?= implode(", ", $resultado) ?></p>
    <?php elseif ($paisSeleccionado): ?>
        <p>No se pudo generar una combinación para el país seleccionado.</p>
    <?php else: ?>
        <p>No se ha seleccionado ningún país.</p>
    <?php endif; ?>

    <!-- Enlace para volver al menú de loto -->
    <p><a href="menu.php">Volver al menú de Loterías</a></p>
    <p><a href="../home/index.php">Volver al inicio</a></p>
</body>
</html>

