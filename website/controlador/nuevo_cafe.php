<?php
/*Control de seguridad*/
session_start();
if ($_SESSION["autenticado"]==false){echo "<script>window.location='/';</script>";}

/*Leemos la ficha de usuario*/
require_once($_SERVER['DOCUMENT_ROOT'] .'/controlador/clsuser.php'); $usuario = new Usuario();

/*recogemos las variables*/
$fecha = $_POST["fecha"];
$cantidad = $_POST["cantidad"];
// grabamos la info
$usuario->nuevoCafe($_SESSION["idusuario"], $fecha, $cantidad);

header('Location: /perfil.php');

?>