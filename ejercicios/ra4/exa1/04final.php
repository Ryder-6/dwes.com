<?php


session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

if (!isset($_COOKIE['jwt'])) {
  $_SESSION['errores'][] = 'la sesion ha caducado';
  header('location: 01inicio.php');
  exit();
}

$usuario = verificarJWT($_COOKIE['jwt']);
if (!$usuario) {
  $_SESSION['errores'][] = 'no se ha podido verificar el usuario';
  header('location: 01inicio.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['operacion'] == 'entradas') {

  $precio_final = 0;
  inicio_html('03 entradas', ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);
?>

  <h1>Entradas: TeCobroDeMas</h1>
  <h2>ID: <?= $usuario['id'] ?></h2>
  <h2>Nombre<?= $usuario['nombre'] ?></h2>
  <h4>fecha inicio: <?= $_SESSION['fecha']->format('d-m-Y h:i:s A') ?></h4>
  <?php
  if (!empty($_SESSION['carrito'])) {
  ?>
    <table>
      <thead>
        <tr>
          <th>id</th>
          <th>nombre</th>
          <th>fila</th>
          <th>precio</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($_SESSION['carrito'] as $key => $value) : 
          $precio_final += $value['precio'];
          ?>
          <tr>
            <td><?= $key ?></td>
            <td><?= $value['name'] ?></td>
            <td><?= $value['fila'] ?></td>
            <td><?= $value['precio'] ?>€</td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>

<h4>Precio total: <?= $precio_final ?></h4>

<form action="01inicio.php" method="GET">
  <button type="submit" name="operacion" id="operacion" value="cerrar">tramitar</button>
</form>
<?php
  }
}

fin_html();

?>