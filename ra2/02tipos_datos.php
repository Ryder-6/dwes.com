<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Tipos de datos</title>
  </head>
  <body>
<h1>Tipos de datos</h1>
<h2>Tipos escalares (primitivos)</h2>
<ul>
  <li>Booleanos</li>
  <li>Numérico entero</li>
  <li>Numérico en coma flotante</li>
  <li>Cadenas de caracteres</li>
</ul>

<h2>Tipos de datos compuestos</h2>
<ul>
  <li>Arrays</li>
  <li>Objetos</li>
  <li>Callable (funciones)</li>
  <li>Iterable</li> 
</ul>

<h2>Tipos especiales</h2>
<ul>
  <li>Null</li>
  <li>Resource</li>
</ul>

<h2>Booleanos</h2>
<p>Inicialmente las constantes true y false son valores de tipo booleano. Sin embargo
  PHP extiende el significado de valor booleano a valor cierto y falsedad en otros 
  tipos de datos</p>
<ul>
  <li>Numérico entero: 0 y -0 es false, cualquier otro es true</li>
  <li>Númerico en coma flotante: 0.0 y -0.0 es false, cualquier otro es true</li>
  <li>Un array con 0 elementos es False, con elementos es true.</li>
  <li>El tipo especial null es false, un valor distinto de null es true.</li>
  <li>Una variable no definida es false</li>
  <li>La cadena vacía es false, cualquier otra es true.</li>
</ul>

<?php
$valor_booleano = true;
$edad = 20;
$mayor_edad = $edad > 18;

echo "<p>Mayor de edad es booleano: " . is_bool($mayor_edad) . "</p>";

$dinero = 20;
// Pregunta si $dinero es != 0
if( $dinero ) echo "<p>Soy rico</p>";

$mi_nombre = "Juan";
// Pregunta si $mi_nombre es != de ""
if( $mi_nombre ) echo "<p>Me llamo $mi_nombre</p>";
?>

<h2>Enteros</h2>
<p>En PHP los números enteros son de 32 bits. Pueden expresarse en diferentes
  notaciones</p>
<?php
$numero_entero = 1234;
echo "<p>El número entero es $numero_entero</p>";

$numero_negativo = -123;
echo "<p>Un número negativo se precede con -: $numero_negativo</p>";

// Podemos expresar un número en octal
$numero_octal = 0120;
echo "<p>El número octal 0120 es en el sistema decimal: $numero_octal</p>";

// Puedo mostrar el número en octal con la función decoct()
echo "<p>El número octal 0120 expresado en octal es " . decoct($numero_octal) . "</p>";

// Número en hexadecimal
$numero_hex = 0xAB9C;
echo "<p>El número hex AB9C es en el sistema decimal: $numero_hex</p>";

// Puedo mostrar el número hexadecimal en hexadecimal con la función dechex()
echo "<p>El número hex AB9C es en hexadecimal: " . dechex($numero_hex) . "</p>";

// Un número expresado en binario
$numero_binario = 0b10101010;
echo "<p>El número binario 10101010 es: $numero_binario</p>";

// Puedo mostrar el número binario en binario con la función decbin()
echo "<p>El número binario  " . decbin($numero_binario) . " es $numero_binario</p>";

// Los números enteros se almacenan en memoria en el sistema decimal
// aunque los haya expresado en diferentes notaciones.

// Con cualquier función dec...() obtengo el número en un sistema de numeración
echo "<p>El número binario $numero_binario en hexadecimal es " . dechex($numero_binario) . "</p>";

$numero_binario = 0b11111111; // el 255 en decimal y FF en hexadecimal
echo "<p>El número binario $numero_binario en hexadecimal es " . dechex($numero_binario) . "</p>";

?>

<h2>Números en coma flotante</h2>
<p>El separador decimal es el punto . y se pueden expresar números muy grandes o muy pequeños
  con notación científica</p>
<?php
$pi = 3.14159;
echo "<p>El número PI (relación entre longitud y diámetro de una circunferencia) es $pi</p>";
echo "<p>El número PI pero con 3 decimales " . round($pi, 3) . "</p>";

$inf_int = 7.9e13;  // 7.9 x 10 ^13
echo "<p>La información que circula por Internet en un día es: $inf_int</p>";

$tamayo_virus = 0.2e-9;
echo "<p>El tamaño de un virus es $tamayo_virus</p>";
?>

<h2>Cadenas de caracteres</h2>
<p>El tipo string o cadena de caracteres es una serie de caracteres donde cada caracter equivale a un
  byte. PHP solo admite 256 caracteres, las cadenas están en ASCII y no hay soporte utf8. Hay 4 formas 
  de expresar una cadena de caracteres:</p>
  <ul>
    <li>Comillas simples</li>
    <li>Comillas dobles</li>
    <li>Cadena Heredoc</li>
    <li>Cadena Nowdoc</li>
  </ul>

<h3>Comillas simples</h3>
<?php
// Una cadena de caracteres entre comillas simples no reconoce ningún carácter de escape
// excepto \' y \\ y además, no podemos interpolar variables.
echo '<p>Esto es una cadena sencilla</p>';
echo '<p>Puedo poner una cadena
en varias líneas 
porque la sentencia
no acaba hasta
el punto y coma</p>';

// No se reconocen carácteres de escape, salvo \' y \\
echo '<p>El mejor pub irlandes es O\'Donnel</p>';
echo '<p>La raíz del disco duro en Windows es C:\</p>';
echo '<p>La raíz del disco duro en Windods es C:\\</p>';

echo '<p>Esta cadena tiene salto\nde línea</p>';

// NO interpola variables
echo '<p>Hola, $mi_nombre, ¿cómo estás?</p>';
?>

<h3>Comillas dobles</h3>
<p>Es la forma habitual de expresar cadenas de caracteres ya que expande los caracteres de escape y las 
  variables </p>

<?php
$cadena = "Esto es una cadena con comillas dobles";
echo "Es una cadena un objeto? " . is_object($cadena) . "</p>";
if( is_object($cadena)) echo "<p>La cadena es objeto</p>";
else echo "<p>La cadena no es un objeto</p>";

$con_secuencias = "<p>\t\tEl símbolo \$ se emplea para las variables \n y
si lo quieres en una cadena hay que escaparlo con \\. Es mejor usar \" para
delimitar las cadenas en lugar de '</p>";

echo $con_secuencias;
?>
<h3>Cadenas HEREDOC</h3>
<p>Es una cadena muy larga, incluyendo saltos de línea que se respetan, que comienza por <<< y un 
identificador (generalmente en mayúsculas). Justo después hay un salto de línea y se escribe
la cadena, con saltos de línea que sean necesarios, con interporlación de variables y caracteres 
de escape. Para finalizar la cadena se hace un salto de línea y se vuelve a poner el mismo identificador</p>
<?php
$cadena_hd = <<<CADENAHD
<p>Esto es una cadena
heredoc que respeta los
saltos de línea, le puedo
poner variables como $mi_nombre y
además secuencias de escape. El
identificado no necesita \$ y tampoco
usamos \", simplemente la escribimos y
acabamos con un salto de línea más el 
identificador</p>
CADENAHD;

echo <<<TABLA
<table border='1'>
  <caption>Tabla de prueba</caption>
  <thead>
    <tr>
      <th>Referencia</th><th>Descripción</th><th>PVP</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
  </table>
TABLA;
?>

<h3>Cadenas NOWDOC</h3>
<p>La cadena Nowdoc es como Heredoc pero con comillas simples. No se interpolan variables ni se 
  reconocen secuencias de escapa más allá de \ y '. No se respetan los saltos de línea</p>
<?php
$cadena_nd = <<<'ND'
<p>Esta es una cadena nowdoc
y el salto de línea no lo respeta,
no puedo meter variables
y solo reconoce \\ y \'</p>
ND;
?>

<h2>Conversión de tipos</h2>
<p>Hay dos tipos de conversiones de tipos: implícitas y explícitas. Las primeras ocurren cuando 
  en una expresión hay operandos de diferente tipo. PHP convierte algunos de ellos para evaluar 
  la expresión.</p>

<?php
$cadena = "25";
$numero = 8;
$booleano = true;
$resultado = $cadena + $numero + $booleano; // 25 + 8 + 1 (por el true)
echo "<p>El resultado es $resultado</p>";

echo <<<IMPORTANTE
<p>¡¡¡IMPORTANTE!!! Cuando se haga la conversión implícita solo afecta al operando durante la 
evaluación de la expresión, pero no cambia el tipo de datos de la variable. Es decir, la conversión 
de la variable $cadena a entero solamente para evaluar la expresión de suma, pero $cadena sigue 
siendo de tipo string</p>
IMPORTANTE;

$flotante = 3.5;
$resultado = $cadena + $flotante + $numero + $booleano;
echo "<p>El resultado es: $resultado</p>";

$cadena = "25 marranos dan más provecho que 7 lechones";
$resultado = $numero + $cadena;
echo "<p>El resultado es: $resultado</p>";

echo <<<EXPLICITAS
<p>Las conversiones explícitas, conocidas como casting o moldeo, se hacen precediendo 
la expresión con el tipo de datos a convertir entre paréntesis</p>
EXPLICITAS;

// Si quiero convertir a un número entero (int)expresión
// Si quiero convertir a un float (float)expresión
// Si quiero convertir a string (string)expresión

echo "<p style='text-decoration:underline;'>Conversiones a entero</p>";
$valor_booleano = true;
$valor_convertido = (int)$valor_booleano;
echo "<p>El valor convertido a entero es $valor_convertido</p>";
$valor_float = 3.94159;
$valor_convertido = (int)$valor_float;
echo "<p>El valor convertido a entero es $valor_convertido</p>";

$valor_cadena = "123";
$valor_convertido = (int)$valor_cadena;
echo "<p>El valor convertido a entero es $valor_convertido</p>";

$valor_cadena = " 123";
$valor_convertido = (int)$valor_cadena;
echo "<p>El valor convertido a entero es $valor_convertido</p>";

$valor_cadena = "a123";
$valor_convertido = (int)$valor_cadena;
echo "<p>El valor convertido a entero es $valor_convertido</p>";

echo "<p style='text-decoration:underline;'>Conversiones a coma flotante</p>";
$valor_float = "3.5614";
$valor_convertido = (float)$valor_float;
echo "<p>El valor convertido a float es $valor_convertido</p>";

$valor_float = "2.5e-10";
$valor_convertido = (float)$valor_float;
echo "<p>El valor convertido a float es $valor_convertido</p>";
echo "<p>Tipo de datos del valor convertido " . gettype($valor_convertido) . "</p>";
?>

</body>
</html>