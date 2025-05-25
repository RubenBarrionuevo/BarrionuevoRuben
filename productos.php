<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Ruben Barrionuevo Jimenez">
    <title>Listado de productos</title>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="presentacion/script.js"></script>
    <link rel="stylesheet" type="text/css" href="presentacion/estilo.css"/>
</head>
<body>
<section>
<?php 
require_once('funciones/obtener_productos.php'); 
if (isset($_GET['exito'])) {
    echo '<script>alert("' . htmlspecialchars($_GET['exito']) . '");</script>';
}
if (isset($_GET['error'])) {
  echo '<script>alert("' . htmlspecialchars($_GET['error']) . '");</script>';
}
?>
<!-- Volvemos a usar la misma tabla CSS que usamos para el ejercicio número uno de esta misma asignatura -->
  <h1>Listado de productos</h1>
<!-- Menu de categorias creado con input type radio -->
<div style="text-align:center;">
<form style="display:inline" action="productos.php" method="post">
<div class="radio-inputs">
  <label class="radio">
    <input type="radio" name="radio" checked="" value="lacteos">
    <span class="name">Lacteos</span>
  </label>
  <label class="radio">
    <input type="radio" name="radio" value="conservas"><span class="name">Conservas</span>
  </label>
  <label class="radio">
    <input type="radio" name="radio" value="bebidas"><span class="name">Bebidas</span>
  </label>
  <label class="radio">
    <input type="radio" name="radio" value="snacks"><span class="name">Snacks</span>
  </label>
  <label class="radio">
    <input type="radio" name="radio" value="dulces"><span class="name">Dulces</span>
  </label>
  <label class="radio">
    <input type="radio" name="radio" value="otros"><span class="name">Otros</span>
  </label>
  <label class="radio">
    <input type="radio" name="radio" value=""><span class="name">Todos</span>
  </label>

  <label class="radio">
  <input style="display:inline" type="submit" name="Filtrar" value="Buscar">
  </label>
</div>
</form>
</div>
<!-- Fin de menu -->
<!-- Inicio tabla para visualizar los datos -->
  <div class="tbl-header">
    <table cellpadding="0" cellspacing="0" border="0">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Código EAN</th>
          <th>Categoria</th>
          <th>Propiedades</th>
          <th>Unidades</th>
          <th>Precio</th>
          <th>Modificar stock</th>
          <th>Eliminar producto</th>
        </tr>
      </thead>
    </table>
  </div>
  <div class="tbl-content">
    <table cellpadding="0" cellspacing="0" border="0">
      <tbody>
        <?php
        /** 
         * Mediante el siguiente bloque if comprobamos si el servidor recibe una peticion de tipo post
         */
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $categoria = $_POST['radio'] ?? null;
        
          if ($categoria) {
            $productos = obtener_productos($pdo,$categoria);
          } else {
            $productos = obtener_productos($pdo);
          }
        } else{
          $productos = obtener_productos($pdo);
        }
            foreach ($productos as $producto) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($producto['nombre']) . '</td>';
                echo '<td>' . htmlspecialchars($producto['codigo_ean']) . '</td>';
                echo '<td>' . htmlspecialchars($producto['categoria']) . '</td>';
                echo '<td>' . htmlspecialchars($producto['propiedades'] ?? 'No descritas') . '</td>';
                echo '<td>' . htmlspecialchars($producto['unidades']) . '</td>';
                echo '<td>' . htmlspecialchars($producto['precio']) . '</td>';
                echo '<td>';
                echo '<form action="funciones/modificarproducto.php" method="post"><input type="hidden" name="id" value="'.$producto['id'].'"/>';
                echo '<input type="text" name="alterar"/>';
                echo '<button style="margin-top:5px" type="submit" class="button"><span>Añadir/Retirar</span></button>';
                echo '</form>';
                echo '</td>';
                echo '<td>';
                echo '<form action="funciones/eliminar.php" method="post"><input type="hidden" name="id" value="'.$producto['id'].'"/>';
                echo '<button style="margin-top:5px" type="submit" class="button"><span>Eliminar</span></button>';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }
        ?>
      </tbody>
    </table>
  </div>
</section>
</body>
</html>
