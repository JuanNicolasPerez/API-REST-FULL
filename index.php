<?php

// REQUERIMOS LOS CONTROLADORES
require_once "controladores/rutasControlador.php";
require_once "controladores/cursosControlador.php";
require_once "controladores/clientesControlador.php";

// REQUERIMOS LOS MODELOS
require_once "modelos/cursos.modelo.php";
require_once "modelos/cursos.modelo.php";

// CREAMOS EL OBJETO DE LA CLASE
$rutas = new ControladorRutas();

// LLAMAMOS AL METODO O FUNSION inicio()
$rutas ->inicio();

?>