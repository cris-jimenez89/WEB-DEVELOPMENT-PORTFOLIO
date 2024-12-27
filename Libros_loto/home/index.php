<?php
$proverbios = [
    "Los grandes espíritus siempre han encontrado una violenta oposición de mentes mediocres.",
    "La paciencia es un árbol de raíz amarga pero de frutos muy dulces.",
    "La fortuna sonríe a los valientes.",
    "El que no sabe es como el que no ve.",
    "Una sonrisa cuesta menos que la electricidad, pero da más luz.",
    "Donde hay amor, hay vida.",
    "No puedes impedir que los pájaros de la tristeza vuelen sobre tu cabeza, pero sí puedes impedir que aniden en tu cabello.",
    "El sabio no dice lo que sabe, pero el necio no sabe lo que dice.",
    "El hombre que se mueve montañas comienza llevando pequeñas piedras.",
    "La acción es la clave fundamental de todo éxito.",
    "Quien no arriesga no gana."
];

$proverbioAleatorio = $proverbios[array_rand($proverbios)]; //aleatorio

$loremIpsum = "Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit..."; //mi texto
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" > 
</head>
<body>
    <header>
        <img src="https://picsum.photos/100/100" alt="Logo" />
        <h1>Bienvenido a nuestra Aplicación</h1>
    </header>
    <main>
        <p><?php echo $loremIpsum; ?></p>
        <p><em><?php echo $proverbioAleatorio; ?></em></p>
        <nav>
            <ul>
                <li><a href="../loto/menu.php">Generador de Loto</a></li>
                <li><a href="../libros/index.php">Listado de Libros</a></li>
            </ul>
        </nav>
    </main>
</body>
</html>