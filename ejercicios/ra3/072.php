<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');

inicio_html("07 version 2", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);


function error($n_error)
{
  $errores = [
    1 => 'DNI invalido',
    2 => 'nombre invalido',
    3 => 'No aceptados los terminos de registro de datos personales',
    4 => 'Error, no hay fichero subido',
    5 => 'Error, Tamaño de fichero invalido',
    6 => 'Error, Tipo invalido',
    7 => 'Error, Creando la carpeta curriculums',
    8 => 'Error, Error en la subida de archiv',

  ];

  echo "<h2>Error $n_error: $errores[$n_error]</h2>";
  fin_html();
  exit();
}


define('DIRECTORIO_CURRICULUMS', $_SERVER['DOCUMENT_ROOT'] . '/ejercicios/ra3/curriculums');
function guarda_fichero($fichero, $tipos_aceptados = [], $tamanio_maximo = 1024 * 1024, $nombre)
{
  // 1. Datos fichero
  $name = $fichero['name'];
  $tmp_name = $fichero['tmp_name'];
  $type = $fichero['type'];
  $size = $fichero['size'];
  $error = $fichero['error'];

  // 2. Validacion
  if ($error == UPLOAD_ERR_NO_FILE) error(4);
  if ($size > $tamanio_maximo || $error == UPLOAD_ERR_FORM_SIZE) error(5);
  if (!in_array($type, $tipos_aceptados) || !$type == mime_content_type($tmp_name)) error(6);

  // 3. Carpeta
  if ($error == UPLOAD_ERR_OK) {
    if (!is_dir(DIRECTORIO_CURRICULUMS)) {
      if (!mkdir(DIRECTORIO_CURRICULUMS, 0755, true)) {
        error(7);
      }
    }
  }

  $extension_fichero = [
    'application/pdf' => '.pdf'
  ];
  $nombre = $nombre . $extension_fichero[$type];

  // 4. Guardar
  if (!is_uploaded_file($tmp_name) || !move_uploaded_file($tmp_name, DIRECTORIO_CURRICULUMS .'/' . $nombre)) {
    error(8);
  }
}

$TAMANIO_MAXIMO_FICHERO = 1024 * 1024;
$TIPOS_MIME_ACEPTADOS = ['application/pdf'];
$dni_regex = '/^[0-9]{8}[a-zA-Z]$/';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // 1. saneamiento
  $saneamiento = [
    'dni' => FILTER_SANITIZE_SPECIAL_CHARS,
    'nombre' => FILTER_SANITIZE_SPECIAL_CHARS,
    'aceptacion' => FILTER_DEFAULT
  ];

  $datos_saneados = filter_input_array(INPUT_POST, $saneamiento);

  // 2. validacion
  $datos_validados['dni'] = preg_match($dni_regex, $datos_saneados['dni']) ? $datos_saneados['dni'] : error(1);
  $datos_validados['nombre'] = isset($datos_saneados['nombre']) && $datos_saneados['nombre'] != '' ? $datos_saneados['nombre'] : error(2);
  $datos_validados['aceptacion'] = filter_var($datos_saneados['aceptacion'], FILTER_VALIDATE_BOOLEAN);

  if ($datos_saneados['aceptacion'] && $datos_validados['dni']) {
    
    guarda_fichero($_FILES['fichero'], $TIPOS_MIME_ACEPTADOS, $TAMANIO_MAXIMO_FICHERO, $datos_validados['dni']);
  } else {
  }
?>
<?php


// 3. resultado
}

?>
<h1>Solicitud de empleo</h1>
<fieldset>
  <legend>Datos del solicitante</legend>
  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?= $TAMANIO_MAXIMO_FICHERO ?>">

    <label for="dni">dni</label>
    <input type="text" name="dni" id="dni" value="<?= isset($datos_validados['dni']) && !$terminos_acetados ? $datos_validados['dni'] : '' ?>">

    <label for="fichero">CV</label>
    <input type="file" name="fichero" id="fichero" accept="application/pdf">

    <label for="nombre">nombre solicitante</label>
    <input type="text" name="nombre" id="nombre" value="<?= isset($datos_validados['nombre']) && !$terminos_acetados ? $datos_validados['nombre'] : '' ?>">

    <label for="aceptacion">Aceptacion de registro de datos personales</label>

    <input type="checkbox" name="aceptacion" id="aceptacion" >

    <button type="submit">Enviar solicitud</button>

  </form>
</fieldset>
<?php



fin_html();
?>