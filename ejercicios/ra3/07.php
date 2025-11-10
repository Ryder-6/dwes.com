<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');

inicio_html("Ejercicio7", ["/estilos/formulario.css", "/estilos/general.css"]);


function error(int $codigo_error): void
{
  $mensajes_error = [
    1 => 'DNI invalido',
    2 => 'Falta nombre',
    3 => 'Falta aceptacion de registro de datos personales',
    4 => 'No se ha subido fichero',
    5 => 'Error, no hay archivo',
    6 => 'Tipo mime no permitido',
    7 => 'El fichero supera el tamaño maximo',
    8 => 'Error al guardar el archivo',
    9 => 'Error inesperado',
  ];

  ob_clean();
?>
  <h1>Error en la aplicacion</h1>
  <h2>Codigo error: <?= $codigo_error ?></h2>
  <h3>Mensaje error: <?= $mensajes_error[$codigo_error] ?></h3>
<?php

  fin_html();
  exit($codigo_error);
}

$tipos_mime_aceptados = ['application/pdf'];
$tamanio_maximo = 1024 * 1024;
define('DIRECTORIO_SUBIDA', $_SERVER['DOCUMENT_ROOT'] . "/ra3/uploads/curriculums");

function guardar_fichero($fichero, $tipos_permitidos = [], $tamanio_maximo = 1024 * 1024, $nombre = "")
{
  $tmp_name = $fichero['tmp_name'];
  $name = $fichero['name'];
  $size = $fichero['size'];
  $type = $fichero['type'];
  $error = $fichero['error'];

  if ($error === UPLOAD_ERR_NO_FILE) {
    error(5);
  }

  // Tipos mime
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $tipo_mime_finfo = finfo_file($finfo, $tmp_name);
  if (
    !in_array($type, $tipos_permitidos) // basico
    || $type != mime_content_type($tmp_name) // + avanzado
    || $type != $tipo_mime_finfo // ideal(?)
  ) {
    error(6);
  }


  if ($size > $tamanio_maximo || $error == UPLOAD_ERR_INI_SIZE) {
    error(7);
  }

  if ($error == UPLOAD_ERR_OK) {
    echo "<h2>Archivo valido</h2>";

    $nombre_fichero = DIRECTORIO_SUBIDA . "/$nombre.pdf";

    if (!is_dir(DIRECTORIO_SUBIDA)) {
      if (!mkdir(DIRECTORIO_SUBIDA, 0755, true) && !is_dir(DIRECTORIO_SUBIDA)) {
        /*
        Dar permisos de creacion
        */
        error(8);
      }
    }

    if (!is_uploaded_file($tmp_name) || !move_uploaded_file($tmp_name, $nombre_fichero)) {
      error(9);
    }
  }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  //1. Saneamiento
  $saneamiento = [
    'dni' => FILTER_SANITIZE_SPECIAL_CHARS,
    'fichero' => FILTER_DEFAULT,
    'nombre' => FILTER_SANITIZE_SPECIAL_CHARS,
    'registro' => FILTER_DEFAULT,
  ];

  $datos_saneados = filter_input_array(INPUT_POST, $saneamiento, true);

  //2. Validacion

  $regex_dni = "/^[0-9]{7,8}[A-Z]$/";
  if (preg_match($regex_dni, $datos_saneados['dni'])) {
    $datos_validados['dni'] = $datos_saneados['dni'];
  } else {
    error(1);
  }

  if ($datos_saneados['nombre']) {
    $datos_validados['nombre'] = $datos_saneados['nombre'];
  } else {
    error(2);
  }

  $datos_validados['registro'] = filter_var($datos_saneados['registro'], FILTER_VALIDATE_BOOLEAN);
  if (!$datos_validados['registro']) {
    error(3);
  }

  if (isset($_FILES['fichero'])) {
    guardar_fichero($_FILES['fichero'], $tipos_mime_aceptados, $tamanio_maximo, $datos_validados['dni']);
  } else {
    error(4);
  }

  //3. Resultado





}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

  <h1>Solicitud de empleo</h1>
  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>datos de solicitud</legend>
      <input type="hidden" name="MAX_FILE_SIZE" value="<?= $tamanio_maximo ?>">

      <label for="dni">DNI</label>
      <input type="text" name="dni" id="dni">

      <label for="fichero">Curriculum</label>
      <input type="file" name="fichero" id="fichero" accept="application/pdf">

      <label for="nombre">nombre</label>
      <input type="text" name="nombre" id="nombre">

      <label for="registro">aceptacion de registro de datos personales</label>
      <input type="checkbox" name="registro" id="registro">


      <button type="submit">Enviar solicitud</button>
    </fieldset>
  </form>


<?php
}


fin_html();
?>