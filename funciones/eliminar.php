<?php
/**
 * Script para eliminar un producto de la base de datos por su ID.
 *
 * Recibe el ID del producto a eliminar vía POST, valida y sanea el dato,
 * ejecuta la consulta DELETE en la base de datos y redirige con mensaje de éxito.
 *
 * @author Ruben Barrionuevo
 * @version 1.0.0
 * @license Creative Commons Atribution
 * @package Productos
 */

require_once('conexion.php');

/**
 * Establece la conexión PDO con la base de datos.
 *
 * @return PDO Objeto de conexión PDO.
 */
$pdo = conexionpdo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
     * ID del producto a eliminar, saneado como entero.
     *
     * @var int $id
     */
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    try {
        /**
         * Consulta preparada para eliminar el producto con el ID especificado.
         *
         * @var PDOStatement $adios
         */
        $eliminar = "DELETE FROM productos WHERE id = :id;";
        $adios = $pdo->prepare($eliminar);
        $adios->bindParam(':id', $id);
        $adios->execute();

        // Confirmamos que la operación se realizó correctamente
        header("Location: ../productos.php?exito=Enhorabuena+has+eliminado+el+producto+con+id+" . $id);

    } catch (PDOException $e) {
        // Mostrar mensaje de error en caso de excepción
        echo "Error al procesar la solicitud: " . $e->getMessage();
    }
}
