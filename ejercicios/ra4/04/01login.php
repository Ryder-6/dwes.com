<?php

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  # code...
  $operacion = filter_input(INPUT_GET, 'operacion', FILTER_SANITIZE_SPECIAL_CHARS);
  if ($operacion == 'cerrar') {
    $id = session_name();
    $param = session_get_cookie_params();
  
    setcookie($id, '', 0, $param['path'], $param['domain'], $param['secure'], $param['httponly']);
  
    session_destroy();
  
    unset($_SESSION);

    setcookie('jwt', '', 0, '/');
  
    session_start();
  }
}

inicio_html("01 Pantalla inicial", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

if (isset($_SESSION['errores'])) {
?>
  <h1>Errores</h1>
<?php
  foreach ($_SESSION['errores'] as $error) {
    echo "<h4> - $error </h4>";
  }

  unset($_SESSION['errores']);
}




?>
<h1>cochesitos pum pum</h1>
<h2>Login</h2>
<fieldset>
  <legend>datos de login</legend>
  <form action="02inicial.php" method="POST">
    <label for="email">email</label>
    <input type="email" name="email" id="email">

    <label for="pass">password</label>
    <input type="password" name="pass" id="pass">

    <button type="submit" name="operacion" id="operacion" value="login">Iniciar</button>
  </form>
</fieldset>
<?php

fin_html();
?>