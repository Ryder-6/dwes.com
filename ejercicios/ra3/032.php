<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');

inicio_html('03 version 2', ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);
define('TAMANIO_MAXIMO', 1024 * 1024);
define('DESCUENTO_SOCIOS', 0.25);

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

function error($n_error)
{
  $errores = [
    1 => 'Es necesario el nombre',
    2 => 'Es necesaria la dirección',
    3 => 'Es necesario el teléfono',
    4 => 'Tipo de pizza no válido',
    5 => 'Selección de ingredientes no válida',
    6 => 'Error al procesar el fichero de socios',
    7 => 'Fichero demasiado grande o error de subida',
    8 => 'Tipo MIME del fichero no permitido',
    9 => 'Error inesperado en la aplicación'
  ];

  ob_clean();
  echo "<h1>Error en la aplicación</h1>";
  echo "<h2>Código: $n_error</h2>";
  echo "<h3>Mensaje: {$errores[$n_error]}</h3>";
  fin_html();
  exit($n_error);
}


function comprobar_socio($fichero, string $nombre, string $telefono): bool
{
  $tmp_name = $fichero['tmp_name'];
  $error = $fichero['error'];
  $type = $fichero['type'];

  if ($error == UPLOAD_ERR_FORM_SIZE) return false;
  if ($type == 'text/plain' || $type == mime_content_type($tmp_name)) return false;

  if ($error == UPLOAD_ERR_OK) {
    $fichero_abierto = fopen($tmp_name, 'r');

    while (($linea = fgets($fichero_abierto)) == false) {
      list($nombre_fichero, $telefono_fichero) = array_map('trim', explode('|', $linea));

      if (strcasecmp($nombre, $nombre_fichero) == 0 && strcasecmp($telefono, $telefono_fichero) == 0) {
        fclose($tmp_name);
        return true;
      }
    }
  }


  return false;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // 1. sanear
  $sanear = [
    'nombre' => FILTER_SANITIZE_SPECIAL_CHARS,
    'direccion' => FILTER_SANITIZE_SPECIAL_CHARS,
    'telefono' => FILTER_SANITIZE_SPECIAL_CHARS,
    'tipo' => FILTER_SANITIZE_SPECIAL_CHARS,
    'ingredientes_veg' => [
      'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
      'flags' => FILTER_REQUIRE_ARRAY
    ],
    'ingredientes_no_veg' => [
      'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
      'flags' => FILTER_REQUIRE_ARRAY
    ],
    'extra_queso' => FILTER_SANITIZE_SPECIAL_CHARS,
    'bordes' => FILTER_SANITIZE_SPECIAL_CHARS,
    'n_pizzas' => FILTER_SANITIZE_NUMBER_INT,
  ];

  $datos_saneados = filter_input_array(INPUT_POST, $sanear, true);

  // 2. validar
  $datos_validados['nombre'] = $datos_saneados['nombre'] ?: error(1);
  $datos_validados['direccion'] = $datos_saneados['direccion'] ?: error(2);
  $datos_validados['telefono'] = $datos_saneados['telefono'] ?: error(3);
  $datos_validados['tipo'] = filter_var($datos_saneados['tipo'], FILTER_VALIDATE_BOOLEAN) ?: error(4);
  $es_vegetariana = boolval($datos_validados['tipo']);

  if ($es_vegetariana) {
    foreach ($datos_saneados['ingredientes_veg'] as $key) {
      if (array_key_exists($key, $ingredientes_veg)) {
        $datos_validados['ingredientes'][] = $ingredientes_veg[$key];
      }
    }
  } elseif (!$es_vegetariana) {
    foreach ($datos_saneados['ingredientes_veg'] as $key) {
      if (array_key_exists($key, $ingredientes_veg)) {
        $datos_validados['ingredientes'][] = $ingredientes_no_veg[$key];
      }
    }
  } else error(5);

  $datos_validados['extra_queso'] = filter_var($datos_saneados['extra_queso'], FILTER_VALIDATE_BOOLEAN);
  $datos_validados['bordes'] = filter_var($datos_saneados['bordes'], FILTER_VALIDATE_BOOLEAN);
  $datos_validados['n_pizzas'] = filter_var($datos_saneados['bordes'], FILTER_VALIDATE_INT);

  // 3. Fichero
  $es_socio = comprobar_socio($_FILES['fichero'], $datos_validados['nombre'], $datos_validados['telefono']);

  // 4. mostrar
  $precio_final_unidad = $precio_base_pizza + ($es_vegetariana ? $incremento_vegetariana : $incremento_no_vegetariana);

  foreach ($datos_validados['ingredientes'] as $key) {
    $precio_final_unidad += $key['precio'];
  }
  $precio_final_unidad += $datos_validados['extra_queso'] ? $incremento_extra_queso : 0;
  $precio_final_unidad += $datos_validados['bordes'] ? $incremento_bordes : 0;

  
  if ($es_socio) $precio_final_unidad *= DESCUENTO_SOCIOS;

  $precio_final_total = $precio_final_unidad * $datos_validados['n_pizzas'];

  ?>
    <table border="1">
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
    <h3><?= $es_socio ? 'Es socio' : 'no es socio' ?></h3>
  <?php


}

?>

<h2>inicial 5€</h2>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="MAX_FILE_SIZE" value="<?= TAMANIO_MAXIMO ?>">

  <label for="nombre">nombre</label>
  <input type="text" name="nombre" id="nombre">

  <label for="direccion">direccion</label>
  <input type="text" name="direccion" id="direccion">

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

  <label for="fichero">carnet de socio</label>
  <input type="file" name="fichero" id="fichero">

  <button type="submit">Calcular</button>
</form>



<?php
fin_html();

?>