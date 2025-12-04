<?php

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

if (!isset($_COOKIE['jwt'])) {
  $_SESSION['errores'][] = 'ha sesion ha caducado';
  header('location: 01login.pho');
  exit();
}

$usuario = verificarJWT($_COOKIE['jwt']);

if (!$usuario) {
  $_SESSION['errores'][] = 'no se ha podido verificar el usuario';
  header('location: 01login.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['operacion'] == 'extras') {
  $extras_saneados = filter_input(INPUT_POST, 'extras', FILTER_SANITIZE_SPECIAL_CHARS, ['flags' => FILTER_REQUIRE_ARRAY]);
  $pintura_saneada = filter_input(INPUT_POST, 'pintura', FILTER_SANITIZE_SPECIAL_CHARS);

  if (!empty($extras_saneados)) {
    foreach ($extras_saneados as $key) {
      if (array_key_exists($key, $extras)) {
        $_SESSION['extras'][] = $extras[$key];
      }
    }
  }

  if (!empty($pintura_saneada) && array_key_exists($pintura_saneada, $pinturas)) {
    $_SESSION['pintura'] = $pinturas[$pintura_saneada];
  }else {
    $_SESSION['errores'][] = 'no se ha indicado pintura';
    header('location: 01login.php');
    exit();
  }


  inicio_html("05 Pantalla pagos", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);
?>

  <h1>cochesitos pum pum</h1>
  <h2>bienvendo usuario <?= $usuario['email'] ?></h2>
  <fieldset>
    <legend>Extras el modelo <?= $_SESSION['modelo']['name'] ?></legend>
    <form action="06final.php" method="POST">
      <?php foreach ($financiacion as $key => $value) :  ?>
        <label for="pago"><?= $value['name'] ?> => <?= $key['anios'] ?></label>
        <input type="radio" name="pago" id="pago" value="<?= $key ?>">;
      <?php endforeach ?>

      <button type="submit" name="operacion" id="operacion" value="pago"> Final</button>
    </form>
  </fieldset>
<?php
  fin_html();
}
header('location: 01login.php')
?>