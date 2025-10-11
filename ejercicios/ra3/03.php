<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pizzas</title>
</head>

<body>

  <?php
  $precio_base_pizza = 5;
  $incremento_vegetariana = 3;
  $incremento_no_vegetariana = 3;

  $ingredientes_veg = [
    'pe' => ['name' => 'Pepino', "precio" => 1],
    'ca' => ['name' => 'Calabacín', "precio" => 1.5],
    'pv' => ['name' => 'Pimiento verde', "precio" => 1.25],
    'pr' => ['name' => 'Pimiento rojo', "precio" => 1.75],
    'to' => ['name' => 'Tomate', "precio" => 1.5],
    'ac' => ['name' => 'Aceitunas', "precio" => 3],
    'ce' => ['name' => 'Cebolla', "precio" => 1],
  ];

  $ingredientes_no_veg = [
    'at' => ['name' => 'Atún', 'precio' => 2],
    'cp' => ['name' => 'Carne picada', 'precio' => 2.5],
    'pp' => ['name' => 'Peperoni', 'precio' => 1.75],
    'mo' => ['name' => 'Morcilla', 'precio' => 2.25],
    'an' => ['name' => 'Anchoas', 'precio' => 1.5],
    'sa' => ['name' => 'Salmón', 'precio' => 3],
    'ga' => ['name' => 'Gambas', 'precio' => 4],
    'la' => ['name' => 'Langostinos', 'precio' => 4],
    'me' => ['name' => 'Mejillones', 'precio' => 2],
  ];

  $es_vegetariana = false;
  $ingredientes_escogidos = []

  ?>


<?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  # code...
}?>

<h1>Pizzas</h1>

<?php if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  
?>
  <fieldset>
    <legend>Ingredientes de la pizza</legend>

    <h2>inicial 5€</h2>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">

      <label for="nombre">nombre</label>
      <input type="text" name="nombre" id="nombre">

      <label for="direccion">direccion</label>
      <input type="text" name="direccion" id="direccion">

      <div>
        <label for="vegetariana">vegetariana</label>
        <input type="radio" id="vegetariana" name="tipo" value="1" <?= $es_vegetariana ? 'checked' : '' ?> />

        <label for="no_vegetariana">NO vegetariana</label>
        <input type="radio" id="no_vegetariana" name="tipo" value="0" <?= !$es_vegetariana ? 'checked' : '' ?> />

      </div>

      <div>
        <select name="ingredientes_veg[]" id="ingredientes_veg" multiple <?= !$es_vegetariana ? 'disabled' : '' ?>>
          <?php foreach ($ingredientes_veg as $key => $value): ?>
            <option value="<?= $key ?>">
              <?= $value['name'] ?> => <?= $value['precio'] ?>
            </option>
          <?php endforeach; ?>
        </select>

        <select name="ingredientes_no_veg[]" id="ingredientes_no_veg" multiple <?= $es_vegetariana ? 'disabled' : '' ?>>
          <?php foreach ($ingredientes_no_veg as $key => $value): ?>
            <option value="<?= $key ?>">
              <?= $value['name'] ?> => <?= $value['precio'] ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
          

      <label for="extra_queso">extra queso</label>
      <input type="checkbox" name="extra_queso" id="extra_queso">

      <label for="bordes">bordes rellenos</label>
      <input type="checkbox" name="bordes" id="bordes">
      <button type="submit">Calcular</button>
    </form>


  </fieldset>
<?php } ?>

</body>

</html>