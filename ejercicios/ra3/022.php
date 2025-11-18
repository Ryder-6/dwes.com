<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');

inicio_html('08', ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);
define('TAMANIO_MAXIMO', 1024 * 1024);

function error($n_error)
{
  $errores = [
    1 => 'error de tamaño de fichero csv',
    2 => 'error de tipo mime, deve ser csv',
    3 => 'error en la lectura del archivo csv',
  ];

  echo "<h2>Error $n_error: $errores[$n_error]</h2>";
  fin_html();
  exit($n_error);
}

function abrir_fichero_csv($fichero)
{
  $tmp_name = $fichero['tmp_name'];
  $size = $fichero['size'];
  $type = $fichero['type'];
  $error = $fichero['error'];

  if ($error == UPLOAD_ERR_FORM_SIZE) error(1);
  if (
    !$type == "text/csv" ||
    !$type == mime_content_type($tmp_name)
  ) {
    error(2);
  }
  if ($error == UPLOAD_ERR_OK) {

    if (($fichero_abierto = fopen($tmp_name, 'r')) != false) {
      $cabecera = fgetcsv($fichero_abierto);

      $libros = [];
      while (($fila = fgetcsv($fichero_abierto)) != false) {
        if (count($fila) < 4) continue;

        list($isbn, $autor, $titulo, $genero) = $fila;

        $libros[$isbn] = [
          'titulo' => $titulo,
          'autor' => $autor,
          'genero' => $genero
        ];
      }
      fclose($fichero_abierto);
      return $libros;
    }
  }
  error(3);
}




$autores = [
  'kf' =>  'Ken Follet',
  'mh' =>  'Max Hastings',
  'ia' =>  'Isaac Asimov',
  'cs' =>  'Carl Sagan',
  'sj' =>  'Steve Jacobson',
  'gr' =>  'George R.R. Martin'
];

$generos = [
  'nh' => 'Novela histórica',
  'dc' => 'Divulgación científica',
  'b' => 'Biografía',
  'f' => 'Fantasía'
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // 1. sanear
  $sanear = [
    'isbn' => FILTER_SANITIZE_SPECIAL_CHARS,
    'titulo' => FILTER_SANITIZE_SPECIAL_CHARS,
    'autores' => [
      'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
      'flags' => FILTER_REQUIRE_ARRAY
    ],
    'generos' => [
      'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
      'flags' => FILTER_REQUIRE_ARRAY
    ]
  ];

  $datos_saneados = filter_input_array(INPUT_POST, $sanear, true);

  // 2. validar
  $regex_isbn = "/^[0-9]{3}-[0-9]{1}-[0-9]{5}-[0-9]{3}-[0-9]{1}$/";
  $datos_validados['isbn'] = preg_match($regex_isbn, $datos_saneados['isbn']) ? $datos_saneados['isbn'] : '';

  $datos_validados['titulo'] = isset($datos_saneados['titulo']) ?  $datos_saneados['titulo'] : '';


  $datos_validados['autores'] = [];
  if (isset($datos_saneados['autores'])) {
    foreach ($datos_saneados['autores'] as $key) {
      if (array_key_exists($key, $autores)) $datos_validados['autores'][] = $autores[$key];
    }
  }


  $datos_validados['generos'] = [];
  if (isset($datos_saneados['generos'])) {
    foreach ($datos_saneados['generos'] as $key) {
      if (array_key_exists($key, $generos)) $datos_validados['generos'][] = $generos[$key];
    }
  }

  // 3. fichero
  $libros = abrir_fichero_csv($_FILES['fichero']);

  // 4. presentar 

  $libros_encontrados = [];

  if (isset($datos_validados['isbn'])) {
    array_key_exists($datos_validados['isbn'], $libros) ? $libros_encontrados[] = $libros[$datos_validados['isbn']] : '';
  }
  if (isset($datos_validados['titulo'])) {
    foreach ($libros as $key => $value) {
      if ($value['titulo'] == $datos_validados['titulo']) $libros_encontrados[] = $libros[$key];
    }
  }
  if (isset($datos_validados['autores'])) {
    foreach ($libros as $key => $value) {
      foreach ($datos_validados['autores'] as $autor) {
        if (strcasecmp($value['autor'], $autor) == 0) {
          $libros_encontrados[$key] = $value;
        }
      }
    }
  }
  if (isset($datos_validados['generos'])) {
    foreach ($libros as $key => $value) {
      foreach ($datos_validados['generos'] as $genero) {
        if (strcasecmp($value['genero'], $genero) == 0) {
          $libros_encontrados[$key] = $value;
          
        }
      }

    }
  }

?>

  <table>
    <thead>
      <tr>
        <th>isbn</th>
        <th>titulo</th>
        <th>autor</th>
        <th>genero</th>
      </tr>
    </thead>
    <tbody>

      <?php foreach ($libros_encontrados as $key => $value) : ?>
        <tr>
          <td> <?= $key ?></td>
          <td> <?= $value['titulo'] ?></td>
          <td> <?= $value['autor'] ?></td>
          <td> <?= $value['genero'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>

  </table>


<?php

}

?>
<h2>Busqueda de libros</h2>

<fieldset>
  <legend>Parametros de busqueda</legend>

  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?= TAMANIO_MAXIMO ?>">

    <label for="isbn">ISBN</label>
    <input type="text" name="isbn" id="isbn">

    <label for="titulo">titulo</label>
    <input type="text" name="titulo" id="titulo">

    <label for="autores">Autores</label>
    <select name="autores[]" id="autores" multiple>
      <?php foreach ($autores as $key => $value) : ?>
        <option value="<?= $key ?>"> <?= $value ?> </option>
      <?php endforeach; ?>
    </select>

    <label for="generos">Generos</label>
    <select name="generos[]" id="generos" multiple>
      <?php foreach ($generos as $key => $value) : ?>
        <option value="<?= $key ?>"> <?= $value ?></option>
      <?php endforeach; ?>
    </select>

    <label for="fichero">busacar en:</label>
    <input type="file" name="fichero" id="fichero" accept="text/csv">

    <button type="submit">buscar</button>
  </form>
</fieldset>

<?php



fin_html();
?>