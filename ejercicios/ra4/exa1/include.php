<?php

$entradas = [
  'chi01' => [
    'name' => 'chicago, el musical',
    'fila1_10' => 25,
    'fila11_20' => 20
  ],
  'can02' => [
    'name' => 'concierto de año nuevo',
    'fila1_10' => 25,
    'fila11_20' => 15
  ],
  'ope03' => [
    'name' => 'opera don Giovani',
    'fila1_10' => 30,
    'fila11_20' => 25
  ],
  'ama04' => [
    'name' => 'Amadeus',
    'fila1_10' => 40,
    'fila11_20' => 35
  ]
];

$usuarios = [
  '123456' => [
    'nombre' => 'Fernando Muñoz',
    'pass' => password_hash('abc123', PASSWORD_DEFAULT)
  ],
  '654321' => [
    'nombre' => 'Fernanda Muñoz',
    'pass' => password_hash('321cba', PASSWORD_DEFAULT)
  ]
];
