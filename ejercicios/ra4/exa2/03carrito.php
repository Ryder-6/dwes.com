<?php


session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

$usuario = comprobarJWT();



// 1. sanear validar

$producto_key = filter_input(INPUT_POST, 'producto', FILTER_SANITIZE_SPECIAL_CHARS);
$cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_SANITIZE_NUMBER_INT);
$cantidad = filter_var($cantidad, FILTER_VALIDATE_INT, ['options' => [
  'min_range' => 1,
  'max_range' => 99
  ]]);

if (array_key_exists($producto_key, $productos) && $cantidad) {
  $producto = $productos[$producto_key];
  echo "producto añadido: {$producto['name']} ";

  $_SESSION['carrito'][$producto_key] = [
    'name' => $producto['name'],
    'precio' => $producto['precio'],
    'cantidad' => $cantidad
  ];

}



inicio_html('01 login', ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);
?>
<h1>Ultramarinos Guilliman</h1>
<h2>nombre <?= $usuario['name'] ?></h2>
<h3>email <?= $usuario['email'] ?></h3>
<h4>fecha <?= $_SESSION['fecha']->format('d-m-Y h:m') ?></h4>
<fieldset>
  <legend>login</legend>
  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
    <label for="producto">Productos:</label>
    <select name="producto" id="producto">
      <?php foreach ($productos as $key => $value) : ?>
        <option value="<?= $key ?>"><?= $value['name'] ?> => <?= $value['precio'] ?></option>
      <?php endforeach ?>
    </select>
    <label for="cantidad">cantidad</label>
    <input type="number" name="cantidad" id="cantidad">

    <button type="submit" name="operacion" id="operacion" value="anadir">Añadir</button>
  </form>
</fieldset>

<form action="04final.php" method="post">
<button type="submit" name="operacion" id="operacion" value="carrito">Pagar</button>
</form>
<?php
fin_html();
?>