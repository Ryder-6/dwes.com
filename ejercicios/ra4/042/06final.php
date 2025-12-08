<?php

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

define('CUOTA_INICIAL', 0.75);
define('CUOTA_FINAL', 0.75);


// 1. jwt 
$usuario = comprobarJWT();
$coche = $_SESSION['coche'][$usuario['email']];

// 2. sanear validar

$financiacion_saneada = filter_input(INPUT_POST, 'financiacion', FILTER_SANITIZE_SPECIAL_CHARS);

if (array_key_exists($financiacion_saneada, $financiacion)) {
  $financiacion_validada = $financiacion[$financiacion_saneada];
}else {
  $_SESSION['errores'][] = 'financiacion no valida';
  header('location: 01login.php');
  exit();
}

$total = $coche['motor']['precio'] +
  $coche['modelo']['precio'] +
  $coche['pintura']['precio'];

if (!$coche['extras']) {

  foreach ($coche['extras'] as $key => $value) {
    $total += $value['precio'];
  }
}


$cuota_inicial = $total * CUOTA_INICIAL;
$cuota_final = $total * CUOTA_FINAL;
$total_restante = $total - ($cuota_inicial + $cuota_final);

$n_cuotas = $financiacion_validada['anios'] * 12;

$precio_x_cuota = $total_restante / $n_cuotas;

$fechas_pago = new DateTime();

$historial_cuotas = [];
for ($i = 1; $i <= $n_cuotas; $i++) {
  $historial_cuotas[] = [
    'pago' => $precio_x_cuota,
    'fecha' => clone($fechas_pago->modify('+1 month'))
  ];
}

inicio_html('05 financiacion a elegir', ['/estilos/general.css', '/estilos/formulario.css', '/estilos/tabla.css']);
?>
<h1>Toyota</h1>

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
      <td><?= $coche['modelo']['name'] ?></td>
      <td><?= $coche['motor']['name'] ?></td>
      <td><?= $coche['pintura']['name'] ?></td>
      <td>
        <?php
        if (!empty($coche[$usuario['email']]['extras'])) {
          foreach ($coche[$usuario['email']]['extras'] as $key => $value) :
        ?>
            - <?= $value['name'] ?> => <?= $value['precio'] ?> <br>
        <?php endforeach;
        } else {
          echo "no extras escogidos";
        } ?>
      </td>
      <td><?= $financiacion_validada['name']?> </td>
    </tr>
  </tbody>
</table>

<hr>
<h2>fechas de pagos:</h2>

<table>
  <thead>
    <tr>
      <th>nº pago</th>
      <th>fecha</th>
      <th>precio</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($historial_cuotas as $key => $value) :  ?>
      <tr>
        <td><?= $key ?></td>
        <td><?= $value['fecha']->format('d-m-Y') ?></td>
        <td><?= round($value['pago'], 2) ?></td>
      </tr>
      <?php endforeach ?>
  </tbody>
</table>


<form action="01login.php" method="GET">
  <button type="submit" name="operacion" id="operacion" value="cerrar"> procesar pago</button>
</form>
<?php
fin_html();
?>