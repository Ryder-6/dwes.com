<?php

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

if (!isset($_COOKIE['jwt'])) {
  $_SESSION['errores'][] = 'sesion expirada';
  header('location: 01login.php');
  exit();
}

$usuario = verificarJWT($_COOKIE['jwt']);
if (!$usuario) {
  $_SESSION['errores'][] = 'no se ha podido verificar';
  header('location: 01login.php');
  exit();
}

inicio_html("03 Pantalla modelos motor", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);
?>

<h1>cochesitos pum pum</h1>
<h2>bienvendo usuario <?= $usuario['email'] ?></h2>

<fieldset>
  <legend>Modelo y motor</legend>
  <form action="04extras.php" method="POST">
    <label for="modelo">modelo</label>
    <select name="modelo" id="modelo">
      <?php foreach ($modelos as $key => $value) : ?>
        <option value="<?= $key ?>"> <?= $value['name'] ?> => <?= $value['precio'] ?></option>
      <?php endforeach ?>
    </select>

    <label for="motor">motor</label>
    <select name="motor" id="motor">
      <?php foreach ($motores as $key => $value) : ?>
        <option value="<?= $key ?>"> <?= $value['name'] ?> => <?= $value['precio'] ?></option>
      <?php endforeach ?>
    </select>

    <button type="submit" name="operacion" id="operacion" value="modelo_motor">extras</button>
  </form>
</fieldset>
<?php

fin_html();
?>