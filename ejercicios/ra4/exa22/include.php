<?php

$usuarios = [
  '123456' => [
    'name' => 'Fernando Muñoz',
    'pass' => password_hash('abc123', PASSWORD_DEFAULT)
  ],
  '654321' => [
    'name' => 'Fernanda Gonzales',
    'pass' => password_hash('abc123', PASSWORD_DEFAULT)

  ]
];

$conciertos = [
  'chi01' => [
    'name' => 'chicago el muscial',
    'f1_10' => 25,
    'f11_20' => 20
  ],
  'can02' => [
    'name' => 'Concierto de año nuevo',
    'f1_10' => 25,
    'f11_20' => 15
  ],
  'ope03' => [
    'name' => 'Opera Don Giovani',
    'f1_10' => 30,
    'f11_20' => 25
  ],
  'ama04' => [
    'name' => 'Amadeus',
    'f1_10' => 40,
    'f11_20' => 35
  ],
];

function comprobarJWT()
{
  if (!isset($_COOKIE['jwt'])) {
    $_SESSION['errores'][] = 'La session ha caducado';
    header('location: 01login.php');
    exit();
  }

  $usuario = verificarJWT($_COOKIE['jwt']);
  if (!$usuario) {
    $_SESSION['errores'][] = 'No se ha podido verficiar el usuario';
    header('location: 01login.php');
    exit();
  }
  return $usuario;
}
