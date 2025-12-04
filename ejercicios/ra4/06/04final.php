<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

if (!isset($_COOKIE['jwt'])) {
  $_SESSION['errores'][] = 'no se ha iniciado sesion';
  header('location: 01carrito.php');
  exit();
}

$usuario = verificarJWT($_COOKIE['jwt']);
if (!$usuario) {
  $_SESSION['errores'][] = 'no se ha podido verificar el usuario';
  header('location: 01carrito.php');
  exit();
}

// sanear pago
$t_titular = filter_input(INPUT_POST, 'titular', FILTER_SANITIZE_SPECIAL_CHARS);
$t_numero = filter_input(INPUT_POST, 'n_tarjeta', FILTER_SANITIZE_SPECIAL_CHARS);
$t_cvv = filter_input(INPUT_POST, 'cvv', FILTER_SANITIZE_SPECIAL_CHARS);

$precio_total = 0;
foreach ($_SESSION['carrito'] as $key => $value) {
  $precio_total += $value['precio'] * $value['cantidad'];
}

inicio_html("04 Pantalla factura", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);
?>
<h1>Ultramarinos Guilliman</h1>
<h2>nombre: <?= $usuario['nombre'] ?></h2>
<h2>email: <?= $usuario['email'] ?></h2>
<h2>direccion: <?= $usuario['direccion'] ?></h2>
<h2>telefono: <?= $usuario['telefono'] ?></h2>

<hr>
<h4>Precio total <?= $precio_total ?></h4>

<h4>Carrito:</h4>
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

<form action="01carrito.php" method="GET">
  <button type="submit" name="operacion" id="operacion" value="cerrar"> terminar pedido </button>
</form>
<?php
fin_html();


?>