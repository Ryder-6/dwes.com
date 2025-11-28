<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');

inicio_html("03 Pantalla inicial", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

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

if ($_SERVER['DOCUMENT_ROOT'] == 'POST' && $_COOKIE['tipo'] == 0 || $_COOKIE['tipo'] == 1 && isset($_POST['ingredientes'])) {

  $ingredientes_saneados = filter_input(INPUT_POST, 'ingredientes', FILTER_SANITIZE_SPECIAL_CHARS, ['flags' => FILTER_REQUIRE_ARRAY]);
  
  $ingredientes_validados = [];
  foreach ($ingredientes as $key) {
    if ($_COOKIE['tipo'] == 1) {
      if (array_key_exists($key, $ingredientes_no_veg)) {
        $ingredientes_validados[] = $ingredientes_no_veg['key'];
      }
    }
    if ($_COOKIE['tipo'] == 0) {
      if (array_key_exists($key, $ingredientes_veg)) {
        $ingredientes_validados[] = $ingredientes_veg['key'];
      }
    }
    
  }



} else {
?>
  <a href="pantalla_inicial.php"> repetir eleccion</a>
<?php
}

fin_html();

?>