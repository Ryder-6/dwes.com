<?php
$usuarios = [
  'pepe@gmail.com' => [
    'pass' => password_hash('pepe123', PASSWORD_DEFAULT),
    'name' => 'Jose Garcia'
  ],
  'pepa@gmail.com' => [
    'pass' => password_hash('pepa123', PASSWORD_DEFAULT),
    'name' => 'Josefa Garcia'
  ],
];

$productos = [
  'lata01' => [
    'name' => 'lata de atun',
    'precio' => 3.5,
  ],
  'docena02' => [
    'name' => 'docena de huevos',
    'precio' => 2.5
  ],
  'garba03' => [
    'name' => 'paquete de garbanzos',
    'precio' => 3.25
  ],
  'morci04' => [
    'name' => 'morcilla',
    'precio' => 4.15
  ]
];

function comprobarJWT()
{
  if (!isset($_COOKIE['jwt'])) {
    $_SESSION['errores'][] = 'Sesion ha caducado';
    header('location: 01login.php');
    exit();
  }
  $usuario = verificarJWT($_COOKIE['jwt']);
  if (!$usuario) {
    $_SESSION['errores'][] = 'No se ha podido verificar el usuario';
    header('location: 01login.php');
    exit();
  }
  return $usuario;
}

?>
