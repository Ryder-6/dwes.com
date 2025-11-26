<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if ($_POST['operacion'] == 'buscar') {
    $directorio = filter_input(INPUT_POST, 'directorio', FILTER_SANITIZE_SPECIAL_CHARS);

    if (isset($_COOKIE['directorio'])) {
      setcookie('directorio', '', time() - 3600, '/');
    }
     
    setrawcookie('directorio', $directorio, time() +  60 * 60 , '/');
    header('location: /ejercicios/ra4/01/pantalla_lista.php');
    exit();
  }
}

inicio_html("01 Pantalla inicial", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  ?>
  <h1>Busqueda de archivos</h1>
  <fieldset>
    <legend>Busqueda de archivos en carpeta</legend>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">

    <h3>se busca desde <?= $_SERVER['DOCUMENT_ROOT'] ?></h3>
    <label for="directorio">directorio :</label>
    <input type="text" name="directorio" id="directorio">

    <button type="submit" name="operacion" value="buscar">buscar</button>
    </form>
  </fieldset>
  
  <?php
}

fin_html();
?>