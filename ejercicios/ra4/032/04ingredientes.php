<?php

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

// 1. verificar jwt

if (!isset($_COOKIE['jwt'])) {
  $_SESSION['errores'][] = 'Error, no se ha iniciado session';
  header('location: 01login.php');
  exit();
}

$usuario = verificarJWT($_COOKIE['jwt']);

if (!$usuario) {
  $_SESSION['errores'][] = 'error, login ivaldio';
  header("location: 01login.php");
  exit();
}



// 1=vegana | 0=no_vegana
$tipo = filter_input(INPUT_POST, 'tipo', FILTER_VALIDATE_BOOLEAN);
$_SESSION['tipo'] = $tipo;

inicio_html("0 Pantalla ingredientes", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

?>
<h1>Bienvenido <?= $usuario['nombre'] ?></h1>
<h2>Datos de usuario</h2>
<h3>email: <?= $usuario['email'] ?></h3>
<h3>direccion <?= $usuario['direccion'] ?></h3>
<h3>telefono <?= $usuario['telefono'] ?></h3>

<form action="05extras.php" method="POST">
  <fieldset>
    <legend>ingredientes deseados</legend>
    <label for="ingredientes">ingredientes</label>
    <select name="ingredientes[]" id="ingredientes" multiple>
      <?php foreach (($tipo ? $ingredientes_veg : $ingredientes_no_veg) as $key => $value) : ?>
        <option value="<?= $key ?>"><?= $value['name'] ?> => <?= $value['precio'] ?>€</option>
      <?php endforeach; ?>
    </select>
    <button type="submit" name="operacion" id="operacion" value="ingredientes">Extras</button>
  </fieldset>

</form>
<?php
fin_html();


?>