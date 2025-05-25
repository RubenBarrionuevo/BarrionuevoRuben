<?php
/**
 * Incluye la configuración de conexión a la base de datos mediante PDO.
 */
require_once('conexion.php');

/**
 * Instancia la conexión PDO a la base de datos.
 *
 * @var PDO $pdo Objeto de conexión PDO.
 */
$pdo = conexionpdo();

/**
 * Obtiene un listado de productos desde la base de datos.
 *
 * Si se pasa una categoría, filtra los productos por dicha categoría.
 * Si no se especifica categoría, devuelve todos los productos.
 *
 * @param PDO $pdo Objeto de conexión PDO.
 * @param string|null $categoria (Opcional) Categoría para filtrar productos. Por defecto es null (sin filtro).
 * @return array|false Retorna un array con los productos o false en caso de error.
 */
function obtener_productos($pdo, $categoria = null){
    try {
        /**
         * Consulta SQL para obtener productos.
         *
         * Se ajusta según si se ha definido una categoría para filtrar.
         *
         * @var string $query
         */
        if ($categoria == null) {
            $query = 'SELECT * FROM productos';
        } else {
            /**
             * Consulta para filtrar por categoría.
             * Si no existen coincidencias devuelve un array vacío.
             */
            $query = 'SELECT * FROM productos WHERE categoria = :categoria';
        }

        /**
         * Prepara la consulta SQL.
         *
         * @var PDOStatement $sth
         */
        $sth = $pdo->prepare($query);

        if ($categoria !== null) {
            /**
             * Asigna el valor de la categoría para la consulta preparada.
             */
            $sth->bindParam(':categoria', $categoria);
        }

        /**
         * Ejecuta la consulta.
         */
        $sth->execute();

        /**
         * Obtiene todos los productos devueltos por la consulta.
         * Por defecto usa PDO::FETCH_BOTH.
         *
         * @var array $productos
         */
        $productos = $sth->fetchAll();

        return $productos;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

/**
 * Ejemplos de uso de la función obtener_productos:
 *
 * - Obtener todos los productos sin filtro de categoría:
 *   $productos = obtener_productos($pdo);
 *
 * - Obtener productos filtrados por categoría (por ejemplo, 'bebidas'):
 *   $productos = obtener_productos($pdo, 'bebidas');
 *
 * Este funcionamiento puede observarse en el script productos.php.
 */
