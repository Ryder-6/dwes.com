<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');

inicio_html("07 version 3", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

define('TAMANIO_MAXIMO', 1024 * 1024);
define('DIRECTORIO_SUBIDA', $_SERVER['DOCUMENT_ROOT'] . 'ejercicios/ra3/CV');

$TIPOS_PERMITIDOS = ['image/png', 'image/jpeg', 'image/webp', 'application/pdf'];

function error($n_error)
{
  $errores = [
    1 => 'falta dni',
    2 => 'falta nombre',
    3 => 'no hay cv subido',
    4 => 'Tamaño invalido de fichero',
    5 => 'tipo mime inapropiado',
    6 => 'Error creando la carpeta',
    7 => 'Error guardando el fichero',
  ];


  echo "<h3>Error $n_error: $errores[$n_error]</h1>";
  exit($n_error);
}


function guardar_fichero($fichero, $tipos_aceptados, $nombre): bool
{
  $tmp_name = $fichero['tmp_name'];
  $type = $fichero['type'];
  $error = $fichero['error'];
  $size = $fichero['size'];

  if ($error == UPLOAD_ERR_NO_FILE) {
    error(3);
    return false;
  }
  if ($error == UPLOAD_ERR_FORM_SIZE) {
    error(4);
    return false;
  }
  if (!in_array($type, $tipos_aceptados) || $type != mime_content_type($tmp_name)) {
    error(5);
    return false;
  }

  if ($error == UPLOAD_ERR_OK) {
    if (!is_dir(DIRECTORIO_SUBIDA)) {
      if (!mkdir(DIRECTORIO_SUBIDA, 0755, true)) {
        error(6);
      }
    }

    $extension = "." . explode('/', $type)[1];

    if (!move_uploaded_file($tmp_name, DIRECTORIO_SUBIDA . $nombre . $extension)) {
      error(7);
      return false;
    } else return true;
  } else return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // 1. sanear
  $sanear = [
    'dni' => FILTER_SANITIZE_SPECIAL_CHARS,
    'nombre' => FILTER_SANITIZE_SPECIAL_CHARS,
    'terminos' => FILTER_SANITIZE_SPECIAL_CHARS,
  ];
  $datos_saneados = filter_input_array(INPUT_POST, $sanear);

  // 2. validar
  $datos_validados['dni'] = isset($datos_saneados['dni']) ? $datos_saneados['dni'] : error(1);
  $datos_validados['nombre'] = isset($datos_saneados['nombre']) ? $datos_saneados['nombre'] : error(2);
  $datos_validados['terminos'] = filter_var($datos_saneados['terminos'], FILTER_VALIDATE_BOOLEAN);
  // 3. fichero
  if ($datos_validados['terminos'] && $datos_validados['dni'] && $datos_validados['nombre']) {
    if (guardar_fichero($_FILES['fichero'], $TIPOS_PERMITIDOS, TAMANIO_MAXIMO, $datos_validados['dni'])) {

      // 4. mostrar
?>
      <h1>bienvenido <?= $datos_validados['nombre'] ?></h1>
      <p>Seras informado del resultado por correo en los proximos dias</p>

<?php
    }
  }
}

?>
<h1>solicitud de trabajo</h1>

<fieldset>
  <legend>presenta tus datos</legend>

  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?= TAMANIO_MAXIMO ?>">

    <label for="dni">dni</label>
    <input type="text" name="dni" id="dni" value="<?= isset($datos_saneados['dni']) && isset($datos_validados['terminos']) && $datos_validados['terminos'] == false  ? $datos_saneados['dni'] : '' ?>">

    <label for="nombre">nombre</label>
    <input type="text" name="nombre" id="nombre" value="<?= isset($datos_saneados['nombre']) && isset($datos_validados['terminos']) && $datos_validados['terminos'] == false  ? $datos_saneados['nombre'] : '' ?>">

    <label for="fichero">CV</label>
    <input type="file" name="fichero" id="fichero">
    <?php if (isset($datos_validados['terminos']) && $datos_validados['terminos'] == false) { ?>
      <h3>debes aceptar los terminos antes de continuar</h3>
    <?php
    } ?>

    <label for="terminos">Aceptación registro datos personales</label>
    <input type="checkbox" name="terminos" id="terminos">

    <button type="submit">Enviar solicitud</button>

  </form>
</fieldset>


<?php

fin_html();

?>