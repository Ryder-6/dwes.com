<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');


if (!isset($_COOKIE['jwt'])) {
  header('location: 02login.php');
  exit();
}

$usuario = verificarJWT($_COOKIE['jwt']);
if (!$usuario) {
  header('location: 02login.php');
  exit();
}

if (!isset($_SESSION['carrito'])) {
  $_SESSION['errores'][] = 'Carrito vacio';
  header('location: 01carrito.php');
  exit();
}

inicio_html("03 Pantalla pago", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);
?>
<h1>Ultramarinos Guilliman</h1>
<fieldset>
  <legend>metodo pago</legend>
  <form action="04final.php" method="POST">
    <label for="titular">titular de la cuenta</label>
    <input type="text" name="titular" id="titular">

    <label for="n_tarjeta">numero de tarjeta</label>
    <input type="text" name="n_tarjeta" id="n_tarjeta">

    <label for="cvv">cvv</label>
    <input type="text" name="cvv" id="cvv">

    <button type="submit" name="operacion" id="operacion" value="pago">Pagar</button>
  </form>
</fieldset>



<h4>Carrito actual:</h4>
<table>
  <thead>
    <tr>
      <th>producto</th>
      <th>precio</th>
      <th>cantidad</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if (isset($_SESSION['carrito'])) {
      foreach ($_SESSION['carrito'] as $key => $value) : ?>
        <tr>
          <td><?= $value['name'] ?></td>
          <td><?= $value['precio'] ?>€</td>
          <td><?= $value['cantidad'] ?></td>
        </tr>

    <?php endforeach;
    } ?>
  </tbody>
</table>
<?php

fin_html();
?>