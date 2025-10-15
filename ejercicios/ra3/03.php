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
  $incremento_vegetariana = 2;
  $incremento_no_vegetariana = 3;
  $incremento_bordes = 1.5;
  $incremento_extra_queso = 2.5;


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

  ?>


  <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //1. saneamiento 
    $saneamiento = [
      'nombre' => FILTER_SANITIZE_SPECIAL_CHARS,
      'direccion' => FILTER_SANITIZE_SPECIAL_CHARS,
      'telefono' => FILTER_SANITIZE_NUMBER_INT, 
      'tipo' => FILTER_SANITIZE_NUMBER_INT, // 0-NO es vegetariana | 1-ESvegetariana
      'ingredientes_veg' => [
        'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
        'flags' => FILTER_REQUIRE_ARRAY
      ],
      'ingredientes_no_veg' => [
        'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
        'flags' => FILTER_REQUIRE_ARRAY
      ],
      'extra_queso' => FILTER_DEFAULT,
      'bordes' => FILTER_DEFAULT,
      'n_pizzas' => FILTER_SANITIZE_NUMBER_INT

    ];

    $datos_saneados = filter_input_array(INPUT_POST, $saneamiento, true);
    
    
    //2. validacion 
      //Notas: lo suyo seria saltar excepciones si falta nombre, direccion o telefono

    $datos_validados['nombre'] = $datos_saneados['nombre'] ? $datos_saneados['nombre'] : '';
    $datos_validados['direccion'] = $datos_saneados['direccion'] ? $datos_saneados['direccion'] : '';
    $datos_validados['telefono'] = is_int($datos_saneados['telefono']) ? $datos_saneados['telefono'] : '';

    $datos_validados['tipo'] = $datos_saneados['tipo'];
    $es_vegetariana = filter_var($datos_validados['tipo'],FILTER_VALIDATE_BOOLEAN);

    if ($es_vegetariana && !empty($datos_saneados['ingredientes_veg'])) {
      foreach ($datos_saneados['ingredientes_veg'] as $ingrediente) {
        if (array_key_exists($ingrediente, $ingredientes_veg)) {
          $datos_validados['ingredientes'][] = $ingredientes_veg[$ingrediente];
        }
      }
    } elseif (!$es_vegetariana && !empty($datos_saneados['ingredientes_no_veg'])) {
      foreach ($datos_saneados['ingredientes_no_veg'] as $ingrediente) {
        if (array_key_exists($ingrediente, $ingredientes_no_veg)) {
          $datos_validados['ingredientes'][] = $ingredientes_no_veg[$ingrediente];
        }
      }
    }else{
    $datos_validados['ingredientes'][] = 'Ninguno';
    }

    $datos_validados['extra_queso'] = boolval($datos_saneados['extra_queso']);
    $datos_validados['bordes'] = boolval($datos_saneados['bordes']);

    if ($datos_saneados['n_pizzas'] >= 1 && $datos_saneados['n_pizzas'] <= 5) {
      $datos_validados['n_pizzas'] = $datos_saneados['n_pizzas'];
    }else {
      $datos_validados['n_pizzas'] = 1;
    
    }


    //3. resultado

    $precio_final_unidad = $precio_base_pizza + ($es_vegetariana ? $incremento_vegetariana : $incremento_no_vegetariana);

    foreach ($datos_validados['ingredientes'] as $key => $value) {
      $precio_final_unidad += $value['precio'];
    }
    $precio_final_unidad += $datos_validados['extra_queso'] ? $incremento_extra_queso : 0;
    $precio_final_unidad += $datos_validados['bordes'] ? $incremento_bordes : 0;

    $precio_final_total = $precio_final_unidad * $datos_validados['n_pizzas'];

  ?>
    <table>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Direccion</th>
          <th>Telefono</th>
          <th>Tipo</th>
          <th>Ingredientes</th>
          <th>Extra Queso</th>
          <th>Bordes rellenos</th>
          <th>Nº Pizzas</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?=$datos_validados['nombre']?></td>
          <td><?=$datos_validados['direccion']?></td>
          <td><?=$datos_validados['telefono']?></td>
          <td><?=$datos_validados['tipo'] ? 'Vegetariana' : 'No Vegetariana' ?></td>
          <td>
            <?php foreach ($datos_validados['ingredientes'] as $key => $value): ?>
              - <?=$value['name']?> => <?=$value['precio']?> <br>
            <?php endforeach; ?>

          </td>
          <td><?=$datos_validados['extra_queso'] ? 'Si' : 'No'?></td>
          <td><?=$datos_validados['bordes'] ? 'Si' : 'No'?></td>
          <td><?=$datos_validados['n_pizzas']?></td>
        </tr>

      </tbody>
    </table>
    <h1>Precio unidad: <?=$precio_final_unidad?></h1>
    <h1>Precio total: <?=$precio_final_total?></h1>
  <?php




  } ?>


  <?php if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  ?>
    <h1>Pizzas</h1>
    <fieldset>
      <legend>Ingredientes de la pizza</legend>

      <h2>inicial 5€</h2>
      <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">

        <label for="nombre">nombre</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="direccion">direccion</label>
        <input type="text" name="direccion" id="direccion" required>

        <label for="telefono">Telefono</label>
        <input type="text" name="telefono" id="telefono">

        <div>
          <label for="vegetariana">vegetariana</label>
          <input type="radio" id="vegetariana" name="tipo" value="1" <?= $es_vegetariana ? 'checked' : '' ?> />

          <label for="no_vegetariana">NO vegetariana</label>
          <input type="radio" id="no_vegetariana" name="tipo" value="0" <?= !$es_vegetariana ? 'checked' : '' ?> />

        </div>

        <div>
          <select name="ingredientes_veg[]" id="ingredientes_veg" multiple>
            <?php foreach ($ingredientes_veg as $key => $value): ?>
              <option value="<?= $key ?>">
                <?= $value['name'] ?> => <?= $value['precio'] ?>
              </option>
            <?php endforeach; ?>
          </select>

          <select name="ingredientes_no_veg[]" id="ingredientes_no_veg" multiple>
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

        <label for="n_pizzas">Numero de pizzas</label>
        <input type="number" name="n_pizzas" id="n_pizzas" min=1 max=5 default=1>

        <button type="submit">Calcular</button>
      </form>


    </fieldset>
  <?php } ?>

</body>

</html>