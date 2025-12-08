<?php

function comprobarJWT()
{
  if (!$_COOKIE['jwt']) {
    $_SESSION['errores'][] = 'Session ha caducado';
    header('location: 01login.php');
    exit();
  }

  $usuario = verificarJWT($_COOKIE['jwt']);
  if (!$usuario) {
    $_SESSION['errores'][] = 'no se ha podido verificar el usuario';
    header('location: 01login.php');
    exit();
  }
  return $usuario;
}

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
    'pass' => password_hash('123', PASSWORD_DEFAULT),
    'direccion' => "Av La Palmera, 55-2º-B",
    'telefono'  => 600202020
  ]
];

$modelos = [
  'mo' => ['name' => 'monroy', 'precio' => 20000],
  'mu' => ['name' => 'muchopami', 'precio' => 21000],
  'za' => ['name' => 'zapatoveloz', 'precio' => 22000],
  'gu' => ['name' => 'guperino', 'precio' => 25500],
  'al' => ['name' => 'alomejor', 'precio' => 29750],
  'te' => ['name' => 'telapegas', 'precio' => 32550]
];

$motores = [
  'ga' => ['name' => 'gasolina', 'precio' => 0],
  'di' => ['name' => 'diesel', 'precio' => 2000],
  'hi' => ['name' => 'hibrido', 'precio' => 5000],
  'el' => ['name' => 'electrico', 'precio' => 10000],
];

$pinturas = [
  'gt' => ['name' => 'gris triste', 'precio' => 0],
  'rs' => ['name' => 'rojo sangre', 'precio' => 250],
  'rp' => ['name' => 'rojo pasion', 'precio' => 150],
  'an' => ['name' => 'azul nohce', 'precio' => 175],
  'ca' => ['name' => 'caramelo', 'precio' => 300],
  'ma' => ['name' => 'mango', 'precio' => 275],
];

$extras = [
  'gps' => ['name' => 'Navegador GPS', 'precio' => 500],
  'ca' => ['name' => 'calefaccion asientos', 'precio' => 250],
  'aat' => ['name' => 'antena aleta tiburon', 'precio' => 50],
  'asl' => ['name' => 'Arranque sin llave', 'precio' => 150],
  'ci' => ['name' => 'cargador inalambricos', 'precio' => 200],
  'ap' => ['name' => 'arranque en pendiente', 'precio' => 300],
  'cc' => ['name' => 'control de crucero', 'precio' => 500],
  'dam' => ['name' => 'detectar angulo muerto', 'precio' => 350],
  'fla' => ['name' => 'faros led automaticos', 'precio' => 400],
  'fe' => ['name' => 'frenada de emergencia', 'precio' => 375],
];

$financiacion = [
  '2a' => ['name' => '2 años', 'anios' => 2],
  '5a' => ['name' => '5 años', 'anios' => 5],
  '10a' => ['name' => '10 años', 'anios' => 10],
];
