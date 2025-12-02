<?php


require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');

session_start();

// 1. verificar JWT
if (!isset($_SESSION['jwt'])){
$_SESSION['errores'][] = 'No se ha iniciado sesion';
header('Location: 01login.php');
}


$usuario = verificarJWT($_COOKIE['jwt']);

if (!$usuario) {
  $_SESSION['errores'][] = "No se ha podido verificar el usuario";
  header('location: 01login.php');
}

?>
<h1>Bienvenido <?= $usuario['nombre'] ?></h1>
<h2>Datos de usuario</h2>
<h3>email: <?= $usuario['email'] ?></h3>
<h3>direccion <?= $usuario['direccion'] ?></h3>
<h3>telefono <?= $usuario['telefono'] ?></h3>

<?php

fin_html();
?>