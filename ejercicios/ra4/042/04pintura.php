<?php

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

// 1. jwt
/*
if (!$_COOKIE['jwt']) {
  $_SESSION['errores'][] = 'la sesion ha caducado';
  header('location: 01login.php');
  exit();
}

$usuario = verificarJWT($_COOKIE['jwt']);
if (!$usuario) {
  $_SESSION['errores'][] = ' no se ha podido verificar el usuario';
  header('location: 01login.php');
  exit();
}
*/

$usuario = comprobarJWT();

// 2. sanear validar
$modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_SPECIAL_CHARS);
$motor = filter_input(INPUT_POST, 'motor', FILTER_SANITIZE_SPECIAL_CHARS);

if (array_key_exists($modelo, $modelos)) {
  $modelo = $modelos[$modelo];
} else {
  $_SESSION['errores'][] = "no se ha podido validar el modelo ";
}

if (array_key_exists($motor, $motores)) {
  $motor = $motores[$motor];
} else {
  $_SESSION['errores'][] = 'no se ha podido validar la motorizacion';
}

if (isset($_SESSION['errores'])) {
  header('location: 01login.php');
  exit();
}



$_SESSION['coche'][$usuario['email']] = [
  'modelo' => $modelo,
  'motor' => $motor,
];

$coche = $_SESSION['coche'][$usuario['email']];


inicio_html('03 pintura extras', ['/estilos/general.css', '/estilos/formulario.css', '/estilos/tabla.css']);
?>
<h1>Toyota</h1>
<h2>email: <?= $usuario['email'] ?> </h2>
<h2>telefono: <?= $usuario['telefono'] ?> </h2>
<h2>direccion: <?= $usuario['direccion'] ?> </h2>

<h3>modelo: <?= $coche['modelo']['name'] ?></h3>
<h3>motor: <?= $coche['motor']['name'] ?></h3>

<fieldset>
  <legend> pintura y extras</legend>
  <form action="05pago.php" method="POST">
    <label for="pintura">pintura</label>
    <select name="pintura" id="pintura">
      <?php foreach ($pinturas as $key => $value) :  ?>
        <option value="<?= $key ?>"> <?= $value['name'] ?> => <?= $value['precio'] ?>€</option>
      <?php endforeach ?>
    </select>
      <?php foreach ($extras as $key => $value) :  ?>
        <div>
          <label for="extras[]"><?= $value['name'] ?> => <?= $value['precio'] ?>€</label>
          <input type="checkbox" name="extras[]" id="extras[]" value="extras[<?= $key ?>]">
        </div>
      <?php endforeach ?>

    <button type="submit" name="operacion" id="operacion" value="modelo"> continuar => </button>
  </form>
</fieldset>


<?php
fin_html();
?>