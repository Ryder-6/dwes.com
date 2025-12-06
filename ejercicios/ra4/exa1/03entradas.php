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
  $_SESSION['errores'][] = 'No se han podido verificar los datos de sesion';
  header('location: 01inicio.php');
  exit();
}

date_default_timezone_set('Europe/Madrid');

inicio_html('03 entradas', ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);
?>

<h1>Entradas: TeCobroDeMas</h1>
<h2>ID: <?= $usuario['id'] ?></h2>
<h2>Nombre<?= $usuario['nombre'] ?></h2>
<h4>fecha inicio: <?= $_SESSION['fecha']->format('d-m-Y h:i:s A') ?></h4>

<fieldset>
  <legend>añadir entradas</legend>
  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
    <label for="entrada">Entrada</label>
    <select name="entrada" id="entrada">
      <?php foreach ($entradas as $key => $value) : ?>
        <option value="<?= $key ?>"> <?= $value['name'] ?> </option>
      <?php endforeach; ?>
    </select>

    <label for="fila">Fila</label>
    <input type="number" name="fila" id="fila">

    <button type="submit" name="operacion" id="operacion" value="anadir">añadir entrada</button>
  </form>
</fieldset>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['operacion'] == 'anadir') {
  // 1. sanear
  $entrada = filter_input(INPUT_POST, 'entrada', FILTER_SANITIZE_SPECIAL_CHARS);
  $fila = filter_input(INPUT_POST, 'fila', FILTER_SANITIZE_NUMBER_INT);

  // 2. validar
  $entrada_id = $entrada;
  $entrada = array_key_exists($entrada, $entradas) ? $entradas[$entrada] : false;
  $fila = filter_var($fila, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 20]]);

  if ($entrada && $fila) {
    // 3. añadir 
    $precio = $fila <= 10 ? $entrada['fila1_10'] :  $entrada['fila11_20'];
    $_SESSION['carrito'][$entrada_id] = [
      'name' => $entrada['name'],
      'fila' => $fila,
      'precio' => $precio
    ];
  }

  // 4. mostrar
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
        <?php foreach ($_SESSION['carrito'] as $key => $value) :  ?>
          <tr>
            <td><?= $key ?></td>
            <td><?= $value['name'] ?></td>
            <td><?= $value['fila'] ?></td>
            <td><?= $value['precio']?>€</td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>


<?php
  }
}

?>
<form action="04final.php" method="POST">
  <button type="submit" name="operacion" id="operacion" value="entradas">tramitar compra</button>
</form>

<?php



fin_html()
?>