<?php
include 'db-funciones.php'; // Incluir las funciones de conexión

$pdo = conectarBaseDatos(); // Conectar a la base de datos

// Obtener el customer_id desde la URL
$customer_id = $_GET['customer_id'] ?? null;

if ($customer_id) {
    // Obtener el historial de alquileres para este cliente
    $sql = "SELECT rental.rental_id, rental.rental_date, rental.return_date, film.title 
            FROM rental
            JOIN inventory ON rental.inventory_id = inventory.inventory_id
            JOIN film ON inventory.film_id = film.film_id
            WHERE rental.customer_id = :customer_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['customer_id' => $customer_id]);
    $alquileres = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "No se ha proporcionado un cliente.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Alquileres</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
    <p><a href="customer.php">Regresar a la lista de clientes</a></p>
        <h1>Historial de Alquileres del Cliente</h1>

        <?php if (count($alquileres) > 0): ?>
            <table>
                <tr>
                    <th>Película</th>
                    <th>Fecha de Alquiler</th>
                    <th>Fecha de Devolución</th>
                </tr>
                <?php foreach ($alquileres as $alquiler): ?>
                <tr>
                    <td><?= htmlspecialchars($alquiler['title']) ?></td>
                    <td><?= htmlspecialchars($alquiler['rental_date']) ?></td>
                    <td><?= htmlspecialchars($alquiler['return_date']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No se encontraron alquileres para este cliente.</p>
        <?php endif; ?>
    </div>
</body>
</html>
