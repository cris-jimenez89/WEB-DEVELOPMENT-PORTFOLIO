<?php
include 'db-funciones.php';

$pdo = conectarBaseDatos();

if (isset($_GET['id'])) {
    $filmId = $_GET['id'];
    // Obtener la película por el ID
    $pelicula = obtenerPeliculaPorId($pdo, $filmId);

    // Si el formulario se ha enviado, actualizar la película
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
        $data = [
            'film_id' => $_POST['film_id'],
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'release_year' => $_POST['release_year'],
            'rental_duration' => $_POST['rental_duration'],
            'rental_rate' => $_POST['rental_rate'],
            'length' => $_POST['length'],
            'language_id' => $_POST['language_id'],
        ];
        // Llamada a la función de actualización
        actualizarPelicula($pdo, $data);
        $mensaje = "Película actualizada con éxito.";
        header("Location: film.php"); // Redirigir a la lista de películas
        exit;
    }
} else {
    echo "ID de película no proporcionado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Película</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Editar Película</h1>

        <?php if (isset($mensaje)): ?>
            <p><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <!-- Formulario para editar la película -->
        <?php if ($pelicula): ?>
            <form method="post" action="">
                <input type="hidden" name="film_id" value="<?= htmlspecialchars($pelicula['film_id']) ?>">
                <label>Título:</label>
                <input type="text" name="title" value="<?= htmlspecialchars($pelicula['title']) ?>" required>

                <label>Descripción:</label>
                <textarea name="description" required><?= htmlspecialchars($pelicula['description']) ?></textarea>

                <label>Año de lanzamiento:</label>
                <input type="number" name="release_year" value="<?= htmlspecialchars($pelicula['release_year']) ?>" required>

                <label>Duración:</label>
                <input type="number" name="length" value="<?= htmlspecialchars($pelicula['length']) ?>" required>

                <label>Tarifa de alquiler:</label>
                <input type="text" name="rental_rate" value="<?= htmlspecialchars($pelicula['rental_rate']) ?>" required>

                <label>Duración de alquiler:</label>
                <input type="number" name="rental_duration" value="<?= htmlspecialchars($pelicula['rental_duration']) ?>" required>

                <label>ID de Lenguaje:</label>
                <input type="number" name="language_id" value="<?= htmlspecialchars($pelicula['language_id']) ?>" required>

                <button type="submit" name="actualizar">Actualizar Película</button>
            </form>
        <?php else: ?>
            <p>No se encontró la película.</p>
        <?php endif; ?>

    </div>
</body>
</html>

