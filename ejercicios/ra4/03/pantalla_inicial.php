<?php

require_once($_SERVER['DOCUMENT_ROOT']. '/includes/funciones.php');

inicio_html("03 Pantalla inicial", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  ?>
  <h1>La michipizzeria</h1>
  <h2>precio base 5€</h2>

  <fieldset>
    <legend>tipo</legend>
  <form action="pantalla_ingredientes.php" method="POST">
    <div>
    <label for="vegetariana">Vegetariana</label>
    <input type="radio" name="tipo" id="vegetariana" value="0">

    </div>
    <div>
    <label for="no_vegetariana">No Vegetariana => +2€</label>
    <input type="radio" name="tipo" id="no_vegetariana" value="1">
    </div>

    <button type="submit">Añadir ingredientes</button>
  </form>
  </fieldset>
  <?php
}


fin_html();

?>