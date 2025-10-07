<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ejercicio 1</title>
</head>


<?php

$isbnRegex = "/^\d{3}-\d-\d{5}-\d{3}-\d$/";


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

$libros = [
  "123-4-56789-012-3" => [
    "autor" => "Ken Follet",
    "titulo" => "Los pilares de la tierra",
    "genero" => "Novela histórica"
  ],
  "987-6-54321-098-7" => [
    "autor" => "Ken Follet",
    "titulo" => "La caída de los gigantes",
    "genero" => "Novela histórica"
  ],
  "345-1-91827-019-4" => [
    "autor" => "Max Hastings",
    "titulo" => "La guerra de Churchill",
    "genero" => "Biografía"
  ],
  "908-2-10928-374-5" => [
    "autor" => "Isaac Asimov",
    "titulo" => "Fundación",
    "genero" => "Fantasía"
  ],
  "657-4-39856-543-3" => [
    "autor" => "Isaac Asimov",
    "titulo" => "Yo, robot",
    "genero" => "Fantasía"
  ],
  "576-4-23442-998-5" => [
    "autor" => "Carl Sagan",
    "titulo" => "Cosmos",
    "genero" => "Divulgación científica"
  ]
];


if ($_SERVER['REQUEST_METHOD'] == "POST") {
  //1. saneamiento
  $saneamiento = [
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

  $datos_saneados = filter_input_array(INPUT_POST, $saneamiento, false);

  //2. validacion
  $isbn_validado = ($datos_saneados['isbn'] != null && preg_match($isbnRegex, $datos_saneados['isbn'])) ? $datos_saneados['isbn'] : '';
  $titulo_validado = $datos_saneados['titulo'] ?? '';

  $autores_validado = [];
  foreach ($datos_saneados['autores'] as $autor) {
    if (array_key_exists($autor, $autores)) {
      $autores_validado[] =  $autores[$autor];
    }
  }

  $generos_validado = [];
  foreach ($datos_saneados['generos'] as $genero) {
    if (array_key_exists($genero, $generos)) {
      $generos_validado[] = $generos[$genero];
    }
  }

  //3. Mostrar resultado

  $resultado_libros = [];
  if (!$isbn_validado && !$titulo_validado && !$autores_validado && !$generos_validado) {
    echo "<h2>No se han encontrado libros</h2>";
  } else {
    //isbn
    if ($isbn_validado && array_key_exists($isbn_validado, $libros)) {
      $resultado_libros[$isbn_validado] = $libros[$isbn_validado];
    }

    //titulo
    if ($titulo_validado) {
      foreach ($libros as $isbn_libro => $valores) {
        if (strcasecmp($valores['titulo'], $titulo_validado) === 0) {
          $resultado_libros[$isbn_libro] = $valores;
        }
      }
    }


    //Generos
    if ($generos_validado) {
      foreach ($libros as $isbn_libro => $valores) {
        if (in_array($valores['genero'], $generos_validado)) {
          $resultado_libros[$isbn_libro] = $valores;
        }
      }
    }


    //Autores
    if ($autores_validado) {
      foreach ($libros as $isbn_libro => $valores) {
        if (in_array($valores['autor'], $autores_validado)) {
          $resultado_libros[$isbn_libro] = $valores;
        }
      }
    }
  }


  // resultado
?>
  <table>
    <thead>
      <tr>
        <th>isbn</th>
        <th>titulo</th>
        <th>genero</th>
        <th>autor</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($resultado_libros as $key => $value) : ?>
        <tr>
          <td><?= $key ?></td>
          <td><?= $value['titulo']?></td>
          <td><?= $value['genero']?></td>
          <td><?= $value['autor']?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php
}


if ($_SERVER['REQUEST_METHOD'] == "GET") {
?>

  <body>
    <fieldset>
      <legend>Ejercicio 1</legend>

      <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
        <label for="isbn">ISBN</label>
        <input type="text" name="isbn" id="isbn">

        <label for="titulo">titulo</label>
        <input type="text" name="titulo" id="titulo">

        <label for="autores">Autor</label>
        <select name="autores[]" id="autores" multiple>
          <?php foreach ($autores as $key => $value) : ?>
            <option value="<?= $key ?>"><?= $value ?></option>
          <?php endforeach; ?>
        </select>

        <label for="generos">generos</label>
        <select name="generos[]" id="generos" multiple>
          <?php foreach ($generos as $key => $value) : ?>
            <option value="<?= $key ?>"> <?= $value ?></option>
          <?php endforeach; ?>
        </select>

        <button type="submit">Buscar</button>
      </form>
    </fieldset>

  <?php
}

  ?>







  </body>

</html>