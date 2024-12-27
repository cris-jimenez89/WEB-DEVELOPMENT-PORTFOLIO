<!-- buscar_peliculas.php -->
<?php
include 'db-funciones.php';
$pdo = conectarBaseDatos();

// Inicializamos las variables de los filtros
$title = isset($_POST['title']) ? $_POST['title'] : '';
$release_year = isset($_POST['release_year']) ? $_POST['release_year'] : '';
$category = isset($_POST['category']) ? $_POST['category'] : '';

$peliculas = [];

// Si se ha enviado el formulario de búsqueda
if (isset($_POST['buscar'])) {
    $peliculas = buscarPeliculas($pdo, $title, $release_year, $category);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Películas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Buscar Películas</h1>

        <!-- Formulario para buscar películas -->
        <form method="post" action="">
            <label for="title">Título:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($title) ?>">

            <label for="release_year">Año de Lanzamiento:</label>
            <select id="release_year" name="release_year">
                <option value="">Seleccionar Año</option>
                <?php
                $aniosStmt = $pdo->query("SELECT DISTINCT release_year FROM film WHERE release_year IS NOT NULL ORDER BY release_year");
                $anios = $aniosStmt->fetchAll(PDO::FETCH_COLUMN);
                foreach ($anios as $anio) {
                    echo "<option value='$anio' " . ($release_year == $anio ? 'selected' : '') . ">$anio</option>";
                }
                ?>
            </select>

            <label for="category">Categoría:</label>
            <select id="category" name="category">
                <option value="">Seleccionar Categoría</option>
                <?php
                $categoriasStmt = $pdo->query("SELECT name FROM category ORDER BY name");
                $categorias = $categoriasStmt->fetchAll(PDO::FETCH_COLUMN);
                foreach ($categorias as $categoria) {
                    echo "<option value='$categoria' " . ($category == $categoria ? 'selected' : '') . ">$categoria</option>";
                }
                ?>
            </select>

            <button type="submit" name="buscar">Buscar</button>
        </form>

        <p><a href="film.php">Regresar a lista de películas</a></p>

        <h2>Resultados de la Búsqueda</h2>

        <?php if (!empty($peliculas)): ?>
            <table>
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Año</th>
                    <th>Duración</th>
                    <th>Tarifa</th>
                    <th>Categoría</th>
                </tr>
                <?php foreach ($peliculas as $pelicula): ?>
                    <tr>
                        <td><?= htmlspecialchars($pelicula['title']) ?></td>
                        <td><?= htmlspecialchars($pelicula['description']) ?></td>
                        <td><?= htmlspecialchars($pelicula['release_year']) ?></td>
                        <td><?= htmlspecialchars($pelicula['length']) ?></td>
                        <td><?= htmlspecialchars($pelicula['rental_rate']) ?></td>
                        <td><?= htmlspecialchars($pelicula['category_name']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No se encontraron películas que coincidan con los filtros.</p>
        <?php endif; ?>
    </div>
</body>
</html>
