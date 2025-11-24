<?php // JAVIER RIDER JIMENEZ Nº13

require_once($_SERVER['DOCUMENT_ROOT'] . '/exra2313/includes/funciones.php');

inicioHtml("examen 23 13", ['/exra2313/estilos/formulario.css', '/exra2313/estilos/general.css', '/exra2313/estilos/tabla.css']);


$cursos = [
  'of' => ['name' => 'Ofimatica', 'precio' => 100],
  'pr' => ['name' => 'Programacion', 'precio' => 200],
  'ro' => ['name' => 'Reparacion de ordenadores', 'precio' => 150],
];

function error($n_error)
{
  $errores = [
    1 => 'Email invalido',
    2 => 'Clases invalidas',
    3 => 'numero de clases invalida',
    4 => 'Fichero no subido',
    5 => 'Tamaño fichero invalido',
    6 => 'Tipo de fichero no admitido',
    7 => 'Fallo creando directorio',
    8 => 'Fallo guardando fichero',
    9 => 'Fallo inesperado en el fichero'
  ];

  echo "<h2>error $n_error: $errores[$n_error] </h2>";
?>
  <a href="<?= $_SERVER['PHP_SELF'] ?>"> volver a intentarlo</a>
  <?php

  finHtml();
  exit($n_error);
}


define('TAMANIO_MAXIMO', 100 * 1024);
$TIPOS_ACEPTADOS = ['application/pdf'];
define('DIRECTORIO_EXAMEN', $_SERVER['DOCUMENT_ROOT'] . '/exra2313/tarjetas');

function guarda_fichero($fichero, array $tipos_aceptados, string $nombre)
{
  $tmp_name = $fichero['tmp_name'];
  $type = $fichero['type'];
  $error = $fichero['error'];
  $name = $fichero['name'];
  $size = $fichero['size'];

  if ($error == UPLOAD_ERR_NO_FILE) error(4);
  if ($error == UPLOAD_ERR_FORM_SIZE) error(5);
  if (!in_array($type, $tipos_aceptados) || !$type == mime_content_type($tmp_name)) error(6);

  if ($error == UPLOAD_ERR_OK) {
    if (!is_dir(DIRECTORIO_EXAMEN)) {
      if (!mkdir(DIRECTORIO_EXAMEN, 0755, true)) {
        error(7);
      }
    }

    $extension = explode('/', $type)[1];
    if (!move_uploaded_file($tmp_name, DIRECTORIO_EXAMEN . "/$nombre.$extension")) {
      error(8);
    } else return [
      'n_original' => $name,
      'n_guardado' => "$nombre.$extension",
      'tamanio' => $size
    ];
  } else error(9);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // 1. sanear

  $sanear = [
    'email' => FILTER_SANITIZE_SPECIAL_CHARS,
    'cursos' => [
      'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
      'flags' => FILTER_REQUIRE_ARRAY,
    ],
    'n_clases' => FILTER_SANITIZE_NUMBER_INT,
    'desempleo' => FILTER_SANITIZE_SPECIAL_CHARS
  ];

  $datos_saneados = filter_input_array(INPUT_POST, $sanear, true);

  // 2. validar

  $datos_validados['email'] = filter_var($datos_saneados['email'], FILTER_VALIDATE_EMAIL) ?: error(1);
  if (!empty($datos_saneados['cursos']) && is_array($datos_saneados['cursos'])) {
    $datos_validados['cursos'] = [];
    foreach ($datos_saneados['cursos'] as $key) {
      if (array_key_exists($key, $cursos)) {
        $datos_validados['cursos'][] = $cursos[$key];
      }
    }

    if (empty($datos_validados['cursos'])) {
      error(2);
    }
  } else error(2);

  $options_n_clases  = [
    'options' => [
      'min_range' => 5,
      'max_range' => 10,
    ]
  ];
  $datos_validados['n_clases'] = filter_var($datos_saneados['n_clases'], FILTER_VALIDATE_INT, $options_n_clases) ?: error(3);

  $datos_validados['desempleo'] = filter_var($datos_saneados['desempleo'], FILTER_VALIDATE_BOOLEAN);

  // 3. fichero
  if ($datos_validados['desempleo']) {
    $datos_fichero = guarda_fichero($_FILES['fichero'], $TIPOS_ACEPTADOS, $datos_validados['email']);
  
    echo "<h3>Fichero guardado </h3>";
  
  } else {
    echo "<h3>No Desempleado, no fichero </h3>";
  
  }

  // 4. mostrar

  $importe = 0;
  foreach ($datos_validados['cursos'] as $key => $value) {
    $importe += $value['precio'];
  }
  $importe_clases = $importe + (10 * $datos_validados['n_clases']);

  $importe_clases_descuento =  ($datos_validados['desempleo']) ? $importe_clases * 0.90 : $importe_clases;

  ?>

  <h1>Presupuesto</h1>
  <table>
    <thead>
      <tr>
        <th>email</th>
        <th>Cursos</th>
        <th>Nº Clases</th>
        <th>Desempleo</th>
        <th>datos_fichero</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?= $datos_validados['email'] ?></td>
        <td>
          <?php foreach ($datos_validados['cursos'] as $key => $value) : ?>
            - <?= $value['name'] ?> => <?= $value['precio'] ?>€ <br>
          <?php endforeach; ?>
        </td>
        <td><?= $datos_validados['n_clases'] ?></td>
        <td><?= $datos_validados['desempleo'] ? 'Si -10%' : 'No' ?></td>
        <td>
          <?php
          if (isset($datos_fichero)) {
            foreach ($datos_fichero as $key => $value) {
              echo " - $key => $value <br>";
            }
          }
          ?>
        </td>
      </tr>
    </tbody>
  </table>

  <h2>Importe base: <?= $importe ?></h2>
  <h2>Importe con clases: <?= $importe_clases ?></h2>
  <?php 
  if ($datos_validados['desempleo']) {
    # code...
    echo "<h2>Importe con descuento: $importe_clases_descuento </h2>";
  }
  
  ?>


  <a href="<?= $_SERVER['PHP_SELF'] ?>"> volver a intentarlo</a>
<?php


}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

  <h1>examen </h1>
  <fieldset>
    <legend>formulario</legend>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="MAX_FILE_SIZE" value="<?= TAMANIO_MAXIMO ?>">

      <label for="email">email</label>
      <input type="email" name="email" id="email" require>

      <label for="cursos"> cursos</label>
      <select name="cursos[]" id="cursos" multiple require>
        <?php foreach ($cursos as $key => $value) : ?>
          <option value="<?= $key ?>"> <?= $value['name'] ?> => <?= $value['precio'] ?>€</option>
        <?php endforeach; ?>
      </select>

      <label for="n_clases">Numero de clases</label>
      <input type="number" name="n_clases" id="n_clases" min="5" max="10" require>

      <label for="desempleo">situacion de desempleo</label>
      <input type="checkbox" name="desempleo" id="desempleo">

      <label for="fichero">Tarjeta demandante de empleo</label>
      <input type="file" name="fichero" id="fichero" accept="application/pdf">

      <button type="submit">Enviar solicitud</button>
    </form>
  </fieldset>

<?php

}

finHtml();

?>