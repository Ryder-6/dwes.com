<?php

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

// 1. validar JWT

if (!isset($_COOKIE['jwt'])) {
  $_SESSION['errores'][] = 'sesion no iniciada';
  header('location: 01login.php ');
  exit();
}
$usuario = verificarJWT($_COOKIE['jwt']);

if (!$usuario) {
  $_SESSION['errores'][] = 'sesion no iniciada';
  header('location: 01login.php ');
  exit();
}


// 2. validar post entrante
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['operacion'] == 'extras') {



  $extras_escodigos = [];
  foreach ($extras as $key => $value) {
    $extras_valido = filter_input(INPUT_POST, "$key", FILTER_VALIDATE_BOOLEAN);
    if ($extras_valido) {
      $extras_escodigos[] = $extras[$key];
    }
  }

  $precio_final = 5;

  foreach ($_SESSION['ingredientes'] as $key => $value) {
    $precio_final += $value['precio'];
  }
  foreach ($extras_escodigos as $key => $value) {
    $precio_final += $value['precio'];
  }
  if ($_SESSION['tipo'] == 0) $precio_final += 2;


  inicio_html("0 Pantalla extras", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

?>
  <h1>Bienvenido <?= $usuario['nombre'] ?></h1>
  <h2>Datos de usuario</h2>
  <h3>email: <?= $usuario['email'] ?></h3>
  <h3>direccion <?= $usuario['direccion'] ?></h3>
  <h3>telefono <?= $usuario['telefono'] ?></h3>

  <h2> Precio total <?= $precio_final ?></h2>
  <fieldset>
    <legend>resumen pedido</legend>
    <table>
      <thead>
        <tr>
          <th>tipo</th>
          <th>ingredientes</th>
          <th>extras</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?= $_SESSION['tipo'] ? 'Vegetariana' : 'No vegetariana' ?></td>
          <td>
            <?php foreach ($_SESSION['ingredientes'] as $key => $value) : ?>
              <?= $value['name'] ?> => <?= $value['precio'] ?>€
            <?php endforeach  ?>
          </td>
          <td>
            <?php foreach ($extras_escodigos as $key => $value) : ?>
              <?= $value['name'] ?> => <?= $value['precio'] ?>€
            <?php endforeach  ?>
          </td>
        </tr>
      </tbody>
    </table>

  </fieldset>

  <form action="01login.php" method="GET">
    <button type="submit" name="operacion" id="operacion" value="cerrar"> pedir otras</button>
  </form>

<?php

  fin_html();
} else {
  header('location: 01login.php');
}
