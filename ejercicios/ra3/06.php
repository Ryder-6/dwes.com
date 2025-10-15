<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>06 sticky form</title>
</head>

<body>

  <?php
  $proyectos = [
    'ap' => 'Agua potable',
    'ep' => 'Escuela de primaria',
    'ps' => 'Placas solares',
    'cm' => 'Centro médico'
  ];

  ?>

  <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. sanamiento
    $saneamiento = [
    'email' => FILTER_SANITIZE_EMAIL,
    'autorizo' => FILTER_DEFAULT,
    'proyecto' => FILTER_SANITIZE_SPECIAL_CHARS,
    'propuesta' => FILTER_SANITIZE_SPECIAL_CHARS
    ];

    $datos_saneados = filter_input_array(INPUT_POST, $saneamiento, true);

    // 2. validacion
    if (filter_var($datos_saneados['email'], FILTER_VALIDATE_EMAIL)) {
      $datos_validados['email'] = $datos_saneados['email'];
      $datos_validados['autorizo'] = filter_var($datos_saneados['autorizo'], FILTER_VALIDATE_BOOLEAN); 
      $datos_validados['proyecto'] = array_key_exists($datos_saneados['proyecto'], $proyectos) ? $proyectos[$datos_saneados['proyecto']] : '';
      $datos_validados['propuesta'] = filter_var($datos_saneados['propuesta'], FILTER_SANITIZE_SPECIAL_CHARS);

      // 3. presentacion
      ?>
      <table>
        <thead>
          <tr>
            <th>email</th>
            <th>autorizacion</th>
            <th>proyecto</th>
            <th>propuesta</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?=$datos_validados['email']?></td>
            <td><?=$datos_validados['autorizo'] ? 'Si' : 'No' ?></td>
            <td><?=$datos_validados['proyecto']?></td>
            <td><?=$datos_validados['propuesta']?></td>
          </tr>
        </tbody>
      </table>
      
      
      <?php


    }else{
      echo '<h2>email incorrecto</h2>';
    }
    


  } ?>




  <h1>Propuestas ONG</h1>
  <fieldset>
    <legend>Propuestas</legend>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
      <label for="email">email</label>
      <input type="email" name="email" id="email" <?= isset($_POST['email']) ? $_POST['email'] : '' ?>>

      <label for="autorizo">Autorizo registro</label>
      <input type="checkbox" name="autorizo" id="autorizo" <?= isset($_POST['propuesta']) ? 'checked' : '' ?>>

      <label for="proyecto">Proyecto</label>
      <select name="proyecto" id="proyecto">
        <option value="">Default</option>
        <?php foreach ($proyectos as $key => $value): ?>
          <option value="<?= $key ?>" <?= (isset($_POST['proyecto']) && $_POST['proyecto'] === $key) ? 'select' : '' ?>>
            <?= $value ?>
          </option>
        <?php endforeach; ?>
      </select>
      <br>
      <label for="propuesta">Propuesta</label>
      <br>
      <textarea name="propuesta" id="propuesta" cols="30" rows="10"> <?= isset($_POST['propuesta']) ? $_POST['propuesta'] : '' ?></textarea>

<br>
      <button type="submit">enviar propuesta</button>
    </form>
  </fieldset>

</body>

</html>