<?php

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

// 1. sanear-validar

$sanear = [
  'id' => FILTER_SANITIZE_SPECIAL_CHARS,
  'nombre' => FILTER_SANITIZE_SPECIAL_CHARS,
  'pass' => FILTER_DEFAULT,
];

$datos_saneados = filter_input_array(INPUT_POST, $sanear, true);

if (!$datos_saneados['id']) $_SESSION['errores'][] = 'No se ha introducido Identificador';
if (!$datos_saneados['nombre']) $_SESSION['errores'][] = 'No se ha introducido Contraseña';
if (!$datos_saneados['pass']) $_SESSION['errores'][] = 'No se ha introducido Nombre';
if (isset($_SESSION['errores'])) {
  header('location: 01inicio.php');
  exit();
}

if (strlen($datos_saneados['id']) !== 6) {
  $_SESSION['errores'][] = 'El identificador debe tener 6 caracteres';
  header('location: 01inicio.php');
  exit();
}

if (!array_key_exists($datos_saneados['id'], $usuarios)) {
  $_SESSION['errores'][] = 'Identificador no registrado';
  header('location: 01inicio.php');
  exit();
}

$datos_saneados['nombre'] = trim($datos_saneados['nombre']);
$usuario = $usuarios[$datos_saneados['id']];

if (strtolower($datos_saneados['nombre']) !=  strtolower($usuario['nombre'])) {
  $_SESSION['errores'][] = 'Nombre no casa con Identificador ';

  header('location: 01inicio.php');
  exit();
}

if (!password_verify($datos_saneados['pass'], $usuario['pass'])) {
  $_SESSION['errores'][] = 'Contraseña incorrecta';
  header('location: 01inicio.php');
  exit();
}

// 2. jwt y payload;

$payload = [
  'id' => $datos_saneados['id'],
  'nombre' => $usuario['nombre']
];

$jwt = generarJWT($payload);

if (!$jwt) {
  $_SESSION['errores'][] = 'no se ha podido verificar el jwt';
  header('location: 01inicio.php');
  exit();
}

setcookie('jwt', $jwt, time() + 1 * 24 * 60 * 60, '/');
date_default_timezone_set('Europe/Madrid');
$_SESSION['fecha'] = new DateTime();
$_SESSION['carrito'] = [];

header('location: 03entradas.php');
