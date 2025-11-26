<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');

inicio_html("01 Pantalla inicial", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

function escanear_listar($directorio)
{
  $archivos = scandir($directorio);

  echo "<form action='pantalla_informacion.php' method='POST'>";
  echo "<label for='fichero'>Selecciona un fichero:</label>";
  echo "<select name='fichero' id='fichero'>";

  foreach ($archivos as $archivo) {

    if ($archivo === "." || $archivo === "..") continue;

    if (is_file($directorio . "/$archivo")) {
      echo "<option value='$archivo'>$archivo</option>";
    }
  }

  echo "</select>";
  echo "<br><br>";
  echo "<button type='submit'>Ver información y descargar</button>";


  echo "</form>";
}

if ($_COOKIE['directorio'] && is_dir($_SERVER['DOCUMENT_ROOT'] . $_COOKIE['directorio'])) {
  # code...

?>
  <h2>lista de ficheros del directorio </h2>
<?php
  escanear_listar($_SERVER['DOCUMENT_ROOT'] . $_COOKIE['directorio']);

} else {
?>
  <h3>no has mandado directorio</h3>

<?php
}
fin_html();
?>