<?php


session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');


// LIMPIAR SESION
$operacion = filter_input(INPUT_GET, 'operacion', FILTER_SANITIZE_SPECIAL_CHARS);
if ($operacion == 'cerrar') {
  $id = session_name();
  $param = session_get_cookie_params();
  setcookie($id, '', 0, $param['path'], $param['domain'], $param['secure'], $param['httponly']);

  session_destroy();
  unset($_SESSION);

  session_start();
}

// MOSTRAR ERRORES y limpiar
if (isset($_SESSION['errores'])) {
?>
  <h1>Errores:</h1>
  <?php foreach ($_SESSION['errores'] as $error): ?>
     <h3>- <?= $error ?></h3>
  <?php endforeach; ?>
<?php

  unset($_SESSION['errores']);
}



inicio_html("01 Pantalla inicial", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

?>
<h1>La Michipizzeria</h1>

<fieldset>
  <legend>login de usuario</legend>
  <form action="02autenticar.php" method="POST">
    <label for="email">email</label>
    <input type="email" name="email" id="email">

    <label for="pass">password</label>
    <input type="password" name="pass" id="pass">

    <button type="submit" name="operacion" id="operacion" value="login">Iniciar sesion</button>
  </form>
</fieldset>

<?php



fin_html();

?>