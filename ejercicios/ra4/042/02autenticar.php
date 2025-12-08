<?php

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

// 1. sanear validar
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_var($email, FILTER_VALIDATE_EMAIL);

$pass = $_POST['pass'];

if (!$email) $_SESSION['errores'][] = 'no se ha introducido email';
if (!$pass) $_SESSION['errores'][] = 'no se ha introducido password';

if (isset($_SESSION['errores'])) {
  header('location: 01login.php');
  exit();
}

// 2. verficiar usuario

if (!array_key_exists($email, $usuarios)) {
  $_SESSION['errores'][] = 'email no registrado';
  header('location: 01login.php');
  exit();
}

if (!password_verify($pass, $usuarios[$email]['pass'])) {
  $_SESSION['errores'][] = 'Contraseña incrorrecta';
  header('location: 01login.php');
  exit();
}

// payload y jwt

$usuario = $usuarios[$email];
$payload = [
  'email' => $email,
  'direccion' => $usuario['direccion'],
  'telefono' => $usuario['telefono']
];

$jwt = generarJWT($payload);
setcookie('jwt', $jwt, time() + 1 * 24 * 60 * 60, '/');

header('location: 03modelo.php');
