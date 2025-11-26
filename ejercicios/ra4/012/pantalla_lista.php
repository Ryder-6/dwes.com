<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');


inicio_html("01 Pantalla inicial", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['directorio']) {
  $directorio = filter_input(INPUT_POST, 'directorio', FILTER_SANITIZE_SPECIAL_CHARS);

  $directorio = strpos($directorio, '/') === 0 ? $directorio : '/' . $directorio;

  if (isset($_COOKIE['directorio'])) {
    setcookie('directorio', '', 0, '/');
  }

  setcookie('directorio', $directorio, time() + 60 * 60, '/');

  if (is_dir($_SERVER['DOCUMENT_ROOT'] . $directorio)) {
    $archivos = scandir($_SERVER['DOCUMENT_ROOT'] . $directorio);

    if (isset($archivos) && !empty($archivos)) {
?>
      <table>
        <thead>
          <tr>
            <th>nombre Fichero</th>
            <th>Informacion</th>
          </tr>
        </thead>
        <tbody>
          <form action="pantalla_informacion.php" method="POST">
            <?php foreach ($archivos as $archivo) :
              if (strpos($archivo, '.') === 0) continue; // quitar ocultos
            ?>
              <tr>
                <td><?= $archivo ?></td>
                <td><button type="submit" name="fichero" value="<?= $archivo ?>"> informacion <?= $archivo ?></button></td>
              </tr>
            <?php endforeach; ?>
          </form>
        </tbody>

      </table>


  <?php
    } else {
      echo '<a href="pantalla_inicial.php">No hay ficheros en directorio</a>';
    }
  }
} else {
  ?>

  <a href="pantalla_inicial.php">Volver a intentarlo</a>

<?php

}

fin_html();


?>