<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');


inicio_html("01 Pantalla inicial", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>
  <h1>directorio de busqueda</h1>
  <h4>Directorio actual: <?= $_SERVER['DOCUMENT_ROOT'] ?></h4>

  <fieldset>
    <legend>buscar</legend>
    <form action="pantalla_lista.php" method="post">

      <label for="directorio">directorio</label>
      <input type="text" name="directorio" id="directorio">


      <button type="submit">Buscar</button>
    </form>
  </fieldset>

<?php
}

fin_html();


?>