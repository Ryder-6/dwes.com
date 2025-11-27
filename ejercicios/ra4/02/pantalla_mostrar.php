<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');

inicio_html("01 Pantalla mostrar", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_COOKIE['directorio']) && isset($_POST['ficheros'])) {
  $ficheros = filter_input(INPUT_POST, 'ficheros', FILTER_SANITIZE_SPECIAL_CHARS, [
    'flags' => FILTER_REQUIRE_ARRAY
  ]);

  if ($ficheros) {

    $ruta_rel = $_COOKIE['directorio'];
    $ruta_abs = $_SERVER['DOCUMENT_ROOT'] . $ruta_rel;

    foreach ($_POST['ficheros'] as $fichero) :
?>
      <fieldset>
        <legend><?= $fichero ?></legend>
        <img src="<?= $ruta_rel . '/' . $fichero ?>" alt="<?= $fichero ?>">
      </fieldset>
    <?php

    endforeach;
    ?>
    <a href="pantalla_inicial.php"> volver al inicio</a>

  <?php


  } else {
  ?>
    <a href="pantalla_inicial.php"> error, volver al inicio</a>
<?php
  }
}


fin_html();
