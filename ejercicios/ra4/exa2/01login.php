<?php

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $operacion = filter_input(INPUT_GET, 'operacion', FILTER_SANITIZE_SPECIAL_CHARS);
  if ($operacion == 'cerrar') {
    $id = session_name();
    $param = session_get_cookie_params();
    setcookie($id, '', 0, $param['path'], $param['domain'], $param['secure'], $param['httponly']);

    setcookie('jwt', '', 0, '/');
    unset($_SESSION);

    session_destroy();

    session_start();
  }
}

if (isset($_SESSION['errores'])) {
  echo "<h2> Errores</h2>";
  foreach ($_SESSION['errores'] as $error) {
    echo "<h3> $error</h3>";
  }

  unset($_SESSION['errores']);
}

inicio_html('01 login', ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);
?>
<h1>Ultramarinos Guilliman</h1>
<fieldset>
  <legend>login</legend>
  <form action="02autenticar.php" method="POST">
    <label for="email">email</label>
    <input type="email" name="email" id="email">


    <label for="nombre">nombre</label>
    <input type="text" name="nombre" id="nombre">

    <label for="pass">password</label>
    <input type="password" name="pass" id="pass">
    <button type="submit" name="operacion" id="operacion" value="login">Login</button>
  </form>
</fieldset>

<?php
fin_html();
?>