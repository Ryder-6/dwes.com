<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
inicio_html('05 version 2', ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

define('TAMANIO_MAXIMO_FICHERO', 1024 * 1024);
define('DIRECTORIO_SUBIDA', $_SERVER['DOCUMENT_ROOT'] . '/ejercicios/ra3/permisos_conduccion');
define('CUOTA_INICIAL', 0.25);
define('CUOTA_FINAL', 0.25);

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



$TIPOS_MIME_ACEPTADOS = ['image/png', 'image/jpeg', 'image/webp'];

function error($n_error)
{
  $errores = [
    1 => 'Falta nombre del solicitante',
    2 => 'Telefono invalido (debe tener 9 digitos)',
    3 => 'Email invalido',
    4 => 'Modelo seleccionado invalido',
    5 => 'Motor seleccionado invalido',
    6 => 'Pintura seleccionada invalida',
    7 => 'Tipo de financiacion invalido',
    8 => 'El fichero supera el tamaño máximo permitido',
    9 => 'Tipo MIME del fichero no permitido o no coincide',
    10 => 'No se ha podido crear el directorio de subida',
    11 => 'Error al guardar el fichero subido'
  ];


  echo "<h1>Error en la aplicacion</h1>";
  echo "<h2>Código: $n_error</h2>";
  echo "<h3>Mensaje: {$errores[$n_error]}</h3>";
  fin_html();
  exit($n_error);
}

function guarda_fichero($fichero, $nombre, $email, $tipos_aceptados)
{
  $name = $fichero['name'];
  $tmp_name = $fichero['tmp_name'];
  $size = $fichero['size'];
  $type = $fichero['type'];
  $error = $fichero['error'];

  if ($error == UPLOAD_ERR_FORM_SIZE) error(8);
  if (
    !in_array($type, $tipos_aceptados) ||
    !$type == mime_content_type($tmp_name)
  ) {
    error(9);
  }

  if ($error == UPLOAD_ERR_OK) {
    if (!is_dir(DIRECTORIO_SUBIDA . "/$email")) {
      if (!mkdir(DIRECTORIO_SUBIDA . "/$email", 0755, true)) {
        error(10);
      }
    }

    $extension = explode("/", $type)[1];
    if (!move_uploaded_file($tmp_name, DIRECTORIO_SUBIDA . "/$email" . "/$nombre.$extension")) {
      error(11);
    }
  }
}

function calcula_total(array $datos): float
{
  $total = 0.0;

  foreach (['modelo', 'motor', 'pintura'] as $key) {
    $total += $datos[$key]['precio'];
  }

  if (!empty($datos['extra']) && is_array($datos['extra'])) {
    foreach ($datos['extra'] as $extra) {
      if (is_array($extra) && isset($extra['precio'])) {
        $total += $extra['precio'];
      }
    }
  }

  return $total;
}

function calcula_tarifa($precio, $tipo_financiacion)
{

  $cuota_inicial = $precio * CUOTA_INICIAL;
  $cuota_final = $precio * CUOTA_FINAL;
  $total_financiado = $precio - ($cuota_inicial + $cuota_final);

  $meses = $tipo_financiacion['anios'] * 12;
  $cuota_mensual = round($total_financiado / $meses);

  $fecha_inicial = new DateTime();
  $fecha_inicial->modify('+1 month');

  $fechas_couta_pago = [];
  for ($i = 0; $i <= $tipo_financiacion['anios'] * 12; $i++) {
    $fechas_couta_pago[] = [
      'fecha' => $fecha_inicial->format('d-m-Y'),
      'cuota' => $cuota_mensual
    ];
    $fecha_inicial->modify('+1 month');
  }

  return [
    'cuota_inicial' => $cuota_final,
    'cuota_final' => $cuota_final,
    'cuotas' => $fechas_couta_pago
  ];
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // 1. sanear
  $saneamiento = [

    'name' => FILTER_SANITIZE_SPECIAL_CHARS,
    'telefono' => FILTER_SANITIZE_SPECIAL_CHARS,
    'email' => FILTER_SANITIZE_EMAIL,
    'modelo' => FILTER_SANITIZE_SPECIAL_CHARS,
    'motor' => FILTER_SANITIZE_SPECIAL_CHARS,
    'pintura' => FILTER_SANITIZE_SPECIAL_CHARS,
    'extra' => [
      'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
      'flags' => FILTER_REQUIRE_ARRAY
    ],
    'financiacion' => FILTER_SANITIZE_SPECIAL_CHARS
  ];

  $datos_saneados = filter_input_array(INPUT_POST, $saneamiento, true);

  // 2. validar
  $datos_validados['name'] = $datos_saneados['name'] ?: error(1);
  $datos_validados['telefono'] = preg_match("/^[0-9]{9}$/", $datos_saneados['telefono']) ? $datos_saneados['telefono'] : error(2);
  $datos_validados['email'] = filter_var($datos_saneados['email'], FILTER_VALIDATE_EMAIL) ?: error(3);
  $datos_validados['modelo'] = array_key_exists($datos_saneados['modelo'], $modelos) ? $modelos[$datos_saneados['modelo']] : error(4);
  $datos_validados['motor'] = array_key_exists($datos_saneados['motor'], $motores) ? $motores[$datos_saneados['motor']] : error(5);
  $datos_validados['pintura'] = array_key_exists($datos_saneados['pintura'], $pinturas) ? $pinturas[$datos_saneados['pintura']] : error(6);

  $datos_validados['extra'] = [];
  if (!empty($datos_saneados['extra']) && is_array($datos_saneados['extra'])) {
    foreach ($datos_saneados['extra'] as $key => $val) {
      if (array_key_exists($key, $extras)) {
        $datos_validados['extra'][] = $extras[$key];
      }
    }
  }
  $datos_validados['financiacion'] = array_key_exists($datos_saneados['financiacion'], $financiacion) ? $financiacion[$datos_saneados['financiacion']] : error(7);

  // 3. fichero
  guarda_fichero($_FILES['fichero'], $datos_validados['name'], $datos_validados['email'], $TIPOS_MIME_ACEPTADOS);
  $total = calcula_total($datos_validados);

  $cuotas = calcula_tarifa($total, $datos_validados['financiacion']);

  // 4. presentar

?>
  <table>
    <thead>
      <tr>
        <th>nombre</th>
        <th>telefono</th>
        <th>email</th>
        <th>modelo</th>
        <th>motor</th>
        <th>pintura</th>
        <th>extras</th>
        <th>financiacion</th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <td><?= $datos_validados['name'] ?></td>
        <td><?= $datos_validados['telefono'] ?></td>
        <td><?= $datos_validados['email'] ?></td>
        <td><?= $datos_validados['modelo']['name'] ?> => <?= $datos_validados['modelo']['precio'] ?></td>
        <td><?= $datos_validados['motor']['name'] ?> => <?= $datos_validados['motor']['precio'] ?: 'incluido' ?></td>
        <td><?= $datos_validados['pintura']['name'] ?> => <?= $datos_validados['pintura']['precio'] ?: 'Inlcuido' ?></td>
        <td>
          <ul>

            <?php if (!empty($datos_validados['extra'])) {
              foreach ($datos_validados['extra'] as $key => $value) : ?>
                <li> <?=$value['name'] ?> => <?=$value['precio'] ?></li>

            <?php endforeach;
            } ?>
          </ul>
        </td>
        <td><?= $datos_validados['financiacion']['name'] ?></td>
      </tr>
    </tbody>
  </table>
  <h3>cuota inicial => <?= $cuotas['cuota_inicial'] ?></h3>
  <h3>cuota final => <?= $cuotas['cuota_final'] ?></h3>
  <table>
    <thead>
      <tr>
        <th>fecha</th>
        <th>cantidad</th>
      </tr>
    </thead>
    <tbody>

      <?php foreach ($cuotas['cuotas'] as $key => $value) : ?>
        <tr>
          <td><?= $value['fecha'] ?></td>
          <td><?= $value['cuota'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>


<?php
}




if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

  <h1>Presupuesto de coche</h1>

  <fieldset>
    <legend>Opciones a elegir</legend>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="MAX_FILE_SIZE" value="<?= TAMANIO_MAXIMO_FICHERO ?>">

      <label for="name">name</label>
      <input type="text" name="name" id="name">

      <label for="telefono">telefono</label>
      <input type="text" name="telefono" id="telefono">

      <label for="email">email</label>
      <input type="email" name="email" id="email">

      <label for="modelo">modelo</label>
      <select name="modelo" id="modelo">
        <?php foreach ($modelos as $key => $value) : ?>
          <option value="<?= $key ?>"><?= $value['name'] ?> => <?= $value['precio'] ?></option>
        <?php endforeach; ?>
      </select>

      <label for="motor">motor</label>
      <select name="motor" id="motor">
        <?php foreach ($motores as $key => $value): ?>
          <option value="<?= $key ?>"> <?= $value['name'] ?> => <?= $value['precio'] == 0 ? 'incluido' : $value['precio'] ?></option>
        <?php endforeach; ?>
      </select>

      <label for="pintura">pintura</label>
      <select name="pintura" id="pintura">
        <?php foreach ($pinturas as $key => $value): ?>
          <option value="<?= $key ?>"> <?= $value['name'] ?> => <?= $value['precio'] == 0 ? 'incluido' : $value['precio'] ?></option>
        <?php endforeach; ?>
      </select>

      <h4>extras</h4>

      <?php foreach ($extras as $key => $value): ?>
        <div>
          <label for="extra[<?= $key ?>]"> <?= $value['name'] ?> => <?= $value['precio'] ?></label>
          <input type="checkbox" name="extra[<?= $key ?>]" id="<?= $key ?>">
        </div>
      <?php endforeach; ?>

      <h4>financiacion</h4>
      <?php foreach ($financiacion as $key => $value): ?>
        <div>
          <label for="financiacion"> <?= $value['name'] ?> </label>
          <input type="radio" name="financiacion" id="financiacion" value="<?= $key ?>">
        </div>
      <?php endforeach; ?>

      <label for="fichero">permiso de conduccion</label>
      <input type="file" name="fichero" id="fichero" accept="image/*">

      <button type="submit">Calcular presupuesto</button>


    </form>
  </fieldset>


<?php
}


fin_html();



?>