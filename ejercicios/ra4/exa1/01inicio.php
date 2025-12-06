<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');

// 1. cerrar session

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

// 2. listar errores

if (isset($_SESSION['errores'])) {
  echo "<h2>Errores </h2>";
  foreach ($_SESSION['errores'] as $error) {
  echo "<h3>$error </h3>";    
  }
  unset($_SESSION['errores']);
}

inicio_html('01 login', ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);
?>
<h1>Entradas: TeCobroDeMas</h1>
<fieldset>
  <legend>Login</legend>
  <form action="02autenticar.php" method="POST">
    <label for="id">Identificador</label>
    <input type="text" name="id" id="id">
    
    <label for="nombre">Nombre completo</label>
    <input type="text" name="nombre" id="nombre">

    <label for="pass">password</label>
    <input type="password" name="pass" id="pass">


    <button type="submit" name="operacion" id="operacion" value="login">Iniciar Sesion</button>
  </form>
</fieldset>

<?php 
fin_html();
?>