<?php

function comprobarJWT()
{
  if (!isset($_COOKIE['jwt'])) {
    $_SESSION['errores'][] = 'Sesion caducada';
    header('location: 01login.php');
    exit();
  }

  $usuario = verificarJWT($_COOKIE['jwt']);
  if (!$usuario) {
    $_SESSION['errores'][] = 'No se ha podido verificar';
    header('location: 01login.php');
    exit();
  }
  return $usuario;
}

$usuarios = [
  'pepe@mail.com' => [
    'nombre' => 'pepe perez',
    'telefono' => '852123654',
    'pass' => password_hash('123', PASSWORD_DEFAULT),
  ],
  'pepa@mail.com' => [
    'nombre' => 'pepa perez',
    'telefono' => '852123654',
    'pass' => password_hash('123654', PASSWORD_DEFAULT),
  ],
  'pepo@mail.com' => [
    'nombre' => 'pepo perez',
    'telefono' => '852123654',
    'pass' => password_hash('123', PASSWORD_DEFAULT),
  ]
];
