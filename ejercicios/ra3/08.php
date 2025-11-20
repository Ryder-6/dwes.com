<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');

inicio_html('08', ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

$TIPOS_MIME_PERMITIDOS = ['image/jpg', 'image/png', 'image/webp'];

$archivos_subidos = [];

function error($n_error)
{
  $errores = [
    1 => 'Error, login invalido',
    2 => 'Error, Tipo mime invalido',
    3 => 'Error, Tamaño maximo superado',
    4 => 'Error, En la creacion de la carpeta',
    5 => 'Error, inesperado en la subida de fichero',
    6 => 'Error, Tamaño invalido recibido'
  ];
  echo "<h2>Error $n_error: $errores[$n_error]  </h2>";
  fin_html();
  exit();
}

function guarda_fichero($fichero, $tipos_permitidos, $datos_validados)
{
  global $archivos_subidos;
  $name = $fichero['name'];
  $tmp_name = $fichero['tmp_name'];
  $type = $fichero['type'];
  $size = $fichero['size'];
  $error = $fichero['error'];

  if (
    !in_array($type, $tipos_permitidos) ||
    !$type == mime_content_type($tmp_name)
  ) {
    error(2);
  }

  $extension = explode('/', $type)[1];
  $max_file = 'max_file_' . $extension;

  if ($size > $datos_validados[$max_file]) {
    error(3);
  }

  if ($error == UPLOAD_ERR_OK) {
    $directorio = DIRECTORIO_SUBIDA . '/' . $datos_validados['titulo'];
    if (!is_dir($directorio)) {
      if (!mkdir($directorio, 0755, true)) {
        error(4);
      }
    }

    if (!move_uploaded_file($tmp_name, $directorio . '/' . $name)) {
      error(5);
    } else {
      $archivos_subidos[] = $name;
    }
  }
}

function lista_ficheros() {
  global $archivos_subidos;

  foreach ($archivos_subidos as $archivo) {
    echo "<h2>$archivo</h2>";
  }

}


define('DIRECTORIO_SUBIDA', $_SERVER['DOCUMENT_ROOT'] . '/ejercicios/ra3/upload08');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // 1. sanear
  $saneamiento = [
    'login' => FILTER_SANITIZE_SPECIAL_CHARS,
    'titulo' => FILTER_SANITIZE_SPECIAL_CHARS,
    'max_file_png' => FILTER_SANITIZE_NUMBER_INT,
    'max_file_jpg' => FILTER_SANITIZE_NUMBER_INT,
    'max_file_webp' => FILTER_SANITIZE_NUMBER_INT
  ];

  $datos_saneados = filter_input_array(INPUT_POST, $saneamiento, true);
  // 2. validar

  $login_regex = '/^[a-zA-Z0-9]+$/';

  $datos_validados['login'] = preg_match($login_regex, $datos_saneados['login']) ? $datos_saneados['login'] : error(1);
  $datos_validados['titulo'] = $datos_saneados['titulo'] ? $datos_saneados['titulo'] : '';
  $datos_validados['max_file_png'] = filter_var($datos_saneados['max_file_png'], FILTER_VALIDATE_INT)? $datos_saneados['max_file_png'] : error(6);
  $datos_validados['max_file_jpg'] = filter_var($datos_saneados['max_file_jpg'], FILTER_VALIDATE_INT)? $datos_saneados['max_file_jpg'] : error(6) ;
  $datos_validados['max_file_webp'] = filter_var($datos_saneados['max_file_webp'], FILTER_VALIDATE_INT)? $datos_saneados['max_file_webp'] : error(6) ;

  if ($datos_validados['login'] && $_FILES['fichero']) {
    guarda_fichero($_FILES['fichero'], $TIPOS_MIME_PERMITIDOS, $datos_validados);
  }

  // 3. presentar
  lista_ficheros();
}

?>
<h1>Subir archivos 08</h1>

<fieldset>
  <legend>Subir archivo</legend>

  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="max_file_png" value="<?= 250 * 1024 ?>">
    <input type="hidden" name="max_file_jpg" value="<?= 225 * 1024 ?>">
    <input type="hidden" name="max_file_webp" value="<?= 200 * 1024 ?>">


    <label for="login">login</label>
    <input type="text" name="login" id="login">

    <label for="fichero">fichero</label>
    <input type="file" name="fichero" id="fichero">

    <label for="titulo">titulo</label>
    <input type="text" name="titulo" id="titulo">

    <button type="submit">Enviar archivo</button>

  </form>


</fieldset>

<?php


fin_html();

?>