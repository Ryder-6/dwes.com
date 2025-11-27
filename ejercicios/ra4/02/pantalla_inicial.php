<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');

inicio_html("02 Pantalla inicial", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  ?>
  <h1>Visionado de imagenes</h1>
  
  <fieldset>
    <legend>Directorio actual <?= $_SERVER['DOCUMENT_ROOT'] ?></legend>
    <form action="pantalla_lista.php" method="POST">

      <label for="directorio">directorio de imagenes</label>
      <input type="text" name="directorio" id="directorio">

      <button type="submit">Buscar imagen</button>
    </form>
  </fieldset>
  <?php
}



fin_html();
?>