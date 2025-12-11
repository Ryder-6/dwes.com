<?php

session_start();
require_once($_SERVER['DOCUMENT_ROOT']. '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT']. '/jwt/include_jwt.php');
require_once('include.php');

$usuario = comprobarJWT();


inicio_html('03 ruta', ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);
?>
<h1>Ficheros Gurtelos</h1>
<h2>ruta  actual: <?= $_SERVER['DOCUMENT_ROOT'] ?></h2>
<form action="04archivos.php" method="post">
  <fieldset>
    <legend>login</legend>
    <label for="directorio">Ruta;</label>
    <input type="text" name="directorio" id="directorio">

  </fieldset>
  <button type="submit" name="operacion" id="operaicon" value="directorio">buscar</button>
</form>



<?php 

fin_html();

?>