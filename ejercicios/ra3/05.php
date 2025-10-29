<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');

inicio_html("06", []);



// persona * dia
$destinos = [
  'pa' => ['name' => 'París', 'precio' => '100'],
  'lo' => ['name' => 'londres', 'precio' => '120'],
  'es' => ['name' => 'Estocolmo', 'precio' => '200'],
  'ed' => ['name' => 'Edinburgo', 'precio' => '175'],
  'pr' => ['name' => 'Praga', 'precio' => '125'],
  'vi' => ['name' => 'Viena', 'precio' => '150'],
];

// * persona
$compania_aerea = [
  'ma' => ['name' => 'MiAir', 'precio' => 0],
  'vc' => ['name' => 'AirFly', 'precio' => 50],
  'af' => ['name' => 'VuelaConmigo', 'precio' => 75],
  'aa' => ['name' => 'ApedalesAir', 'precio' => 150]
];

// * persona * dia
$hoteles = [
  '3s' => 0,
  '4s' => 40,
  '5s' => 100
];

$dias = [5, 10, 15];

//total
$visita_guiada = 200;

// * persona
$desayuno_precio = 20;
$bus_turistico = 30;
$segunda_maleta = 20;
$seguro_viaje = 30;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  //1. saneamiento
  $saneamiento = [
    'nombre' => FILTER_SANITIZE_SPECIAL_CHARS,
    'telefono' => FILTER_SANITIZE_SPECIAL_CHARS,
    'email' => FILTER_SANITIZE_EMAIL,
    'destino' => FILTER_SANITIZE_SPECIAL_CHARS,
    'compania' => FILTER_SANITIZE_SPECIAL_CHARS,
    'hotel' => FILTER_SANITIZE_SPECIAL_CHARS,
    'desayuno' => FILTER_DEFAULT,
    'n_personas' => FILTER_SANITIZE_NUMBER_INT,
    'n_dias' => FILTER_SANITIZE_NUMBER_INT,
    'visita' => FILTER_DEFAULT,
    'bus' => FILTER_DEFAULT,
    'maleta' => FILTER_DEFAULT,
    'seguro' => FILTER_DEFAULT,
  ];

  $datos_saneados = filter_input_array(INPUT_POST, $saneamiento, true);

  //2. validacion
  if ($datos_saneados['nombre'] || $datos_saneados['telefono'] || $datos_saneados['email']) {
    $datos_validados['nombre'] = $datos_saneados['nombre'];
    $datos_validados['telefono'] = $datos_saneados['telefono'];
    $datos_validados['email'] = filter_var($datos_saneados['email'], FILTER_VALIDATE_EMAIL);

    if ($datos_saneados['destino'] && array_key_exists($datos_saneados['destino'], $destinos)) {
      $datos_validados['destino'] = $destinos[$datos_saneados['destino']];
      $datos_validados['compania'] = array_key_exists($datos_saneados['compania'], $compania_aerea) ? $compania_aerea[$datos_saneados['compania']] : $compania_aerea['ma'];
      $datos_validados['hotel'] = array_key_exists($datos_saneados['hotel'], $hoteles) ? $datos_saneados['hotel']  : '3s';

      $datos_validados['desayuno'] = filter_var($datos_saneados['desayuno'], FILTER_VALIDATE_BOOL);

      if (filter_var($datos_saneados['n_personas'], FILTER_VALIDATE_INT) && 5 <= $datos_saneados['n_personas'] && $datos_saneados['n_personas'] <= 10) {
        $datos_validados['n_personas'] = $datos_saneados['n_personas'];
        if (in_array($datos_saneados['n_dias'], $dias)) {
          $datos_validados['n_dias'] = filter_var($datos_saneados['n_dias'], FILTER_VALIDATE_INT);

          $datos_validados['visita'] = filter_var($datos_saneados['visita'], FILTER_VALIDATE_BOOL);
          $datos_validados['bus'] = filter_var($datos_saneados['bus'], FILTER_VALIDATE_BOOL);
          $datos_validados['maleta'] = filter_var($datos_saneados['maleta'], FILTER_VALIDATE_BOOL);
          $datos_validados['seguro'] = filter_var($datos_saneados['seguro'], FILTER_VALIDATE_BOOL);


          //3. presentacion
          $precio_persona_dia =
            $datos_validados['destino']['precio'] +
            $hoteles[$datos_validados['hotel']] +
            $datos_validados['compania']['precio'] +
            ($datos_validados['desayuno'] ? $desayuno_precio : 0) +
            ($datos_validados['bus'] ? $bus_turistico : 0) +
            ($datos_validados['maleta'] ? $segunda_maleta : 0) +
            ($datos_validados['seguro'] ? $seguro_viaje : 0);

          $precio_persona_total = $precio_persona_dia * $datos_validados['n_dias'] + ($datos_validados['visita'] ? ($visita_guiada / $datos_validados['n_personas']) : 0);

          $precio_grupo_dia = $precio_persona_dia * $datos_validados['n_personas'];
          $precio_grupo_total = $precio_persona_total * $datos_validados['n_personas'];


          //en este punto ya funciona, hay que hacer el desglose en una tabla

?>
          <h1>Presupuesto</h1>
          <table border="1">
            <thead>
              <tr>
                <th> nombre </th>
                <th> telefono </th>
                <th> email </th>
                <th> destino </th>
                <th> compania </th>
                <th> hotel </th>
                <th> desayuno </th>
                <th> n_personas </th>
                <th> n_dias </th>
                <th> visita </th>
                <th> bus </th>
                <th> maleta </th>
                <th> seguro </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?= $datos_validados['nombre'] ?></td>
                <td><?= $datos_validados['telefono'] ?></td>
                <td><?= $datos_validados['email'] ?></td>
                <td><?= $datos_validados['destino']['name'] ?> => <?= $datos_validados['destino']['precio'] ?></td>
                <td><?= $datos_validados['compania']['name'] ?> => <?= $datos_validados['compania']['precio'] ?></td>
                <td><?= $datos_validados['hotel'] ?> => <?= $hoteles[$datos_validados['hotel']] ?></td>
                <td><?= $datos_validados['desayuno'] ? "si $desayuno_precio" : 'no' ?></td>
                <td><?= $datos_validados['n_personas'] ?></td>
                <td><?= $datos_validados['n_dias'] ?></td>
                <td><?= $datos_validados['visita'] ? "Si $visita_guiada" : "No" ?></td>
                <td><?= $datos_validados['bus'] ? "Si $bus_turistico" : 'no' ?></td>
                <td><?= $datos_validados['maleta'] ? "Si $segunda_maleta" : "No" ?></td>
                <td><?= $datos_validados['seguro'] ? "Si $seguro_viaje" : "No" ?></td>
              </tr>
            </tbody>
          </table>


  <?php



        } else {
          echo "<h2>Error, Dias elegidos invalido</h2>";
        }
      } else {
        echo "<h2>Error, numero de pasajeros erroneo</h2>";
      }
    } else {
      echo "<h2>Error, falta destino</h2>";
    }
  } else {
    echo "<h2>Error, faltan datos identificativos</h2>";
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  ?>
  <h1>viaje turístico</h1>
  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
    <fieldset>
      <legend>Datos de viaje</legend>

      <label for="nombre">Nombre persona Responsable</label>
      <input type="text" name="nombre" id="nombre">
      <br>
      <label for="telefono">telefono</label>
      <input type="text" name="telefono" id="telefono">
      <br>
      <label for="email">email</label>
      <input type="email" name="email" id="email">
      <br>
      <label for="destino">destino</label>
      <select name="destino" id="destino">
        <option value="">Lista destinos</option>
        <?php foreach ($destinos as $key => $value) : ?>
          <option value="<?= $key ?>"> <?= $value['name'] ?> <?= $value['precio'] ?>€/P/D </option>
        <?php endforeach; ?>
      </select>
      <br>
      <label for="compania">Compañia Aerea</label>
      <select name="compania" id="compania">
        <?php foreach ($compania_aerea as $key => $value) : ?>
          <option value="<?= $key ?>">
            <?= $value['name'] ?> =>
            <?php echo $value['precio'] == 0 ? "Incluido" : $value['precio'] . "€/P/D"  ?>
          </option>
        <?php endforeach; ?>
      </select>
      <br>

      <label for="hotel">hotel</label>
      <select name="hotel" id="hotel">
        <?php foreach ($hoteles as $key => $value) : ?>
          <option value="<?= $key ?>">
            <?= $key ?> =>
            <?php echo $value == 0 ? 'Incluido' : $value . "€/P/D" ?>
          </option>
        <?php endforeach; ?>
      </select>

      <br>
      <label for="desayuno">Desayuno incluido</label>
      <input type="checkbox" name="desayuno" id="desayuno">

      <br>
      <label for="n_personas">numero de personas</label>
      <input type="number" name="n_personas" id="n_personas" min="5" max="10">

      <br>
      <br>
      <fieldset>
        <legend>Numero de dias</legend>
        <div>
          <?php foreach ($dias as $key) : ?>
            <input type="radio" name="n_dias" id="<?= $key ?>" value="<?= $key ?>">
            <label for="n_dias"><?= $key ?></label>
            <br>
          <?php endforeach; ?>
        </div>
      </fieldset>

      <fieldset>
        <legend>extras</legend>
        <input type="checkbox" name="visita" id="visita">
        <label for="visita">Visita Guiada - <?= $visita_guiada ?> €</label>
        <br>

        <input type="checkbox" name="bus" id="bus">
        <label for="bus">Bus turístico - <?= $bus_turistico ?> €/P/D</label>

        <br>
        <input type="checkbox" name="maleta" id="maleta">
        <label for="maleta">2º maleta facturada - <?= $segunda_maleta ?> €/P/D</label>

        <br>
        <input type="checkbox" name="seguro" id="seguro">
        <label for="seguro">seguro de viaje - <?= $seguro_viaje ?> €/P/D</label>

      </fieldset>
    </fieldset>

    <button type="submit">Pedir presupuesto</button>

  </form>


<?php
}



fin_html();

?>