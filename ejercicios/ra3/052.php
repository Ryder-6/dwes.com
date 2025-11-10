<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');

inicio_html('05 version 2', ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

// persona * dia
$destinos = [
  'pa' => ['name' => 'París', 'precio' => '100'],
  'lo' => ['name' => 'londres', 'precio' => '120'],
  'es' => ['name' => 'Estocolmo', 'precio' => '200'],
  'ed' => ['name' => 'Edinburgo', 'precio' => '175'],
  'pr' => ['name' => 'Praga', 'precio' => '125'],
  'vi' => ['name' => 'Viena', 'precio' => '150'],
];

// * persona
$compania_aerea = [
  'ma' => ['name' => 'MiAir', 'precio' => 0],
  'vc' => ['name' => 'AirFly', 'precio' => 50],
  'af' => ['name' => 'VuelaConmigo', 'precio' => 75],
  'aa' => ['name' => 'ApedalesAir', 'precio' => 150]
];

// * persona * dia
$hoteles = [
  '3s' => 0,
  '4s' => 40,
  '5s' => 100
];

$dias = [5, 10, 15];

//total
$visita_guiada = 200;

// * persona
$extras = [
  'vi' => ['name' => 'Visita guiada', 'precio' => 200],
  'de' => ['name' => 'Desayuno incluido', 'precio' => 20],
  'bu' => ['name' => 'Bus Turistico', 'precio' => 30],
  'ma' => ['name' => 'Segunda maleta', 'precio' => 20],
  'se' => ['name' => 'Seguro de viaje', 'precio' => 30],
];

function error($n_error): void
{
  $errores = [
    1 => 'No hay nombre de responsable',
    2 => 'Fecha invalida',
    3 => 'Email invalido',
    4 => 'Destino invalido',
    5 => 'Compañia invalida',
    6 => 'Hotel invalido',
    7 => 'numero de personas invalido',
    8 => 'Error en el tamaño del fichero',
    9 => 'Error en el tipo mime del fichero',
    10 => 'Error inesperado en el fichero',
    11 => 'Error en el directorio de subida',
    12 => 'Error inesperando a final de proceso de guardado',
    13 => 'Error, falta fichero, o datos importantes',
    14 => 'Error, numero de dias invalido'
  ];

  ob_clean();
  echo "<h2>Error $n_error: $errores[$n_error]</h2>";
  fin_html();

  exit($n_error);
}

// Valida si la fecha es un formato valido
function validar_fecha($fecha): bool
{
  $valores = explode("-", $fecha);
  if (count($valores) == 3) {
    if (checkdate($valores[1], $valores[2], $valores[0])) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  };
}

// Valida si es fecha futura
function fecha_futura($fecha): bool
{
  $fecha = new DateTime($fecha);
  $fecha_actual = new DateTime();
  if ($fecha_actual < $fecha) {
    return true;
  } else {
    return false;
  }
}

function limpia_nombre($nombre): string
{
  $nombre = str_replace(' ', '-', $nombre);
  return $nombre;
}

define('DIRECTORIO_SUBIDA', $_SERVER['DOCUMENT_ROOT'] . "/ejercicios/ra3/viajes");
define('TAMANIO_MAXIMO_FICHERO', 200 * 1024); //200kbites
$TIPOS_MIME_ACEPTADOS = ['image/png', 'image/webp', 'image/jpeg'];
$accept = htmlspecialchars(implode(',', $TIPOS_MIME_ACEPTADOS), ENT_QUOTES);

function guardar_fichero($fichero, $tamanio_maximo = 1024 * 1024, $tipos_permitidos, $nombre = '', $grupo): bool
{

  // 1. datos de fichero
  $tmp_name = $fichero['tmp_name'];
  $name = $fichero['name'];
  $type = $fichero['type'];
  $size = $fichero['size'];
  $error = $fichero['error'];

  $extensiones = [
    'image/png' => '.png',
    'image/jpeg' => '.jpg',
    'image/webp' => '.webp'
  ];

  // 2. validacion
  if ($tamanio_maximo < $size || $error == UPLOAD_ERR_FORM_SIZE) error(8);
  if (
    !in_array($type, $tipos_permitidos) ||
    !$type == mime_content_type($tmp_name)
  ) {
    error(9);
  }

  if ($error == UPLOAD_ERR_OK) {

    // 3. guardado
    if (!is_dir(DIRECTORIO_SUBIDA . "/$grupo")) {
      if (!mkdir(DIRECTORIO_SUBIDA . "/$grupo", 0755, true) && !is_dir(DIRECTORIO_SUBIDA . "/$grupo")) {
        error(11);
      }
    }
    $nombre = limpia_nombre($nombre);
    $nombre = DIRECTORIO_SUBIDA . "/$grupo" . "/" . $nombre . $extensiones[$type];

    if (!is_uploaded_file($tmp_name) || !move_uploaded_file($tmp_name, $nombre)) {
      error(12);
    }
    return true;
  } else error(10);

  return false;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // 1. Saneamiento
  $saneamiento = [
    'nombre' => FILTER_SANITIZE_SPECIAL_CHARS,
    'fecha' => FILTER_DEFAULT,
    'email' => FILTER_SANITIZE_EMAIL,
    'destino' => FILTER_SANITIZE_SPECIAL_CHARS,
    'compania' => FILTER_SANITIZE_SPECIAL_CHARS,
    'hotel' => FILTER_SANITIZE_SPECIAL_CHARS,
    'n_personas' => FILTER_SANITIZE_NUMBER_INT,
    'dias' => FILTER_SANITIZE_NUMBER_INT,
    'vi' => FILTER_DEFAULT,
    'de' => FILTER_DEFAULT,
    'bu' => FILTER_DEFAULT,
    'ma' => FILTER_DEFAULT,
    'se' => FILTER_DEFAULT
  ];

  $datos_saneados = filter_input_array(INPUT_POST, $saneamiento);

  // 2. Validacion
  $datos_validados['nombre'] = isset($datos_saneados['nombre']) ? $datos_saneados['nombre'] : false;

  $datos_validados['fecha'] = (isset($datos_saneados['fecha']) &&
    validar_fecha($datos_saneados['fecha']) &&
    fecha_futura($datos_saneados['fecha'])) ?
    new DateTime($datos_saneados['fecha']) : false;


  $datos_validados['email'] = filter_var($datos_saneados['email'], FILTER_VALIDATE_EMAIL);
  $datos_validados['destino'] = array_key_exists($datos_saneados['destino'], $destinos) ? $destinos[$datos_saneados['destino']] : false;
  $datos_validados['compania'] = array_key_exists($datos_saneados['compania'], $compania_aerea) ? $compania_aerea[$datos_saneados['compania']] : false;
  $datos_validados['hotel'] = array_key_exists($datos_saneados['hotel'], $hoteles) ? $datos_saneados['hotel'] : false;
  $datos_validados['n_personas'] = filter_var(
    $datos_saneados['n_personas'],
    FILTER_VALIDATE_INT,
    ['options' => [
      'default' => 5,
      'min_range' => 5,
      'max_range' => 10
    ]]
  );
  $datos_validados['dias'] = in_array($datos_saneados['dias'], $dias) ? $datos_saneados['dias'] : false;

  $datos_validados['vi'] = filter_var($datos_saneados['vi'], FILTER_VALIDATE_BOOLEAN);
  $datos_validados['de'] = filter_var($datos_saneados['de'], FILTER_VALIDATE_BOOLEAN);
  $datos_validados['bu'] = filter_var($datos_saneados['bu'], FILTER_VALIDATE_BOOLEAN);
  $datos_validados['ma'] = filter_var($datos_saneados['ma'], FILTER_VALIDATE_BOOLEAN);
  $datos_validados['se'] = filter_var($datos_saneados['se'], FILTER_VALIDATE_BOOLEAN);


  if (!$datos_validados['nombre']) error(1);
  if (!$datos_validados['fecha']) error(2);
  if (!$datos_validados['email']) error(3);
  if (!$datos_validados['destino']) error(4);
  if (!$datos_validados['compania']) error(5);
  if (!$datos_validados['hotel']) error(6);
  if (!$datos_validados['n_personas']) error(7);
  if (!$datos_validados['n_personas']) error(14);


  // 3. Fichero
  if (isset($_FILES['fichero']) && $datos_validados['email'] && $datos_validados['fecha']) {
    guardar_fichero(
      $_FILES['fichero'],
      TAMANIO_MAXIMO_FICHERO,
      $TIPOS_MIME_ACEPTADOS,
      $datos_validados['nombre'],
      ($datos_validados['email'] . "." . $datos_validados['fecha']->format('Y-m-d'))
    );
  } else error(13);

  // 4. Presentacion
?>
  <h1>Presupuesto</h1>
  <table>
    <thead>
      <tr>
        <th>nombre</th>
        <th>fecha</th>
        <th>email</th>
        <th>destino</th>
        <th>compañia</th>
        <th>hotel</th>
        <th>numero personas</th>
        <th>numero dias</th>
        <th>visita Guiada</th>
        <th>desayuno</th>
        <th>bus turistico</th>
        <th>segunda maleta</th>
        <th>seguro viaje</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?= $datos_validados['nombre'] ?></td>
        <td><?= $datos_validados['fecha']->format("d-m-Y") ?></td>
        <td><?= $datos_validados['email'] ?></td>
        <td><?= $datos_validados['destino']['name'] ?> => <?= $datos_validados['destino']['precio'] ?></td>
        <td><?= $datos_validados['compania']['name'] ?> => <?= $datos_validados['compania']['precio'] == 0 ? 'Inluido' : $datos_validados['compania']['precio'] ?>?></td>
        <td><?= $datos_validados['hotel'] ?> <?= $hoteles[$datos_validados['hotel']] == 0 ? 'Inluido' : $hoteles[$datos_validados['hotel']] ?> </td>
        <td><?= $datos_validados['n_personas'] ?></td>
        <td><?= $datos_validados['dias'] ?></td>
        <td><?= $datos_validados['vi'] ? 'Si' : 'No' ?></td>
        <td><?= $datos_validados['de'] ? 'Si' : 'No' ?></td>
        <td><?= $datos_validados['bu'] ? 'Si' : 'No' ?></td>
        <td><?= $datos_validados['ma'] ? 'Si' : 'No' ?></td>
        <td><?= $datos_validados['se'] ? 'Si' : 'No' ?></td>
      </tr>
    </tbody>
  </table>

  <?php
  $precio_persona_dia =
    $datos_validados['destino']['precio'] +
    $hoteles[$datos_validados['hotel']] +
    $datos_validados['compania']['precio'] +
    ($datos_validados['de'] ? $extras['de']['precio'] : 0) +
    ($datos_validados['bu'] ? $extras['bu']['precio'] : 0) +
    ($datos_validados['ma'] ? $extras['ma']['precio'] : 0) +
    ($datos_validados['se'] ? $extras['se']['precio'] : 0);

    $precio_grupo_dia = $precio_persona_dia * $datos_validados['n_personas'];
    $precio_persona_total = $precio_persona_dia * $datos_validados['dias'];
    $precio_grupo_total = $precio_grupo_dia * $datos_validados['dias'] + (($datos_validados['vi'] ? $extras['vi']['precio'] : 0))

  ?>


  <h2>precio</h2>
  <fieldset>
    <legend>Precio total</legend>
    <h3>Precio persona dia => <?= $precio_persona_dia?> </h3><br>
    <h3>Precio grupo dia => <?= $precio_grupo_dia?> </h3><br>
    <h3>Precio persona total => <?= $precio_persona_total?> </h3><br>
    <h3>Precio grupo total => <?= $precio_grupo_total?> </h3><br>

  </fieldset>


<?php
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

  <h1>Agencia de viajes</h1>

  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Organizacion de viaje</legend>
      <input type="hidden" name="MAX_FILE_SIZE" value="<?= TAMANIO_MAXIMO_FICHERO ?>">

      <label for="nombre">persona Responsable</label>
      <input type="text" name="nombre" id="nombre">

      <label for="fecha">fecha</label>
      <input type="date" name="fecha" id="fecha">

      <label for="email">email</label>
      <input type="email" name="email" id="email">

      <label for="destino">Destino</label>
      <select name="destino" id="destino">
        <?php foreach ($destinos as $key => $value) : ?>
          <option value="<?= $key ?>"><?= $value['name'] ?> => <?= $value['precio'] ?></option>
        <?php endforeach; ?>
      </select>

      <label for="compania">Compañia Aerea</label>
      <select name="compania" id="compania">
        <?php foreach ($compania_aerea as $key => $value): ?>
          <option value=<?= $key ?>>
            <?= $value['name'] ?> => <?= ($value['precio'] == 0 ? 'Incluido' : $value['precio'] . '€/P') ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label for="hotel">hotel</label>
      <select name="hotel" id="hotel">
        <?php foreach ($hoteles as $key => $value): ?>
          <option value="<?= $key ?>">
            <?= $key ?> => <?= ($value == 0 ? 'Incluido' : $value . '€/P/D') ?>
          </option>
        <?php endforeach ?>
      </select>

      <label for="n_personas"> numero de personas</label>
      <input type="number" name="n_personas" id="n_personas" min="5" max="10">

      <label for="dias">numero de dias</label>
      <select name="dias" id="dias">
        <?php foreach ($dias as $key) : ?>
          <option value="<?= $key ?>"> <?= $key ?> Dias</option>
        <?php endforeach ?>
      </select>


      <?php foreach ($extras as $key => $value) : ?>
        <label for="<?= $key ?>"><?= $value['name'] ?> => <? $value['precio'] ?></label>
        <input type="checkbox" name="<?= $key ?>" id="<?= $key ?>">
      <?php endforeach; ?>

      <label for="fichero">dni responsable </label>
      <input type="file" name="fichero" id="fichero" accept="<?= $accept ?>">
    </fieldset>

    <button type="submit">enviar</button>
  </form>


<?php
}

fin_html();
?>