<?php

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');

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


$ingredientes_veg = [
  'pe' => ['name' => 'Pepino', "precio" => 1],
  'ca' => ['name' => 'Calabacín', "precio" => 1.5],
  'pv' => ['name' => 'Pimiento verde', "precio" => 1.25],
  'pr' => ['name' => 'Pimiento rojo', "precio" => 1.75],
  'to' => ['name' => 'Tomate', "precio" => 1.5],
  'ac' => ['name' => 'Aceitunas', "precio" => 3],
  'ce' => ['name' => 'Cebolla', "precio" => 1],
];

$ingredientes_no_veg = [
  'at' => ['name' => 'Atún', 'precio' => 2],
  'cp' => ['name' => 'Carne picada', 'precio' => 2.5],
  'pp' => ['name' => 'Peperoni', 'precio' => 1.75],
  'mo' => ['name' => 'Morcilla', 'precio' => 2.25],
  'an' => ['name' => 'Anchoas', 'precio' => 1.5],
  'sa' => ['name' => 'Salmón', 'precio' => 3],
  'ga' => ['name' => 'Gambas', 'precio' => 4],
  'la' => ['name' => 'Langostinos', 'precio' => 4],
  'me' => ['name' => 'Mejillones', 'precio' => 2],
];


// 1=vegana | 0=no_vegana
$tipo = filter_input(INPUT_POST, 'tipo', FILTER_VALIDATE_BOOLEAN);
setcookie('tipo', $tipo, time() +20*60 , '/');

inicio_html("02 Pantalla tipos", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

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
    <button type="submit" name="operacion" id="operacion" value="enviar">Extras</button>
  </fieldset>

</form>
<?php
fin_html();


?>