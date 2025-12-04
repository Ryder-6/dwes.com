<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $operacion = filter_input(INPUT_GET, 'operacion', FILTER_SANITIZE_SPECIAL_CHARS);
  if ($operacion == 'cerrar') {
    $id = session_name();
    $param = session_get_cookie_params();

    setcookie($id, '', 0, $param['path'], $param['domain'], $param['secure'], $param['httponly']);
    setcookie('jwt', '', 0, '/');
    unset($_SESSION);

    session_destroy();
    session_start();
  }
}

if (isset($_SESSION['errores'])) {
  echo "<h2>Errores encontrados</h2>";
  foreach ($_SESSION['errores'] as $error) {
    echo "<h3>- Error: $error </h3>";
  }
  unset($_SESSION['errores']);
}

// validar-sanear
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['operacion'] == 'anadir') {
  $articulo = filter_input(INPUT_POST, 'articulo', FILTER_SANITIZE_SPECIAL_CHARS);
  $cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_SANITIZE_NUMBER_INT);
  $cantidad = filter_var($cantidad, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'default' => 1]]);

  if (array_key_exists($articulo, $productos)) {
    $_SESSION['carrito'][$articulo] = [
      'name' => $productos[$articulo]['name'],
      'precio' => $productos[$articulo]['precio'],
      'cantidad' => $cantidad
    ];
  }
}


// formulario
inicio_html("01 Pantalla carrito", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);
?>

<h1>Ultramarinos Guilliman</h1>

<fieldset>
  <legend>articulos disponibles</legend>
  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
    <label for="articulo">Articulos:</label>
    <select name="articulo" id="articulo">
      <?php foreach ($productos as $key => $value) : ?>
        <option value="<?= $key ?>"> <?= $value['name'] ?> => <?= $value['precio'] ?>€</option>
      <?php endforeach ?>
    </select>

    <label for="cantidad">cantidad</label>
    <input type="number" name="cantidad" id="cantidad">

    <button type="submit" name="operacion" id="operacion" value="anadir">Añadir mas</button>
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

<form action="02login.php" method="post">
  <button type="submit" name="operacion" id="operacion" value="carrito"> iniciar session</button>
</form>

<form action="03pago.php" method="post">
  <button type="submit" name="operacion" id="operacion" value="carrito"> Pagar</button>
</form>



<?php
?>