<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');


$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
$name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
$pass = $_POST['pass'];

if (!$id) $_SESSION['errores'][] = 'no se ha indicado identificador';
if (!$name) $_SESSION['errores'][] = 'no se ha indicado nombre';
if (!$pass) $_SESSION['errores'][] = 'no se ha indicado password';

if (isset($_SESSION['errores'])) {
  header('location: 01login.php');
  exit();
}

if (!array_key_exists($id, $usuarios)) {
  $_SESSION['errores'][] = 'Identificador no valido';
  header('location: 01login.php');
  exit();
}

if (strtolower($name) != strtolower($usuarios[$id]['name'])) {
  $_SESSION['errores'][] = 'nombre no asociado a identificador';
  header('location: 01login.php');
  exit();
}

if (!password_verify($pass, $usuarios[$id]['pass'])) {
  $_SESSION['errores'][] = 'Contraseña incorrecta';
  header('location: 01login.php');
  exit();
}

$usuario = $usuarios[$id];




$payload = [
  'id' => $id,
  'name' => $usuario['name']
];

$jwt = generarJWT($payload);

date_default_timezone_set('Europe/Madrid');

$_SESSION['fecha'] = new DateTime();
$_SESSION['carrito'] = [];

setcookie('jwt', $jwt, time() + 1 * 24 * 60 * 60, '/');

header('location: 03entradas.php');
