<?php
/*Control de seguridad*/
session_start();
if ($_SESSION["autenticado"]==false){echo "<script>window.location='/';</script>";}

/*Leemos la ficha de usuario*/
require_once($_SERVER['DOCUMENT_ROOT'] .'/controlador/clsuser.php'); $usuario = new Usuario();

/*recogemos las variables*/
$id_usuario = $_POST["id_usuario"];
$fecha = $_POST["fecha"];
$cafes = $_POST["cafes"];
// grabamos la info
$usuario->nuevoPagoCafe($id_usuario, $fecha, $cafes);

header('Location: /admin.php');

?>