<?php

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['operacion'] == 'modelo_motor') {
  // 1. jwt

  if (!isset($_COOKIE['jwt'])) {
    $_SESSION['errores'][] = 'La sesion ha caducado';
    header('location: 01login.php');
    exit();
  }

  $usuario = verificarJWT($_COOKIE['jwt']);
  if (!$usuario) {
    $_SESSION['errores'][] = 'no se ha podido verificar identidad';
    header('location: 01login.php');
    exit();
  }

  // 2. sanear validar datos entrantes

  $modelo_saneado = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_SPECIAL_CHARS);
  $motor_saneado = filter_input(INPUT_POST, 'motor', FILTER_SANITIZE_SPECIAL_CHARS);

  $modelo_validado = array_key_exists($modelo_saneado, $modelos) ? $modelos[$modelo_saneado] : false;
  $motor_validado = array_key_exists($motor_saneado, $motores) ? $motores[$motor_saneado] : false;

  if (!$modelo_validado) $_SESSION['errores'][] = 'Modelo invalido introducido';
  if (!$motor_validado) $_SESSION['errores'][] = 'Motor invalido introducido';

  if (isset($_SESSION['errores'])) {
    header('location: 01login.php');
    exit();
  }

  $_SESSION['modelo'] = $modelo_validado;
  $_SESSION['motor'] = $motor_validado;

  inicio_html("04 Pantalla pintura extras", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);
?>

  <h1>cochesitos pum pum</h1>
  <h2>bienvendo usuario <?= $usuario['email'] ?></h2>
  <fieldset>
    <legend>Extras el modelo <?= $_SESSION['modelo']['name'] ?></legend>
    <form action="05pago.php" method="POST">
      <label for="pintura">pintuas</label>
      <select name="pintura" id="pintura">
        <?php foreach ($pinturas as $key => $value) : ?>
          <option value="<?= $key ?>"><?= $value['name'] ?> => <?= $value['precio'] ?></option>
        <?php endforeach ?>
      </select>

      <?php foreach ($extras as $key => $value) :  ?>
        <div>
          <input type="checkbox" name="extras[]" id="<?= $key ?>" value="<?= $key ?>">
          <label for="extras[]"> <?= $value['name'] ?> => <?= $value['precio'] ?></label>
        </div>
      <?php endforeach ?>


      <button type="submit" name="operacion" id="operacion" value="extras"> metodo de pago</button>
    </form>
  </fieldset>
<?php
  fin_html();
}
header('lotacion: 01login.php')
?>