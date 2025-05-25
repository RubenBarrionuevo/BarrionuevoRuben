<?php
/**
 * Script para recibir datos de un formulario para crear un nuevo producto
 * Valida los datos, comprueba unicidad y los inserta en la base de datos.
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
     * Asignación y saneamiento de datos recibidos del formulario.
     * @var string $nombrep Nombre del producto, en minúsculas y saneado.
     * @var string $codigoean Código EAN saneado como entero.
     * @var string $unidades Unidades saneadas como entero.
     * @var string $precio Precio saneado como float.
     * @var string $categoria Categoría saneada como string.
     */
    $nombrep = filter_var(strtolower($_POST['nombrep']), FILTER_SANITIZE_STRING);
    $codigoean = filter_var($_POST['codigoean'], FILTER_SANITIZE_NUMBER_INT);
    $unidades = filter_var($_POST['unidades'], FILTER_SANITIZE_NUMBER_INT);
    $precio = filter_var($_POST['precio'], FILTER_SANITIZE_NUMBER_FLOAT);
    $categoria = filter_var($_POST['categoria'], FILTER_SANITIZE_STRING);

    /**
     * Validaciones de los datos recibidos
     */
    
    // Validar que el nombre del producto no esté vacío
    if (empty($nombrep)) {
        header("Location: ../nuevoproducto.php?error=No+se+ha+definido+un+nombre+de+producto");
        return;
    }

    // Validar longitud máxima del nombre (máx 30 caracteres)
    if (strlen($nombrep) > 30) {
        header("Location: ../nuevoproducto.php?error=El+nombre+del+producto+es+demasiado+largo");
        return;
    }

    // Validar longitud del código EAN entre 8 y 13 caracteres
    if (strlen($codigoean) < 8 || strlen($codigoean) > 13) {
        header("Location: ../nuevoproducto.php?error=La+longitud+del+codigo+EAN+debe+tener+entre+8+y+13+caracteres+de+longitud");
        return;
    }

    /**
     * Comprobar que el código EAN no exista ya en la base de datos.
     */
    try {
        $query_test = "SELECT COUNT(*) FROM productos WHERE codigo_ean = :codigoean";
        $mirar = $pdo->prepare($query_test);
        $mirar->bindParam(':codigoean', $codigoean);
        $mirar->execute();
        $existe = $mirar->fetchColumn();

        // Si ya existe el código, redirigir con error
        if ($existe > 0) {
            header("Location: ../nuevoproducto.php?error=El+codigo+EAN+introducido+ya+se+encuentra+registrado+en+la+base+de+datos");
            return;
        }
    } catch (PDOException $e) {
        header("Location: ../nuevoproducto.php?error=Ha+ocurrido+una+excepcion+con+el+codigo+EAN");
        return;
    }

    // Validar que las unidades sean un entero mayor que 0
    if (!ctype_digit($unidades) || intval($unidades) <= 0) {
        header("Location: ../nuevoproducto.php?error=Las+unidades+deben+ser+un+numero+entero+mayor+a+0");
        return;
    }

    // Validar que el precio sea un número decimal mayor que 0
    if (!is_numeric($precio) || $precio < 0) {
        header("Location: ../nuevoproducto.php?error=El+precio+debe+ser+un+numero+decimal+mayor+a+0");
        return;
    }

    /**
     * Validar categoría recibida desde el formulario
     */
    if (isset($_POST['categoria'])) {
        $categoria = $_POST['categoria'];
    } else {
        $categoria = [];
    }

    // Categorías válidas
    $categoriasok = ['lacteos', 'conservas', 'bebidas', 'snacks', 'dulces', 'otros'];

    if (!in_array($categoria, $categoriasok)) {
        header("Location: ../nuevoproducto.php?error=La+categoria+seleccionada+no+es+valida");
        return;
    }

    /**
     * Validar propiedades recibidas desde el formulario
     */
    if (isset($_POST['propiedades'])) {
        $propiedades = $_POST['propiedades'];
    } else {
        $propiedades = [];
    }

    // Propiedades válidas
    $propiedadesok = ['sin gluten', 'sin lactosa', 'vegano', 'orgánico', 'sin conservantes'];

    if (count($propiedades) > 0) {
        foreach ($propiedades as $propiedad) {
            if (!in_array($propiedad, $propiedadesok)) {
                header("Location: ../nuevoproducto.php?error=No+se+esperaba+la+propiedad+que+se+ha+introducido");
                return;
            }
        }
    }

    // Convertir array de propiedades a cadena para almacenar
    $propiedadesf = implode(',', $propiedades);

    /**
     * Insertar el nuevo producto en la base de datos.
     */
    try {
        $query = "INSERT INTO productos (nombre, codigo_ean, unidades, precio, categoria, propiedades) 
          VALUES (:nombrep, :codigoean, :unidades, :precio, :categoria, :propiedades)";
        $sth2 = $pdo->prepare($query);
        $sth2->bindParam(':nombrep', $nombrep);
        $sth2->bindParam(':codigoean', $codigoean);
        $sth2->bindParam(':unidades', $unidades);
        $sth2->bindParam(':precio', $precio);
        $sth2->bindParam(':categoria', $categoria);
        $sth2->bindParam(':propiedades', $propiedadesf);
        $sth2->execute();

        // Obtener el ID generado del producto insertado
        $idgenerado = $pdo->lastInsertId();

        header("Location: ../productos.php?exito=Enhorabuena+has+creado+el+producto+" . $nombrep . "+con+ID:+" . $idgenerado);

    } catch (PDOException $e) {
        // Mostrar alerta con el error producido
        echo '<script>alert("Error al insertar el producto: ' . $e->getMessage() . '");</script>';
    }
}
