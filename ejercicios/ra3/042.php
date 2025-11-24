<?php


require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');

inicio_html('04 version 2', ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);


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

function error($n_error)
{
  $errores  = [
    1 => 'falta nombre',
    2 => 'falta telefono',
    3 => 'falta email',
    4 => 'error  modelo',
    5 => 'error  motor',
    6 => 'error  pintura',
    7 => 'error  financiacion',
    8 => 'error no hay fichero',
    9 => 'error tamaño fichero invalido',
    10 => 'error tipo mime',
    11 => 'error creando la carpeta',
    12 => 'error guardando el fichero',
    13 => 'error inesperado con el fichero',
  ];

  echo "<h2>Error $n_error: $errores[$n_error]</h2>";
  fin_html();
  exit($n_error);
}

define('TAMANIO_MAXIMO', 1024 * 1024);
define('DIRECTORIO_SUBIDA', $_SERVER['DOCUMENT_ROOT'] . '/ejercicios/ra3/permisos_conduccion');
$TIPOS_MIME_ACEPTADOS = ['application/pdf'];


function guardar_fichero($fichero, $tipos_aceptados, $nombre) {
  
  $tmp_name = $fichero['tmp_name'];
  $size = $fichero['size'];
  $type = $fichero['type'];
  $error = $fichero['error'];

  if ($error == UPLOAD_ERR_NO_FILE) error(8);
  if ($error == UPLOAD_ERR_FORM_SIZE) error(9);
  if (!in_array($type, $tipos_aceptados) || $type != mime_content_type($tmp_name) ) error(10);

  if ($error == UPLOAD_ERR_OK) {
    if (!is_dir(DIRECTORIO_SUBIDA)) {
      if (!mkdir(DIRECTORIO_SUBIDA, 0755, true)) {
        error(11);
      }
    }

    $extension = explode('/', $type)[1];
    if (!move_uploaded_file($tmp_name, DIRECTORIO_SUBIDA . "/$nombre" .".$extension")) {
      error(12);
    }
  }else error(13);


}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // 1. sanear
  $sanear = [
    'nombre' => FILTER_SANITIZE_SPECIAL_CHARS,
    'telefono' => FILTER_SANITIZE_SPECIAL_CHARS,
    'email' => FILTER_SANITIZE_SPECIAL_CHARS,
    'modelo' => FILTER_SANITIZE_SPECIAL_CHARS,
    'motor' => FILTER_SANITIZE_SPECIAL_CHARS,
    'pintura' => FILTER_SANITIZE_SPECIAL_CHARS,
    'extras' => [
      'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
      'flags' => FILTER_REQUIRE_ARRAY
    ],
    'financiacion' => FILTER_SANITIZE_SPECIAL_CHARS
  ];

  $datos_saneados = filter_input_array(INPUT_POST, $sanear, true);

  // 2. validar
  $datos_validados['nombre'] = isset($datos_saneados['nombre']) ? $datos_saneados['nombre'] : error(1);
  $datos_validados['telefono'] = isset($datos_saneados['telefono']) ? $datos_saneados['telefono'] : error(2);
  $datos_validados['email'] = filter_var($datos_saneados['email'], FILTER_VALIDATE_EMAIL) ?: error(3);
  $datos_validados['modelo'] = array_key_exists($datos_saneados['modelo'], $modelos) ? $modelos[$datos_saneados['modelo'] ]: error(4);
  $datos_validados['motor'] = array_key_exists($datos_saneados['motor'], $motores) ? $motores[$datos_saneados['motor']] : error(5);
  $datos_validados['pintura'] = array_key_exists($datos_saneados['pintura'], $pinturas) ? $pinturas[$datos_saneados['pintura']] : error(6);
  
  if (!empty($datos_saneados['extras']) && is_array($datos_saneados['extras'])) {
    foreach ($datos_saneados['extras'] as $key) {
      if (array_key_exists($key, $extras)) {
        $datos_validados['extras'][] = $extras[$key];       
      }
    }
  }
  $datos_validados['financiacion'] = array_key_exists($datos_saneados['financiacion'], $financiacion) ? $financiacion[$datos_saneados['financiacion']] : error(7);

  // 3. fichero
    //guarda_fichero($_FILES['fichero'], $datos_validados['email'], $tipos_aceptados);
  
  
  // 4. mostrar
}
?>
<h1>presupuesto de coche</h1>
<fieldset>
  <legend>componentes</legend>

  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?= TAMANIO_MAXIMO ?>">

    <label for="nombre">nombre</label>
    <input type="text" name="nombre" id="nombre" value="<?= isset($datos_validados['nombre']) ? $datos_validados['nombre'] : '' ?>">

    <label for="telefono">telefono</label>
    <input type="text" name="telefono" id="telefono" value="<?= isset($datos_validados['telefono']) ? $datos_validados['telefono'] : '' ?>">

    <label for="email">email</label>
    <input type="email" name="email" id="email" value="<?= isset($datos_validados['email']) ? $datos_validados['email'] : '' ?>">

    <label for="modelo">modelo</label>
    <select name="modelo" id="modelo">
      <?php foreach ($modelos as $key => $value) : ?>
        <option value="<?= $key ?>" <?= (isset($datos_validados['modelo']) && $key == key($datos_validados['modelo'])) ? 'selected' : '' ?>> <?= $value['name'] ?> => <?= $value['precio'] ?></option>
      <?php endforeach; ?>
    </select>

    <label for="motor">motor</label>
    <select name="motor" id="motor">
      <?php foreach ($motores as $key => $value):  ?>
        <option value="<?= $key ?>" <?= (isset($datos_validados['motor']) && $key == key($datos_validados['motor'])) ? 'selected' : '' ?>> <?= $value['name'] ?> => <?= $value['precio'] ?: 'incluido' ?></option>
      <?php endforeach ?>
    </select>

    <label for="pintura">Pintura</label>
    <select name="pintura" id="pintura">
      <?php foreach ($pinturas as $key => $value): ?>
        <option value="<?= $key ?>" <?= (isset($datos_validados['pintura']) && $key == key($datos_validados['pintura'])) ? 'selected' : '' ?>><?= $value['name'] ?> => <?= $value['precio'] ?: 'sin costo' ?></option>
      <?php endforeach ?>
    </select>

    <?php foreach ($extras as $key => $value) : ?>
      <div>
        <label for="extras[<?= $key ?>]"><?= $value['name'] ?> => <?= $value['precio'] ?></label>
        <input type="checkbox" name="extras[<?= $key ?>]" id="extras" <?= (isset($datos_validados['extras']) && array_key_exists($key, $datos_validados['extras']) ? 'checked' : '') ?>>
      </div>
    <?php endforeach; ?>

    <?php foreach ($financiacion as $key => $value) : ?>
      <div>
        <label for="financiacion"><?= $value['name'] ?></label>
        <input type="radio" name="financiacion" id="financiacion" value="<?= $key ?>" <?= (isset($datos_validados['financiacion']) && $key == key($datos_validados['financiacion'])) ? 'checked' : '' ?>>
      </div>
    <?php endforeach; ?>

    <label for="fichero">recibo del banco</label>
    <input type="file" name="fichero" id="fichero">

    <button type="submit">enviar presupuesto</button>

  </form>


</fieldset>
<?php

fin_html();

?>