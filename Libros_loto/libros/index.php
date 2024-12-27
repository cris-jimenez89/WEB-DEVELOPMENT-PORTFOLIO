<?php
// Array con los 15 libros
$libros = [
    0 => ['titulo' => 'Cien Años de Soledad', 'autor' => 'Gabriel García Márquez', 'ejemplares' => 10, 'genero' => 'Novela'],
    1 => ['titulo' => 'Don Quijote de la Mancha', 'autor' => 'Miguel de Cervantes', 'ejemplares' => 5, 'genero' => 'Novela'],
    2 => ['titulo' => 'La Sombra del Viento', 'autor' => 'Carlos Ruiz Zafón', 'ejemplares' => 7, 'genero' => 'Misterio'],
    3 => ['titulo' => '1984', 'autor' => 'George Orwell', 'ejemplares' => 8, 'genero' => 'Distopía'],
    4 => ['titulo' => 'El Señor de los Anillos', 'autor' => 'J.R.R. Tolkien', 'ejemplares' => 6, 'genero' => 'Fantasía'],
    5 => ['titulo' => 'El Alquimista', 'autor' => 'Paulo Coelho', 'ejemplares' => 10, 'genero' => 'Filosofía'],
    6 => ['titulo' => 'Matar a un Ruiseñor', 'autor' => 'Harper Lee', 'ejemplares' => 4, 'genero' => 'Ficción'],
    7 => ['titulo' => 'Orgullo y Prejuicio', 'autor' => 'Jane Austen', 'ejemplares' => 9, 'genero' => 'Romance'],
    8 => ['titulo' => 'Crimen y Castigo', 'autor' => 'Fiódor Dostoyevski', 'ejemplares' => 5, 'genero' => 'Filosofía'],
    9 => ['titulo' => 'El Gran Gatsby', 'autor' => 'F. Scott Fitzgerald', 'ejemplares' => 7, 'genero' => 'Ficción'],
    10 => ['titulo' => 'En Busca del Tiempo Perdido', 'autor' => 'Marcel Proust', 'ejemplares' => 3, 'genero' => 'Clásico'],
    11 => ['titulo' => 'Cumbres Borrascosas', 'autor' => 'Emily Brontë', 'ejemplares' => 6, 'genero' => 'Romance'],
    12 => ['titulo' => 'Los Miserables', 'autor' => 'Victor Hugo', 'ejemplares' => 4, 'genero' => 'Histórico'],
    13 => ['titulo' => 'Ulises', 'autor' => 'James Joyce', 'ejemplares' => 2, 'genero' => 'Clásico'],
    14 => ['titulo' => 'El Retrato de Dorian Gray', 'autor' => 'Oscar Wilde', 'ejemplares' => 8, 'genero' => 'Fantasía']
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Libros</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
    <div class="container">
        <h1>Listado de Libros</h1>
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Ejemplares</th>
                    <th>Género</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($libros as $id => $libro): ?>
                    <tr>
                        <td><a href="detalle.php?id=<?= $id ?>"><?= $libro['titulo'] ?></a></td>
                        <td><?= $libro['autor'] ?></td>
                        <td><?= $libro['ejemplares'] ?></td>
                        <td><?= $libro['genero'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><a href="../home/index.php">Volver al inicio</a></p>
    </div>
</body>
</html>
