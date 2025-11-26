<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');


inicio_html("01 Pantalla inicial", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fichero']) && $_COOKIE['directorio']) {


  $fichero = $_POST['fichero'];

  $ruta_rel = $_COOKIE['directorio'] . '/' . $fichero;
  $ruta_abs = $_SERVER['DOCUMENT_ROOT'] . $ruta_rel;

  if (is_file($ruta_abs)) {
    $type = mime_content_type($ruta_abs);
    $size = filesize($ruta_abs);

    ?>
    <h3>Nombre: <?= $fichero ?></h3>
    <h3>tipo: <?= $type ?></h3>
    <h3>tamaño: <?= $size ?> bytes</h3>
    <a href="<?= $_SERVER['PHP_SELF'] ?>" download="<?= $ruta_rel ?>"> descargar </a>
    <?php

  } else {

    setcookie('directorio', '', 0, '/');
?>
    <h3>error, no es un fichero</h3>

    <a href="pantalla_inicial.php">Volver a intentarlo</a>
  <?php
  }
} else {
  ?>
  <a href="pantalla_inicial.php">Volver al inicio</a>
<?php
}

fin_html();


?>