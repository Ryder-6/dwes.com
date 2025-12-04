<?php


session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

// 1. jwt

if (!isset($_COOKIE['jwt'])) {
  $_SESSION['errores'][] = 'ha caducado la session';
  header('location: 01login.php');
  exit();
}

$usuario = verificarJWT($_COOKIE['jwt']);
if (!$usuario) {
  $_SESSION['errores'][] = 'no se ha podido verificar el usuario';
  header('location: 01login.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['operacion'] == 'pago') {

  $pago = filter_input(INPUT_POST, 'pago', FILTER_SANITIZE_SPECIAL_CHARS);
  if (!array_key_exists($pago, $financiacion)) $_SESSION['errores'][] = 'metodo de financiacion invaldo';
  if (!isset($_SESSION['modelo'])) $_SESSION['errores'][] = 'modelo no indicado';
  if (!isset($_SESSION['motor'])) $_SESSION['errores'][] = 'motor no indicado';
  if (!isset($_SESSION['pintura'])) $_SESSION['errores'][] = 'pintura no indicada';

  if (isset($_SESSION['errores'])) {
    header('location: 01login.php');
    exit();
  }


  // presupuesto
  $precio_total = $_SESSION['modelo']['precio'] + $_SESSION['motor']['precio'] + $_SESSION['pintura']['precio'];
  if (isset($_SESSION['extras'])) {
    foreach ($_SESSION['extras'] as $key => $value) {
      $precio_total += $value['precio'];
    }
  }

  // calculo de cuotas
  $pago = $financiacion[$pago];
  $cuota_inicial = $precio_total * 0.75;
  $cuota_final = $precio_total * 0.75;
  $n_cuotas = $pago['anios'] * 12;
  $precio_x_cuota = round((($cuota_final + $cuota_inicial) - $precio_total) / $n_cuotas, 2);

  $fechas_pago = [];
  $fecha = new DateTime();

  for ($i = 1; $i <= $n_cuotas; $i++) {
    $fecha->modify('+1 month');
    $fechas_pago[] = clone($fecha);
  }

  inicio_html("06 Pantalla presupuesto", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

?>
  <h1>Cochecitos pum pum</h1>
  <h2>usuario <?= $usuario['nombre'] ?></h2>
  <h2>email <?= $usuario['email'] ?></h2>
  <h2>telefono <?= $usuario['telefono'] ?></h2>
  <h2>direccion <?= $usuario['direccion'] ?></h2>

  <table>
    <thead>
      <tr>
        <th>modelo</th>
        <th>motor</th>
        <th>pintura</th>
        <th>extras</th>
        <th>financiacion</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?= $_SESSION['modelo']['name'] ?> <?= $_SESSION['modelo']['precio'] ?></td>
        <td><?= $_SESSION['motor']['name'] ?> <?= $_SESSION['motor']['precio'] ?></td>
        <td><?= $_SESSION['pintura']['name'] ?> <?= $_SESSION['pintura']['precio'] ?></td>
        <td>
          <?php
          if (isset($_SESSION['extras'])) {
            foreach ($_SESSION['extras'] as $key => $value) :
          ?>
              - <?= $value['name'] ?> => <?= $value['precio'] ?>
          <?php
            endforeach;
          } else {
            echo 'no extras seleccionados';
          }
          ?>
        </td>
      </tr>
    </tbody>
  </table>

  <hr>
  <h3>tabla de pagos</h3>
  <h4>cuota inicial: <?= $cuota_inicial ?></h4>
  <h4>cuota final: <?= $cuota_final ?></h4>
  <h4>cantidad de cuotas <?= $n_cuotas ?></h4>

  <table>
    <thead>
      <tr>
        <th>fecha de pago</th>
        <th>cantidad</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($fechas_pago as $key) : ?>
        <tr></tr>
        <td><?= $key->format('d-m-Y') ?></td>
        <td><?= $precio_x_cuota ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>

  <form action="01login.php" method="GET">
    <button type="submit" name="operacion" id="operacion" value="cerrar">nuevo presupuesto</button>
  </form>
<?php

  fin_html();
} else {
  header('location: 01login');
}
