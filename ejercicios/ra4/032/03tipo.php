<?php
session_start();


require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');


// 1. verificar JWT
if (!isset($_COOKIE['jwt'])) {
  $_SESSION['errores'][] = 'No se ha iniciado sesion';
  header('Location: 01login.php');
  exit();
}


$usuario = verificarJWT($_COOKIE['jwt']);

if (!$usuario) {
  $_SESSION['errores'][] = "No se ha podido verificar el usuario";
  header('location: 01login.php');
  exit();
}


inicio_html("02 Pantalla tipos", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

?>
<h1>Bienvenido <?= $usuario['nombre'] ?></h1>
<h2>Datos de usuario</h2>
<h3>email: <?= $usuario['email'] ?></h3>
<h3>direccion <?= $usuario['direccion'] ?></h3>
<h3>telefono <?= $usuario['telefono'] ?></h3>

<fieldset>
  <legend>Tipo de pizza</legend>
  <form action="04ingredientes.php" method="POST">
  <div>
    <label for="tipo">Vegetariana</label>
    <input type="radio" name="tipo" id="tipo" value="1">

  </div>
    <div>
    <label for="tipo">NO-Vegetariana</label>
    <input type="radio" name="tipo" id="tipo" value="0">
  </div>

  <button type="submit" name="operacion" id="operacion" value="enviar">enviar</button>
  </form>
</fieldset>
<?php

fin_html();
?>