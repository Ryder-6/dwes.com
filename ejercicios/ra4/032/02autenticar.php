<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');

// 1. sanear
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$pass = $_POST['pass'];

if (!$email) $_SESSION['errores'][] = 'Email, no enviado';
if (!$pass) $_SESSION['errores'][] = 'Contraseña, no enviada';

if (isset($_SESSION['errores'])) header("Location: 01login.php");



// 2. validar login

$usuarios = [
  'juan@loquesea.com' => [
    'nombre'    => "Juan García",
    'pass'     => password_hash("juan123", PASSWORD_DEFAULT),
    'direccion' => "c/Mayor, 5-3º-B",
    'telefono'  => 600101010
  ],
  'maria@loquesea.com' => [
    'nombre'    => "María Gómez",
    'pass'     => password_hash("maria123", PASSWORD_DEFAULT),
    'direccion' => "Av La Palmera, 55-2º-B",
    'telefono'  => 600202020
  ],
  'pepe@pe.com' => [
    'nombre' => 'Pepe perez',
    'pass' => password_hash(123, PASSWORD_DEFAULT),
    'direccion' => "Av La Palmera, 55-2º-B",
    'telefono'  => 600202020
  ]
];

if (!array_keys($usuarios, $email)) {
  $_SESSION['errores'][] = "el usuario $email no existe";
  header("Location: 01login.php");
}

if (!password_verify($pass, $usuarios[$email]['pass'])) {
  $_SESSION['errores'][] = 'la contraseña no es valida';
  header("Location: 01login.php");
}


// 3. payload
$payload = [
  'email' => $email,
  'nombre' => $usuarios[$email]['nombre'],
  'direccion' => $usuarios[$email]['direccion'],
  'telefono' => $usuarios[$email]['telefono']
];

$jwt = generarJWT($payload);
setcookie('jwt', $jwt, time() + 20 * 60, '/');

header('Location: 03tipo.php');
