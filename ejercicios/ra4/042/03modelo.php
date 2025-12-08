<?php


session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');


// 1. verificar jwt
/*
if (!$_COOKIE['jwt']) {
  $_SESSION['errores'][] = 'Session ha caducado';
  header('location: 01login.php');
  exit();
}

$usuario = verificarJWT($_COOKIE['jwt']);
if (!$usuario) {
  $_SESSION['errores'][] = 'no se ha podido verificar el usuario';
  header('location: 01login.php');
  exit();
}*/

$usuario = comprobarJWT();

inicio_html('03 modelo motor', ['/estilos/general.css', '/estilos/formulario.css', '/estilos/tabla.css']);

?>
<h1>Toyota</h1>
<h2>email: <?= $usuario['email'] ?> </h2>
<h2>telefono: <?= $usuario['telefono'] ?> </h2>
<h2>direccion: <?= $usuario['direccion'] ?> </h2>

<fieldset>
  <legend>configurador</legend>
  <form action="04pintura.php" method="POST">
    <label for="modelo">modelo</label>
    <select name="modelo" id="motor">
      <?php foreach ($modelos as $key => $value) : ?>
        <option value="<?= $key ?>"><?= $value['name'] ?> => <?= $value['precio'] ?>€</option>
      <?php endforeach ?>
    </select>

    <label for="motor">motor</label>
    <select name="motor" id="motor">
      <?php foreach ($motores as $key => $value) : ?>
        <option value="<?= $key ?>"><?= $value['name'] ?> => <?= $value['precio'] ?>€</option>
      <?php endforeach ?>
    </select>

    <button type="submit" name="operacion" id="operacion" value="motor">continuar =></button>
  </form>
</fieldset>

<?php
fin_html();
?>