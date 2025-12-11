<?php


session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

$usuario = comprobarJWT();

$precio_total = 0;

inicio_html('01 login', ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);
?>
<h1>Ultramarinos Guilliman</h1>
<h2>nombre <?= $usuario['name'] ?></h2>
<h3>email <?= $usuario['email'] ?></h3>
<h4>fecha <?= $_SESSION['fecha']->format('d-m-Y h:m') ?></h4>

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
    foreach ($_SESSION['carrito'] as $key => $value) :
    $precio_total += $value['precio'] * $value['cantidad'];
    ?>
      <tr>
        <td><?= $value['name'] ?></td>
        <td><?= $value['precio'] ?></td>
        <td><?= $value['cantidad'] ?></td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>
  
<h4>Total: <?= $precio_total ?></h4>

<form action="01login.php" method="GET">
<button type="submit" name="operacion" id="operacion" value="cerrar">Tramitar pago</button>
</form>
<?php
fin_html();
?>