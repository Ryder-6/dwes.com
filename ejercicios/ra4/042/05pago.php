<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

// 1. jwt
$usuario = comprobarJWT();

// 2. sanear validar

$pintura = filter_input(INPUT_POST, 'pintura', FILTER_SANITIZE_SPECIAL_CHARS);
$extras_saneados = filter_input(INPUT_POST, 'extras', FILTER_SANITIZE_SPECIAL_CHARS, ['flags' => FILTER_REQUIRE_ARRAY]);

if (array_key_exists($pintura, $pinturas)) {
  $pintura = $pinturas[$pintura];
} else {
  $_SESSION['errores'][] = 'no se ha podido validar la pintura elegida';
  header('location: 01login.php');
}

$extras_validados = [];
if (!empty($extras_saneados)) {
  foreach ($extras_saneados as $key) {
    if (array_key_exists($key, $extras)) {
      $extras_validados[] = $extras[$key];
    }
  }
}



$_SESSION['coche'][$usuario['email']]['pintura'] = $pintura;
$_SESSION['coche'][$usuario['email']]['extras'] = $extras_validados;

$coche = $_SESSION['coche'][$usuario['email']];

inicio_html('05 financiacion a elegir', ['/estilos/general.css', '/estilos/formulario.css', '/estilos/tabla.css']);
?>
<h1>Toyota</h1>
<h2>email: <?= $usuario['email'] ?> </h2>
<h2>telefono: <?= $usuario['telefono'] ?> </h2>
<h2>direccion: <?= $usuario['direccion'] ?> </h2>

<h3>modelo: <?= $coche['modelo']['name'] ?></h3>
<h3>motor: <?= $coche['motor']['name'] ?></h3>
<h3>pintura: <?= $coche['pintura']['name'] ?></h3>

<fieldset>
  <legend> pintura y extras</legend>
  <form action="06final.php" method="POST">

    <?php foreach ($financiacion as $key => $value) :  ?>
      <div>
        <label for="financiacion"><?= $value['name'] ?></label>
        <input type="radio" name="financiacion" id="financiacion" value="<?= $key ?>">
      </div>
    <?php endforeach ?>

    <button type="submit" name="operacion" id="operacion" value="financiacion"> continuar => </button>
  </form>
</fieldset>


<?php
fin_html();
?>