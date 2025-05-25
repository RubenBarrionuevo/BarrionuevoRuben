<?php
//Hacemos un requiere_once al script donde tenemos la configuración de la conexión
require_once(__DIR__ . '/../etc/conf.php');
function conexionpdo(){
//Debido a que la variable $dsn se trata de una variable global, tendremos que definirlo dentro de la propia función
global $dsn;
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES => FALSE];
    try {
        $pdo = new PDO($dsn, user, pass, $options);
        //Si la conexión es correcta nos devolverá la variable $pdo que almacena la instancia con la conexión
        return $pdo;
    } catch (PDOException $e) {
        echo 'Error detectado: ' . $e->getMessage();
        //En caso de error en la conexión, nos retornara el valor false, además de mostrar el error mediante un echo
        return false;
    }
}

//Llamamos a la conexión pdo sin parametro alguno.
conexionpdo();
