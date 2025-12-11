<?php

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

$usuario = comprobarJWT();

$precio_total = 0;
inicio_html('03 entradas', ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);
?>
<h1>Conciertos TeCobroDeMas</h1>

<h2><?= $usuario['name'] ?></h2>
<h2><?= $usuario['id'] ?></h2>
<h3><?= $_SESSION['fecha']->format('d-m-Y h:m') ?></h3>

<table>
  <thead>
    <tr>
      <th>espectaculo</th>
      <th>fila</th>
      <th>precio</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($_SESSION['carrito'] as $key => $value) : 
    $precio_total += $value['precio'];  
    ?>
      <tr>
        <td><?= $value['name'] ?></td>
        <td><?= $value['fila'] ?></td>
        <td><?= $value['precio'] ?></td>
      </tr>
    <?php endforeach; ?>

  </tbody>
</table>

<h4>Precio <?= $precio_total ?></h4>

<form action="01login.php" method="GET">
  <button type="submit" name="operacion" id="operacion" value="cerrar">Terminar</button>
</form>
<?php
fin_html();
?>