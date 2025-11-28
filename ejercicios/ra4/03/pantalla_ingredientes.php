<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');

inicio_html("03 Pantalla ingredientes", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);


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


if ($_SERVER['DOCUMENT_ROOT'] == 'POST' && $_POST['tipo'] == 0 || $_POST['tipo'] == 1) {
  $tipo = filter_var($_POST['tipo'], FILTER_VALIDATE_BOOLEAN);

  if (isset($_COOKIE['tipo'])) setcookie('tipo', '', 0, '/');
  setcookie('tipo', $tipo, time() + 3600, '/');

?>
  <h1>La michipizzeria</h1>
  <h2>Ingredientes deseados</h2>

  <fieldset>
    <legend>ingredientes <?= $tipo == 1 ? 'NO vegetarianos' : 'Vegetarianos' ?></legend>
    <form action="pantalla_extras.php" method="POST">
      <label for="ingredientes">Ingredientes</label>
      <select name="ingredientes[]" id="ingredientes" multiple>
        <?php foreach (($tipo ? $ingredientes_no_veg : $ingredientes_veg)  as $key => $value) : ?>
          <option value="<?= $key ?>"><?= $value['name'] ?> => <?= $value['precio'] ?>€</option>
        <?php endforeach; ?>
      </select>

      <button type="submit">Añadir extras</button>
    </form>
  </fieldset>


<?php

} else {
?>
  <a href="pantalla_inicial.php"> repetir eleccion</a>
<?php
}

fin_html();

?>