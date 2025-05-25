<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Ruben Barrionuevo Jimenez">
    <title>Añadir nuevo producto</title>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="presentacion/script.js"></script>
    <link rel="stylesheet" type="text/css" href="presentacion/estilo.css"/>
    <link rel="stylesheet" type="text/css" href="presentacion/formulario.css"/>
</head>
<body>
<section>
<div class="container">
    <form action="funciones/guardar_producto.php" method="post">
        <h1>Añadir nuevo producto</h1>
        <div class="form-group">
            <input type="text" required="required" name="nombrep"/>
            <label class="control-label">Nombre producto</label><i class="bar"></i>
        </div>
        <div class="form-group">
            <input type="text" required="required" name="codigoean"/>
            <label class="control-label">Codigo EAN</label><i class="bar"></i>
        </div>
        <div class="form-group">
            <input type="text" required="required" name="unidades"/>
            <label class="control-label">Unidades</label><i class="bar"></i>
        </div>
        <div class="form-group">
            <input type="text" required="required" name="precio"/>
            <label class="control-label">Precio</label><i class="bar"></i>
        </div>
        <div class="form-group">
            <select name="categoria">
                <option value="lacteos">Lacteos</option>
                <option value="conservas">Conservas</option>
                <option value="bebidas">Bebidas</option>
                <option value="snacks">Snacks</option>
                <option value="dulces">Dulces</option>
                <option value="otros">Otros</option>
            </select>
            <label for="select" class="control-label">Categoria</label><i class="bar"></i>
        </div>

        <div class="form-group">
            <label>Propiedades</label><i class="bar"></i>
            <div class="checkbox">
                <label><input type="checkbox" name="propiedades[]" value="sin gluten"/><i class="helper"></i>sin gluten</label>
            </div>
            <div class="checkbox">
                <label><input type="checkbox" name="propiedades[]" value="sin lactosa"/><i class="helper"></i>sin lactosa</label>
            </div>
            <div class="checkbox">
                <label><input type="checkbox" name="propiedades[]" value="vegano"/><i class="helper"></i>vegano</label>
            </div>
            <div class="checkbox">
                <label><input type="checkbox" name="propiedades[]" value="orgánico"/><i class="helper"></i>orgánico</label>
            </div>
            <div class="checkbox">
                <label><input type="checkbox" name="propiedades[]" value="sin conservantes"/><i class="helper"></i>sin conservantes</label>
            </div>
        </div>

        <div class="button-container">
            <button type="submit" class="button"><span>Añadir producto</span></button>
        </div>
    </form>
<?php
//Con este  bloque if captamos errores que se hayan podido producir al intentar crear un prodcuto.
if (isset($_GET['error'])) {
    //Capturamos si existe un GET de 'error', si lo existe, mediante un echo generamos una alerta con la información
    echo '<script>alert("' . htmlspecialchars($_GET['error']) . '");</script>';
}
?>
</div>
</section>
</body>
</html>