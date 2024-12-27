<?php
// Parámetros de conexión
$host = 'localhost';
$db = 'world';
$user = 'root';
$pass = 'root';

// Función para crear la conexión a la base de datos
function conectarBaseDatos() {
    global $host, $db, $user, $pass;
    try {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8";
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "<h1>Error de conexión:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}

// Función para obtener la lista de países
function obtenerPaises($pdo) {
    try {
        $stmt = $pdo->query("SELECT Code, Name FROM country ORDER BY Name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}

// Función para obtener las ciudades de un país
function obtenerCiudades($pdo, $countryCode) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM city WHERE CountryCode = :countryCode");
        $stmt->execute(['countryCode' => $countryCode]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}

// Nueva función para obtener los detalles de una ciudad
function obtenerDetalleCiudad($pdo, $cityId) {
    try {
        $stmt = $pdo->prepare(
            "SELECT city.*, country.Name as CountryName 
             FROM city 
             JOIN country ON city.CountryCode = country.Code 
             WHERE city.ID = :cityId"
        );
        $stmt->execute(['cityId' => $cityId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}

// Función para borrar una ciudad (MIRAR DESDE AQUI PARA MI EJERCICIO FINAL)
function borrarCiudad($pdo, $cityId) {
    try {
        $stmt = $pdo->prepare("DELETE FROM city WHERE ID = :cityId");
        $stmt->execute(['cityId' => $cityId]);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}

// Función para actualizar la población de una ciudad
function actualizarPoblacionCiudad($pdo, $cityId, $nuevaPoblacion) {
    try {
        $stmt = $pdo->prepare("UPDATE city SET Population = :population WHERE ID = :cityId");
        $stmt->execute(['population' => $nuevaPoblacion, 'cityId' => $cityId]);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}

// Función para obtener el nombre de un país por su código
function obtenerNombrePais($pdo, $countryCode) {
    try {
        $stmt = $pdo->prepare("SELECT Name FROM country WHERE Code = :countryCode");
        $stmt->execute(['countryCode' => $countryCode]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['Name'];
    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}

// Función para agregar una nueva ciudad
function agregarCiudad($pdo, $nombre, $countryCode, $distrito, $poblacion) {
    try {
        $stmt = $pdo->prepare("INSERT INTO city (Name, CountryCode, District, Population) VALUES (:nombre, :countryCode, :distrito, :poblacion)");
        $stmt->execute(['nombre' => $nombre, 'countryCode' => $countryCode, 'distrito' => $distrito, 'poblacion' => $poblacion]);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}
?>
?>