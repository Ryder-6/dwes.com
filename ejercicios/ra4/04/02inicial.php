<?php

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['operacion'] == 'login') {
  // 1. sanear


  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $pass = $_POST['pass'];

  if (!$email) $_SESSION['errores'][] = 'No hay email';
  if (!$pass) $_SESSION['errores'][] = 'No hay contraseña';

  if (isset($_SESSION['errores'])) {
    header('location: 01login.php');
    exit();
  }

  // 2. validar usuario
  if (!array_key_exists($email, $usuarios)) {
    $_SESSION['errores'][] = "no existe usuario $email";
    header('location: 01login.php');
    exit();
  }

  if (!password_verify($pass, $usuarios[$email]['pass'])) {
    $_SESSION['errores'][] = "Contraseña incorrecta";
    header('location: 01login.php');
    exit();
  }

  // 3. payload
  $usuario = $usuarios[$email];
  $payload = [
    'email' => $email,
    'nombre' => $usuario['nombre'],
    'direccion' => $usuario['direccion'],
    'telefono'  => $usuario['telefono']
  ];

  $jwt = generarJWT($payload);
  setcookie('jwt', $jwt, time() + 1 * 24 * 60 * 60, '/');


  header('location: 03modelomotor.php');
} else {
  header('location: 01login.php');
}
