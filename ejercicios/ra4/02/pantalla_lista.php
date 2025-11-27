<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');

inicio_html("02 Pantalla lista", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['directorio'])) {

  $directorio = strpos($_POST['directorio'], '/') === 0 ? $_POST['directorio'] : '/' . $_POST['directorio'];

  if (is_dir($_SERVER['DOCUMENT_ROOT'] . $directorio)) {

    if (isset($_COOKIE['directorio'])) setcookie('directorio', '', 0, '/');

    setcookie('directorio', $directorio, time() + 3600, '/');

    $archivos = scandir($_SERVER['DOCUMENT_ROOT'] . $directorio);

?>
    <form action="pantalla_mostrar.php" method="POST">
      <table>
        <thead>
          <tr>
            <th>Archivo</th>
            <th>marcar</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($archivos as $archivo) :
            if (strpos($archivo, '.') === 0) continue;
            if (strpos(mime_content_type($_SERVER['DOCUMENT_ROOT'] . $directorio . '/' . $archivo), 'image/') !== 0) continue;

          ?>
            <tr>
            <td><?= $archivo ?></td>
            <td><input type="checkbox" name="ficheros[<?= $archivo ?>]" id="<?= $archivo ?>" value="<?= $archivo ?>"></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <button type="submit">Mostrar imagenes</button>
    </form>
  <?

  } else {
  ?>
    <a href="pantalla_inicial.php"> error, no es un directorio</a>
  <?php
  }
} else {
  ?>
  <a href="pantalla_inicial.php"> error, volver al inicio</a>
<?php

}


fin_html();
?>