<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['operacion'] == 'login') {
  $email = filter_input(INPUT_POST, 'email' , FILTER_SANITIZE_SPECIAL_CHARS);
  $email = filter_var($email, FILTER_VALIDATE_EMAIL);

  $pass = $_POST['pass'];

  if (!array_key_exists($email, $usuarios)) {
    $_SESSION['errores'][] = "el email $email no existe no esta registrado";
    header('location: 01carrito.php');
    exit();
  }

  if (!password_verify($pass, $usuarios[$email]['pass'])) {
    $_SESSION['errores'][] = 'Contraseña incorrecta';
    header('location: 01carrito.php');
    exit();
  }

  $usuario = $usuarios[$email];
  $payload = [
    'email' => $email,
    'nombre' => $usuario['nombre'],
    'direccion' => $usuario['direccion'],
    'telefono' => $usuario['telefono'],
  ];

  $jwt = generarJWT($payload);
  setcookie('jwt' , $jwt, time() + 1 *24 *60 *60, '/');

  header('location: 01carrito.php');
}

inicio_html("02 Pantalla login", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);

?>

<h1>Ultramarinos Guilliman</h1>
<fieldset>
  <legend>login</legend>
  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
    <label for="email">email</label>
    <input type="email" name="email" id="email">

    <label for="pass">password</label>
    <input type="password" name="pass" id="pass">

  <button type="submit" name="operacion" id="operacion" value="login">iniciar sesion</button>
  </form>
</fieldset>

<?php

fin_html();
?>