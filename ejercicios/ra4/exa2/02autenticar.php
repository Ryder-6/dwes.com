<?php

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');


$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_var($email, FILTER_VALIDATE_EMAIL);

$nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS));

$pass = $_POST['pass'];

if (!$email) $_SESSION['errores'][] = 'email no indicado';
if (!$nombre) $_SESSION['errores'][] = 'nombre no indicado';
if (!$pass) $_SESSION['errores'][] = 'contraseña no indicada';

if (isset($_SESSION['errores'])) {
  header('location: 01login.php');
  exit();
}

if (!array_key_exists($email, $usuarios)) {
  $_SESSION['errores'][] = 'email no registrado';
  header('location: 01login.php');
  exit();
}

if (strtolower($nombre) != strtolower($usuarios[$email]['name'])) {
  $_SESSION['errores'][] = 'Nombre no coincide';
  header('location: 01login.php');
  exit();
}

if (!password_verify($pass, $usuarios[$email]['pass'])) {
  $_SESSION['errores'][] = 'Contraseña incorrecta';
  header('location: 01login.php');
  exit();
}

$usuario = $usuarios[$email];

date_default_timezone_set('UTC +1');
$_SESSION['fecha'] = new DateTime();
$_SESSION['carrito'] = [];

$payload = [
  'email' => $email,
  'name' => $usuario['name']
];

$jwt = generarJWT($payload);

setcookie('jwt', $jwt, time() + 1 * 24 * 60 * 60, '/');

header('location: 03carrito.php');
?>