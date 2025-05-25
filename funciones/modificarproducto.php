<?php
/**
 * Script para modificar el stock (unidades) de un producto existente en la base de datos.
 *
 * Recibe vía POST el ID del producto y el valor a alterar (positivo o negativo),
 * valida que el producto exista y que el stock resultante no sea menor o igual a cero,
 * actualiza la cantidad de unidades y redirige con mensajes de éxito o error.
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
     * ID del producto a modificar, saneado como entero.
     *
     * @var int $id
     */
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    /**
     * Valor a alterar en unidades (puede ser positivo o negativo), saneado como entero.
     *
     * @var int $alterar
     */
    $alterar = filter_input(INPUT_POST, 'alterar', FILTER_SANITIZE_NUMBER_INT);

    try {
        /**
         * Consulta para obtener las unidades actuales del producto.
         *
         * @var PDOStatement $veamos
         */
        $buscar = "SELECT unidades FROM productos WHERE id = :id";
        $veamos = $pdo->prepare($buscar);
        $veamos->bindParam(':id', $id);
        $veamos->execute();

        // Verifica si existe el producto con el ID dado
        if ($veamos->rowCount() > 0) {
            $detectado = $veamos->fetch(PDO::FETCH_ASSOC);

            /**
             * Unidades actuales del producto.
             *
             * @var int $unidades
             */
            $unidades = $detectado['unidades'];

            // Calcula el nuevo stock sumando el valor a alterar
            $unidadesalt = $unidades + $alterar;

            // Valida que el nuevo stock no sea menor o igual a cero
            if ($unidadesalt <= 0) {
                header("Location: ../productos.php?error=El+stock+de+un+producto+no+puede+ser+inferior+o+igual+a+0+unidades.");
                return;
            }

            // Prepara la consulta para actualizar las unidades en la base de datos
            $modificarunidades = "UPDATE productos SET unidades = :unidadesalt WHERE id = :id";
            $actualizar_sth = $pdo->prepare($modificarunidades);
            $actualizar_sth->bindParam(':unidadesalt', $unidadesalt);
            $actualizar_sth->bindParam(':id', $id);
            $actualizar_sth->execute();

            // Redirige con mensaje de éxito indicando el nuevo stock
            header("Location: ../productos.php?exito=Enhorabuena+has+modificado+el+producto+con+id+" . $id . "+con+un+stock+de+" . $unidadesalt . "+unidades");
        } else {
            // Redirige con mensaje de error si no se encontró el producto
            header("Location: ../productos.php?error=No+se+encontro+el+producto+con+el+ID+proporcionado.");
        }
    } catch (PDOException $e) {
        // Muestra mensaje en caso de error de base de datos
        echo "Error al procesar la solicitud: " . $e->getMessage();
    }
}
