<?php

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
$productos = [
  'az'  => ['name' => 'Azúcar blanco 1kg',            'precio' => 1.20],
  'ar'  => ['name' => 'Arroz redondo 1kg',            'precio' => 1.35],
  'pa'  => ['name' => 'Paquete de pasta 500g',        'precio' => 0.95],
  'ac'  => ['name' => 'Aceite de oliva 1L',           'precio' => 5.40],
  'sa'  => ['name' => 'Sal fina 1kg',                 'precio' => 0.50],
  'le'  => ['name' => 'Leche entera 1L',              'precio' => 0.90],
  'ha'  => ['name' => 'Harina de trigo 1kg',          'precio' => 1.10],
  'paM' => ['name' => 'Pan molde 450g',               'precio' => 1.80],
  'ca'  => ['name' => 'Café molido 250g',             'precio' => 2.95],
  'ga'  => ['name' => 'Galletas surtidas 800g',       'precio' => 3.20],
];
