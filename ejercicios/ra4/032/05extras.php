<?php

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

// 1. validar jwt

if (!isset($_COOKIE['jwt'])) {
  $_SESSION['errores'][] = 'no se ha iniciado sesion';
  header('location: 01login.php');
  exit();
}
$usuario = verificarJWT($_COOKIE['jwt']);
if (!$usuario) {
  $_SESSION['errores'][] = 'login invalido';
  header('location: 01login.php');
  exit();
}


// sanear y validar ingredientes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['operacion'] == 'ingredientes') {

  if (empty($_POST['ingredientes'])) {
    $_SESSION['errores'][] = 'no se han añadido ingredientes';
    header('location: 01login.php');
    exit();
  }

  $ingredientes = filter_input(INPUT_POST, 'ingredientes', FILTER_SANITIZE_SPECIAL_CHARS, ['flags' => FILTER_REQUIRE_ARRAY]);
  $ingredientes_validados = [];

  foreach ($ingredientes as $key) {
    if (array_key_exists($key, ($_SESSION['tipo'] == 1 ? $ingredientes_veg : $ingredientes_no_veg))) {
      $ingredientes_validados[] = ($_SESSION['tipo'] == 1 ? $ingredientes_veg[$key] : $ingredientes_no_veg[$key]);
    }
  }
  if (empty($ingredientes_validados)) {
    $_SESSION['errores'][] = 'ingredientes no validados';
    header('location: 01login.php');
    exit();
  }

  $_SESSION['ingredientes'] = $ingredientes_validados;

  inicio_html("0 Pantalla extras", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

?>

  <h1>Bienvenido <?= $usuario['nombre'] ?></h1>
  <h2>Datos de usuario</h2>
  <h3>email: <?= $usuario['email'] ?></h3>
  <h3>direccion <?= $usuario['direccion'] ?></h3>
  <h3>telefono <?= $usuario['telefono'] ?></h3>

  <fieldset>
    <legend>Extras</legend>
    <form action="06final.php" method="POST">
      <?php foreach ($extras as $key => $value) : ?>
        <label for="<?= $key ?>"><?= $value['name'] ?> => <?= $value['precio'] ?></label>
        <input type="checkbox" name="<?= $key ?>" id="<?= $key ?>">
      <?php endforeach ?>

      <button type="submit" name="operacion" id="operacion" value="extras"> comprar</button>
    </form>
  </fieldset>


<?php



  fin_html();
} else {
  header("Location: 01login.php");
}
?>