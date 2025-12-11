<?php

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

$usuario = comprobarJWT();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['operacion'] == 'anadir') {
  $espectaculo = filter_input(INPUT_POST, 'espectaculo', FILTER_SANITIZE_SPECIAL_CHARS);
  $fila = filter_input(INPUT_POST, 'fila', FILTER_SANITIZE_NUMBER_INT);
  $fila = filter_var($fila, FILTER_VALIDATE_INT, ['options' => [
    'max_range' => 20,
    'min_range' => 1
  ]]);

  if (!array_key_exists($espectaculo, $conciertos)) $_SESSION['errores'][] = 'no se ha verificado el concierto';
  if (!$fila) $_SESSION['errores'][] = 'Fila invalida';

  if (isset($_SESSION['errores'])) {
    header('location: 01login.php');
    exit();
  }else{
    $_SESSION['carrito'][$espectaculo] = [
      'name' => $conciertos[$espectaculo]['name'],
      'fila' => $fila,
      'precio' => ($fila <= 10) ? $conciertos[$espectaculo]['f1_10'] : $conciertos[$espectaculo]['f11_20'],
    ];
  
  }

}




inicio_html('03 entradas', ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);
?>
<h1>Conciertos TeCobroDeMas</h1>

<h2><?= $usuario['name'] ?></h2>
<h2><?= $usuario['id'] ?></h2>
<h3><?= $_SESSION['fecha']->format('d-m-Y h:m') ?></h3>
<fieldset>
  <legend>login</legend>
  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
    <label for="espectaculo">espectaculo</label>
    <select name="espectaculo" id="espectaculo">
      <?php foreach ($conciertos as $key => $value) : ?>
        <option value="<?= $key ?>"><?= $value['name'] ?></option>
      <?php endforeach ?>
    </select>

    <label for="fila">fila</label>
    <input type="number" name="fila" id="fila">

    <button type="submit" name="operacion" id="operacion" value="anadir">Añadir entrada</button>
  </form>

</fieldset>
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
    if (!empty($_SESSION['carrito'])) {
      foreach ($_SESSION['carrito'] as $key => $value) : ?>
        <tr>
          <td><?= $value['name'] ?></td>
          <td><?= $value['fila'] ?></td>
          <td><?= $value['precio'] ?></td>
        </tr>
    <?php endforeach;
    }
    ?>

  </tbody>
</table>
<form action="04final.php" method="POST">
  <button type="submit" name="operacion" id="operacion" value="entradas">Tramitar</button>
</form>
<?php
fin_html();
?>