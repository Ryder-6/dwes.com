<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Elementos del lenguaje</title>
  </head>
  <body>
    <h1>Elementos del lenguaje</h1>
    <h2>Entrada y Salida</h2>
    <p>La entrada de datos en PHP es con un formulario o enlace. La salida
      se produce con la función echo, y su forma abreviada, y la función print.

      Además, tenemos la función printf para salida con formato.
    </p>
    <h3>Función echo</h3>
<?php
echo "<p>La función echo emite el resultado de una expresión a la salida (del servidor
al cliente web). Se puede usar como función o como construcción del lenguaje (sin paréntesis)</p>";

echo "<p>Esto es un párrafo HTML enviado con echo</p>";

$nombre = "Juan";
echo "<p>Hola, $nombre, ¿cómo estás?</p>";

echo "<p>Hola", $nombre, "¿cómo estás?</p>";

// Esto no sirve porque hay más de un argumento
//echo("<p>Hola", $nombre, "¿cómo estás?</p>");

echo("<p>Hola, $nombre, ¿cómo estás?</p>");

// Quiero un salto de línea al final de la línea
echo "<p>Hola, esta línea acaba en un salto \n";
echo "Supuestamente esta línea es la siguiente a la anterior \n y esta va después</p>";

$nombre = "José";
$apellidos = "Gómez";
echo "<br>Mi nombres es $nombre y mi apellido es $apellidos<br>";
echo "<br>Mi nombre es " . $nombre . "y mi apellido es " . $apellidos . "<br>";

echo "<br>Uno más dos son " . 1 + 2 . " y tiene que haber salido 3<br>";

echo "<h4>Forma abreviada de echo</h4>";
echo "<p>Cuando hay que enviar a la salida el resultado de una expresión pequeña 
disponemos de la forma abreviada de echo que permite intercarlarse en el código HTML con
menos esfuerzo y más legible</p>";

$tiene_portatil = true;
?>

<!-- Estamos en modo HTML -->
<p>Mi nombre es <?= $nombre . " " . $apellidos ?> y estoy programando en PHP</p>

<!-- Uso habitual de echo abreviado es en los formularios -->

<input type="text" size="30" name="nombre" id="nombre" value="<?=$nombre?>">
<input type="checkbox" name="portatil" id="portatil" <?= $tiene_portatil ? "checked" : ""?> >

<?php
// Qué ocurre si tengo que enviar a la salida código HTML con cadenas de caracteres
echo "<input type='text' name='apellidos' id='apellidos' size='30'>";
?>

<!-- Función print -->
<h4>Función print</h4>
<p>Funciona como echo</p>

<?php
print "<p>Esto es una cadena\n que tiene más de una línea\n y se envía a la salida</p>";

$pi = 3.14159;
$radio = 3;
$circunferencia = 2 * $pi * $radio;
print "<p>La longitud de la circunferencia de radio $radio es $circunferencia</p>";

// La función printf permite dar salida con formato
echo "<h4>Función printf()</h4>";
printf("<br>La circunferencia de radio %d es %1$010.2f", $radio, $circunferencia);
?>

  </body>
</html>