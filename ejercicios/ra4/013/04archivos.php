<?php

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

$usuario = comprobarJWT();

function escanearDirectorio($directorio)
{

  $archivos = scandir($directorio);
  echo <<<FORMULARIO
  <form action="05info.php">
  <label for="fichero">ficheros disponibles</label>
  <select name="fichero" id="fichero">
  FORMULARIO;

  foreach ($archivos as $archivo) {

    echo "<option value='$archivo'>$archivo</option>";
  }
  echo <<<FORMULARIO
        </select>
        <button type="submit" name="operacion" id="operaicon" value="informacion">ver informacion</button>
        </form>
  FORMULARIO;
}

$directorio = filter_input(INPUT_POST, 'directorio', FILTER_SANITIZE_SPECIAL_CHARS);

if (!$directorio) {
  $_SESSION['errores'][] = ' nos se ha indicado directorio';
  header('location: 01login.php');
  exit();
}

$directorio = $_SERVER['DOCUMENT_ROOT'] . (str_starts_with('/', $directorio) ? $directorio : '/' . $directorio);

if (!is_dir($directorio)) {
  $_SESSION['errores'][] = 'No es un directorio';
  header('location: 01login.php');
  exit();
} else {
  inicio_html('03 ruta', ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

  escanearDirectorio($directorio);
  fin_html();
}
