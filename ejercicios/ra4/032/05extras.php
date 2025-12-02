<?php

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('04ingredientes.php');

// 1. validar jwt

if (!isset($_COOKIE['jwt'])) {
  $_SESSION['errores'][] = 'no se ha iniciado sesion';
  header('location: 01login.php');
  exit();
}
$usuario = verificarJWT($_COOKIE['jwt']);
if (!$usuario) {
  $_SESSION['errores'][] = 'login invalido';
  header('location: 01login.php');
  exit();
}

// sanear y validar ingredientes

if (empty($_POST['ingredientes'])) {
  $_SESSION['errores'][] = 'no se han añadido ingredientes';
  header('location: 01login.php');
  exit();
}

$ingredientes = filter_input(INPUT_POST, 'ingredientes', FILTER_SANITIZE_SPECIAL_CHARS, ['flags' => FILTER_REQUIRE_ARRAY]);

foreach ($ingredientes as $key) {
  if (!array_key_exists($key, ($_COOKIE['tipo'] ? $ingingredientes_veg : $ingredientes_no_veg))) {
    # code...
  }
}