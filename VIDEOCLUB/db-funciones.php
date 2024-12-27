<?php
// Parámetros de conexión
$host = 'localhost';
$db = 'sakila';
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

// Función para obtener el total de películas
function obtenerTotalPeliculas($pdo) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) AS total_peliculas FROM film");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_peliculas'];
    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}

// Función para obtener el total de clientes
function obtenerTotalClientes($pdo) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) AS total_clientes FROM customer");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_clientes'];
    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}

// Función para obtener el resumen de alquileres activos y finalizados, HASTA AQUI LAS FUNCIONES PARA EL DASHBOARD
function obtenerResumenAlquileres($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT 
                SUM(CASE WHEN return_date IS NULL THEN 1 ELSE 0 END) AS alquileres_activos,
                SUM(CASE WHEN return_date IS NOT NULL THEN 1 ELSE 0 END) AS alquileres_finalizados
            FROM rental
        ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}

/*// HEMOS MODIFICADO ESTA FUNCION, PORQUE LAS CATEGORIAS NO SE VEIAN!!!! LA DEJAMOS AQUI PERO NO NOS SERVIRA.
Función para obtener todas las películas, A PARTIR DE AQUI SON FUNCIONES PARA EL CRUD DE PELICULAS!
//PARA EL ERROR DE LA ACTUALIZACION DE PELICULAS CUANDO LAS GRABABA POR CULPA DE, HE USADO COALESCE (RECOMENDADO)
function obtenerPeliculas($pdo) {
    $sql = "SELECT f.film_id, f.title, f.description, f.release_year, f.length, f.rental_rate, c.name AS category_name AQUI ESTABA EL ERROR
            FROM film f
            LEFT JOIN film_category fc ON f.film_id = fc.film_id
            LEFT JOIN category c ON fc.category_id = c.category_id";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}*/

function obtenerPeliculas($pdo) {
    // Realizamos la consulta con JOIN para obtener la categoría de cada película, Y PONEMOS QUE ORDENE POR ORDEN ALFABETICO
    //PORQUE LAS PELICULAS QUE CREAMOS SE VAN CARGANDO ABAJO EN LUGAR DE ORGANIZARSE POR NOMBRE
    $sql = "
        SELECT f.*, c.name AS category
        FROM film f
        LEFT JOIN film_category fc ON f.film_id = fc.film_id
        LEFT JOIN category c ON fc.category_id = c.category_id
        ORDER BY f.title ASC 
    ";

    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Función para crear una nueva película
function crearPelicula($pdo, $data) {
    try {
        //iniciar transaccion
        $pdo-> beginTransaction();
        $stmt = $pdo->prepare("
            INSERT INTO film (title, description, release_year, rental_duration, rental_rate, length, language_id)
            VALUES (:title, :description, :release_year, :rental_duration, :rental_rate, :length, :language_id)");
        $stmt->execute($data);
        //confirma la transaccion
        $pdo-> commit();

    } catch (PDOException $e) {
        //si ocurre error deshace los cambios
        $pdo-> rollback();
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}


// Función para actualizar la película
function actualizarPelicula($pdo, $data) {
    $sql = "UPDATE film
            SET title = :title, description = :description, release_year = :release_year,
                rental_duration = :rental_duration, rental_rate = :rental_rate, length = :length,
                language_id = :language_id
            WHERE film_id = :film_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':film_id' => $data['film_id'],
        ':title' => $data['title'],
        ':description' => $data['description'],
        ':release_year' => $data['release_year'],
        ':rental_duration' => $data['rental_duration'],
        ':rental_rate' => $data['rental_rate'],
        ':length' => $data['length'],
        ':language_id' => $data['language_id']
    ]);
}

// Función para obtener una película por su ID (NO SABIA QUE HACIA FALTA)
function obtenerPeliculaPorId($pdo, $filmId) {
    try {
        $stmt = $pdo->prepare("
            SELECT f.film_id, f.title, f.description, f.release_year, f.length, f.rental_rate, c.name as category, f.rental_duration, f.language_id
            FROM film f
            JOIN film_category fc ON f.film_id = fc.film_id
            JOIN category c ON fc.category_id = c.category_id
            WHERE f.film_id = :film_id");
        $stmt->execute(['film_id' => $filmId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
} 


/* COMENTAMOS ESTA FUNCION COMO LA DE ELIMINAR CLIENTE (PUES ES LA PRIMERA QUE HICIMOS), PARA EVITAR LAS RESTRICCIONES!!!4
 Y PONEMOS DEBAJO LA CORRECTA
function eliminarPelicula($pdo, $filmId) {
    try {
        // eliminamos la película por su ID
        $stmt = $pdo->prepare("DELETE FROM film WHERE film_id = :filmId");
        // y pasamos el ID de la película
        $stmt->execute(['filmId' => $filmId]);
        return $stmt->rowCount(); 
    } catch (PDOException $e) {
        echo "<h1>Error al eliminar la película: </h1><p>" . $e->getMessage() . "</p>";
        exit;
    }
} */

// Y CREAMOS ESTA NUEVA FUNCION, EN LA QUE ELIMINAMOS LOS REGISTROS ANTES EN LAS TABLAS RELACIONADAS.
//tenemos que modificarla varias veces porque salen restricciones CON NUEVAS TABLAS.
function eliminarPelicula($pdo, $filmId) {
    try {
        $pdo->beginTransaction();

        // Paso 1: Eliminamos registros en `rental` relacionados con `inventory` que tienen la `film_id` deseada
        $deleteRentalStmt = $pdo->prepare("
            DELETE rental FROM rental
            JOIN inventory ON rental.inventory_id = inventory.inventory_id
            WHERE inventory.film_id = :film_id");
        $deleteRentalStmt->execute(['film_id' => $filmId]);

        // Paso 2: Eliminamos registros en `inventory` relacionados con `film_id`
        $deleteInventoryStmt = $pdo->prepare("DELETE FROM inventory WHERE film_id = :film_id");
        $deleteInventoryStmt->execute(['film_id' => $filmId]);

        // Paso 3: Eliminamos registros en `film_actor` relacionados con `film_id`
        $deleteFilmActorStmt = $pdo->prepare("DELETE FROM film_actor WHERE film_id = :film_id");
        $deleteFilmActorStmt->execute(['film_id' => $filmId]);

        // Paso 4: Eliminamos registros en `film_category` relacionados con `film_id`
        $deleteFilmCategoryStmt = $pdo->prepare("DELETE FROM film_category WHERE film_id = :film_id");
        $deleteFilmCategoryStmt->execute(['film_id' => $filmId]);

        // Paso 5: Finalmente, eliminamos la película de la tabla `film`
        $deleteFilmStmt = $pdo->prepare("DELETE FROM film WHERE film_id = :film_id");
        $deleteFilmStmt->execute(['film_id' => $filmId]);

        // Si todo ha ido bien, cometemos la transacción
        $pdo->commit();

        return true;
    } catch (PDOException $e) {
        // EJEMPLO JAVI PARA ARREGLAR LAS PELICULAS.
        $pdo->rollBack();
        echo "<h1>Error al eliminar la película: " . $e->getMessage() . "</h1>";
        return false;
    }
}



// Función para buscar películas por título y año (he tenido que 
//modificarla ENTERA, PORQUE NO ME FUNCIONABA) 3 MODIFICACIONES, Y ME SIGUE DANDO PROBLEMAS, HACERLA MAS VECESSS
// Función para realizar la búsqueda de películas
function buscarPeliculas($pdo, $title, $release_year, $category) {
    // Consulta básica
    $sql = "SELECT f.film_id, f.title, f.description, f.release_year, f.length, f.rental_rate, c.name AS category_name
            FROM film f
            LEFT JOIN film_category fc ON f.film_id = fc.film_id
            LEFT JOIN category c ON fc.category_id = c.category_id
            WHERE 1"; // Siempre es verdadero para que la consulta funcione correctamente

    // Filtro por título
    if ($title) {
        $sql .= " AND f.title LIKE :title";
    }

    // Filtro por año de lanzamiento
    if ($release_year) {
        $sql .= " AND f.release_year = :release_year";
    }

    // Filtro por categoría
    if ($category) {
        $sql .= " AND c.name = :category";
    }

    // Preparamos la consulta
    $stmt = $pdo->prepare($sql);

    // Vinculamos los parámetros de la consulta
    if ($title) {
        $stmt->bindValue(':title', "%$title%", PDO::PARAM_STR);
    }
    if ($release_year) {
        $stmt->bindValue(':release_year', $release_year, PDO::PARAM_INT);
    }
    if ($category) {
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
    }

    // Ejecutamos la consulta
    $stmt->execute();

    // Retornamos todos los resultados
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



//DESDE AQUI, PARTE DE CRUD CLIENTES

// Función para obtener todos los clientes
function obtenerClientes($pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT c.customer_id, c.first_name, c.last_name, c.email, a.address, c.active
            FROM customer c
            JOIN address a ON c.address_id = a.address_id
            ORDER BY c.last_name, c.first_name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}

// Función para obtener un cliente por su ID
function obtenerClientePorId($pdo, $customerId) {
    try {
        $stmt = $pdo->prepare("
            SELECT c.customer_id, c.first_name, c.last_name, c.email, c.address_id, c.active
            FROM customer c
            WHERE c.customer_id = :customer_id");
        $stmt->execute(['customer_id' => $customerId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}

// Función para crear un nuevo cliente
function crearCliente($pdo, $data) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO customer (first_name, last_name, email, address_id, store_id, active)
            VALUES (:first_name, :last_name, :email, :address_id, :store_id, :active)");
        $stmt->execute($data);
    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}

// Función para actualizar un cliente
function actualizarCliente($pdo, $data) {
    try {
        $stmt = $pdo->prepare("
            UPDATE customer
            SET first_name = :first_name, last_name = :last_name, email = :email,
                address_id = :address_id, active = :active
            WHERE customer_id = :customer_id");
        $stmt->execute($data);
    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}

/*// Función para eliminar un cliente, COMENTO LA FUNCION, PARA PODER ARREGLAR EL PROBLEMA DE LA ELIMINACION DE REGISTROS
function eliminarCliente($pdo, $customerId) {
    try {
        $stmt = $pdo->prepare("DELETE FROM customer WHERE customer_id = :customerId");
        $stmt->execute(['customerId' => $customerId]);
        return $stmt->rowCount();  // Devuelve el número de filas afectadas
    } catch (PDOException $e) {
        echo "<h1>Error al eliminar el cliente:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
} */

// PARA ARREGLAR EL PROBLEMA DE RESTRICCION DE TABLAS PARA ELIMINAR, MODIFICAMOS ESTA FUNCION Y SE ARREGLA!!!!
function eliminarCliente($pdo, $customerId) {
    try {
        // Inicia una transacción para asegurar que todas las eliminaciones se realicen juntas
        $pdo->beginTransaction();

        // Elimina los registros dependientes en la tabla `rental`
        $deleteRentalsStmt = $pdo->prepare("DELETE FROM rental WHERE customer_id = :customer_id");
        $deleteRentalsStmt->execute(['customer_id' => $customerId]);

        // Elimina los registros dependientes en la tabla `payment` (que depende de `rental`)
        $deletePaymentsStmt = $pdo->prepare("DELETE FROM payment WHERE customer_id = :customer_id");
        $deletePaymentsStmt->execute(['customer_id' => $customerId]);

        // Luego elimina el cliente en la tabla `customer`
        $deleteCustomerStmt = $pdo->prepare("DELETE FROM customer WHERE customer_id = :customer_id");
        $deleteCustomerStmt->execute(['customer_id' => $customerId]);

        // Confirma la transacción
        $pdo->commit();
        
        return true;
    } catch (PDOException $e) {
        // En caso de error, revierte la transacción
        $pdo->rollBack();
        echo "<h1>Error en la eliminación: " . $e->getMessage() . "</h1>";
        return false;
    }
}


// Función para buscar clientes por nombre (first_name y last_name) FUNCIONA! HE TENIDO QUE MODIFICARLO
//CASI ENTERO, REVISAR EN NOTAS EJERCICIO!!!
function buscarClientes($pdo, $first_name = '', $last_name = '') {
    try {
        $sql = "SELECT customer_id, first_name, last_name, email FROM customer WHERE 1";
        $params = [];
        
        if ($first_name) {
            $sql .= " AND first_name = :first_name";
            $params[':first_name'] = $first_name;
        }
        if ($last_name) {
            $sql .= " AND last_name = :last_name";
            $params[':last_name'] = $last_name;
        }

        $sql .= " ORDER BY first_name, last_name";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}

//y ahora LAS FUNCIONES DE ALQUILER, TODO EL CRUD
function obtenerAlquileres($pdo) {
    try {
    $sql = "
        SELECT r.rental_id, f.title, c.first_name, c.last_name, r.rental_date, r.return_date, r.staff_id
        FROM rental r
        JOIN inventory i ON r.inventory_id = i.inventory_id
        JOIN film f ON i.film_id = f.film_id
        JOIN customer c ON r.customer_id = c.customer_id";
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
    exit;
}
}


// Obtener un alquiler por ID
function obtenerAlquilerPorId($pdo, $rentalId) {
    try{
    $sql = "
        SELECT r.rental_id, f.title, c.first_name, c.last_name, r.rental_date, r.return_date, r.staff_id
        FROM rental r
        JOIN inventory i ON r.inventory_id = i.inventory_id
        JOIN film f ON i.film_id = f.film_id
        JOIN customer c ON r.customer_id = c.customer_id
        WHERE r.rental_id = :rentalId";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['rentalId' => $rentalId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
    exit;
}
}

// Registrar un nuevo alquiler(me fallo la primera funcion de modo que intentamos una mejorada)
function registrarAlquiler($pdo, $data) {
    try {
        if (empty($data['customer_id']) || empty($data['inventory_id']) || empty($data['rental_date']) || empty($data['staff_id'])) {
            throw new Exception("Todos los campos son requeridos.");
        }

        $sql = "
            INSERT INTO rental (customer_id, inventory_id, rental_date, staff_id)
            VALUES (:customer_id, :inventory_id, :rental_date, :staff_id)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        return true;

    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        return false;
    } catch (Exception $e) {
        echo "<h1>Error en los datos de entrada:</h1> <p>" . $e->getMessage() . "</p>";
        return false;
    }
}


// Registrar una devolución
/* function devolverAlquiler($pdo, $data) {
    try {
        $sql = "
            UPDATE rental
            SET return_date = :return_date
            WHERE rental_id = :rental_id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false; // No se actualizó ningún registro
        }
    } catch (Exception $e) {
        // Si hay un error en la base de datos, lo capturamos
        return false;
    }
} */

//CREAMOS OTRA FUNCION, A VER SI FUNCIONA MEJOR
function devolverAlquiler($pdo, $data) {
    $sql = "
        UPDATE rental
        SET return_date = :return_date
        WHERE rental_id = :rental_id";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        // Verificar si se actualizó algún registro
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            // Si no se actualizó ninguna fila, esto indica que no se encontró el alquiler o ya estaba devuelto
            return false;
        }
    } catch (PDOException $e) {
        // Si hay un error, lo registramos
        error_log("Error al ejecutar la consulta: " . $e->getMessage());
        return false;
    }
}

//PARA ACTUALIZAR ALQUILER, IMPORTANTE PARA PODER REGISTRAR DEVOLUCION!!
function actualizarDevolucion($pdo, $data) {
    $sql = "UPDATE rental SET return_date = :return_date WHERE rental_id = :rental_id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}


// Eliminar un alquiler
function eliminarAlquiler($pdo, $rentalId) {
    try{
    $sql = "
        DELETE FROM rental
        WHERE rental_id = :rental_id";
    
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['rental_id' => $rentalId]);
} catch (PDOException $e) {
    echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
    exit;
}
}

//Esta la he buscado, pero no la entiendo muy bien, REVISARLA PARA ENTENDERLO
function buscarAlquileres($pdo, $customer_id = '', $estado = '') {
    try {
        // Crear la consulta SQL para buscar alquileres
        $sql = "SELECT r.rental_id, r.rental_date, r.return_date, f.title AS film_title, c.first_name, c.last_name, s.first_name AS staff_first_name, s.last_name AS staff_last_name
                FROM rental r
                JOIN customer c ON r.customer_id = c.customer_id
                JOIN inventory i ON r.inventory_id = i.inventory_id
                JOIN film f ON i.film_id = f.film_id
                JOIN staff s ON r.staff_id = s.staff_id
                WHERE 1";  // La condición 1 es siempre verdadera, así que no afecta el resultado

        if ($customer_id) {
            $sql .= " AND r.customer_id = :customer_id";
        }

        // Si se ha seleccionado un estado (activo o devuelto)
        if ($estado !== '') {
            if ($estado == 1) {
                $sql .= " AND r.return_date IS NULL"; //si fecha de vuelta es nula
            } else {
                // Filtrar alquileres devueltos
                $sql .= " AND r.return_date IS NOT NULL";
            }
        }

        $stmt = $pdo->prepare($sql);
        if ($customer_id) {
            $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<h1>Error en la consulta:</h1> <p>" . $e->getMessage() . "</p>";
        exit;
    }
}

// EXTRA, HISTORIAL DE CLIENTES
function obtenerHistorialAlquileres($pdo, $customer_id) {
    // Query para obtener los alquileres de un cliente, con los detalles de las películas
    $sql = "
        SELECT f.title, r.rental_date, r.return_date, r.status
        FROM rental r
        JOIN inventory i ON r.inventory_id = i.inventory_id
        JOIN film f ON i.film_id = f.film_id
        WHERE r.customer_id = :customer_id
        ORDER BY r.rental_date DESC";  // Ordenar por fecha de alquiler, la más reciente primero
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':customer_id' => $customer_id]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



