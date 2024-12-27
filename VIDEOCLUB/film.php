<?php
include 'db-funciones.php';

$pdo = conectarBaseDatos();

// Inicializamos las variables
$peliculas = obtenerPeliculas($pdo); 
$mensaje = '';
$pelicula = null; // Variable para almacenar la película que se está editando

// Crear una nueva película, con LA FUNCION CREAR PELICULA (parece que funciona bien PERO NO ACTUALIZA, REVISAR)
// LO ARREGLAMOS, HABIA UN ERROR CON CATEGORIA!!!
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $data = [
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'release_year' => $_POST['release_year'],
        'rental_duration' => $_POST['rental_duration'],
        'rental_rate' => $_POST['rental_rate'],
        'length' => $_POST['length'],
        'language_id' => $_POST['language_id'],
    ];
    crearPelicula($pdo, $data);
    $mensaje = "Película creada con éxito.";
    //$peliculas = actualizarPelicula($pdo, $data); // incluimos esto POR SI ACTUALIZAMOS LA PELICULA, PERO NO FUNCIONA TAMPOCO
    $peliculas = obtenerPeliculas($pdo);
} 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Películas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Películas</h1>
        
        <?php if ($mensaje): ?>
            <p><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>
    

        <!-- Formulario para crear nueva película -->
        <h2>Crear Nueva Película</h2>
        <form method="post" action="">
            <label>Título:</label>
            <input type="text" name="title" required>

            <label>Descripción:</label>
            <textarea name="description" required></textarea>

            <label>Año de lanzamiento:</label>
            <input type="number" name="release_year" required>

            <label>Duración:</label>
            <input type="number" name="length" required>

            <label>Tarifa de alquiler:</label>
            <input type="text" name="rental_rate" required>

        <div>  <!-- he metido esto porque se veia cortado el texto y el boton. -->
            <label>Duración alquiler:</label>
            <input type="number" name="rental_duration" required>
            <label>ID de Lenguaje:</label>
            <input type="number" name="language_id" required>
            <button type="submit" name="crear">Crear Película</button>

        </div>
        </form>

        <p><a href="dashboard.php">Regresar al inicio</a></p>
        <p><a href="buscar-peliculas.php">Búsqueda avanzada</a></p>



        <h2>Lista de Películas</h2>
<table>
    <tr>
        <th>Título</th>
        <th>Descripción</th>
        <th>Año</th>
        <th>Duración</th>
        <th>Tarifa</th>
        <th>Categoría</th> 
        <th>Acciones</th>
    </tr>
    
    <?php foreach ($peliculas as $pelicula): //hemos
                    // Reemplazar valores nulos por 'Other' en PHP, VAMOS A PROBAR ESTO PARA VISUALIZAR LAS CREADAS, 
                    //por si EL PROBLEMA ERA LA CATEGORIA, LO PROBAMOS
            //$categoria = empty($pelicula['category']) ? 'Other' : $pelicula['category'];
    ?>
    <tr>
        
        <td><?= htmlspecialchars($pelicula['title']) ?></td>
        <td><?= htmlspecialchars($pelicula['description']) ?></td>
        <td><?= htmlspecialchars($pelicula['release_year']) ?></td>
        <td><?= htmlspecialchars($pelicula['length']) ?></td>
        <td><?= htmlspecialchars($pelicula['rental_rate']) ?></td>
        <td><?= htmlspecialchars($pelicula['category']) ?></td> 
        <td>
            <a href="editar-pelicula.php?id=<?= $pelicula['film_id'] ?>">Editar</a><!-- tengo que hacer estos dos scripts y modificar esto-->
            <a href="borrar-pelicula.php?id=<?= $pelicula['film_id'] ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar esta película?');">Eliminar</a>

        </td>
    </tr>
    <?php endforeach; ?>
</table>
