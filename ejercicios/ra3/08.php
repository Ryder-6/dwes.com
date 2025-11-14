<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');

inicio_html('08', ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

$TIPOS_MIME_PERMITIDOS = ['image/jpg', 'image/png', 'image/webp'];

$archivos_subidos = [];

function error($n_error)
{
  $errores = [
    1 => 'Error, login invalido',
  ];
}

function guarda_fichero($fichero, $tipos_permitidos, $nombre = "")
{
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

  if ($error == UPLOAD_ERR_FORM_SIZE) {
    error(3);
  }
}

function lista_ficheros() {}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // 1. sanear
  $saneamiento = [
    'login' => FILTER_SANITIZE_SPECIAL_CHARS,
    'titulo' => FILTER_SANITIZE_SPECIAL_CHARS
  ];

  $datos_saneados = filter_input_array(INPUT_POST, $saneamiento, true);
  // 2. validar

  $login_regex = '/^[a-zA-Z0-9]+$/';

  $datos_validados['login'] = preg_match($login_regex, $datos_saneados['login']) ? $datos_saneados['login'] : error(1);
  $datos_validados['titulo'] = $datos_saneados['titulo'] ? $datos_saneados['titulo'] : '';

  if ($datos_validados['login'] && $_FILES['fichero']) {
    guarda_fichero($_FILES['fichero'], $tipos_permitidos, $datos_validados['titulo']);
  }

  // 3. presentar

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