<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Variables</title>
  </head>
  <body>
<h1>Variables</h1>
<?php
// Una variable se declara al asignarle un valor por primera vez
// sintaxis: $variable = expresión

$numero = 45;

// Si utilizo una variable que no está declarada (a la que todavía
// no le he asignado valor ), entonces es undefined.
$resultado = $num + 4;
echo "<p>El resultado es $resultado</p>";

?>
<h2>Análisis de variables</h2>
<h3>Análisis simple</h3>
<?php
// Consiste en introducir una variable en una cadena con " o heredoc
// para incrustar su valor dentro de la cadena.
$num = 5;
echo "<p>Variable num interpolada: $num</p>"
?>
<h3>Análisis complejo</h3>
<?php
$calle = "Trafalgar Sq";
$numero = 5;
$población = "London";
$distrito = 5000;

// Tiene que salir: 5th, Trafalgar Sq London 5000
echo "<p>Mi dirección en Londres es $numeroth, $calle $poblacion $distrito</p>";
// Lo evitamos con {}
echo "<p>Mi dirección es Londres es {$numero}th, $calle $poblacion $distrito</p>";
?>

<h2>Funciones para variables</h2>
<?php
// Función gettype() -> Devuelve el tipo de una variable
$numero = 10;
echo "<p>El tipo de datos de $resultado es " . gettype($resultado) . "</p>";
echo "<p>El tipo de datos de una expresión es "  . gettype($numero + 5.5) . "</p>";

// Función empty() -> Devuelve true si una variable está vacía. Comprueba
// que la variable tenga un valor
// - Si es un entero devuelve true si es 0, false en caso contrario.
// - Si es un float devuelve true si es 0.0, false en caso contrario.
// - Si es cadena devuelve true si es "", false en caso contrario.
// - Devuelve true si la variable es null o false

if( empty($numero) ) echo "<p>\$numero está vacía<br>";
else echo "<p>\$numero tiene el valor $numero<br>";

// Función isset() -> Devuelve true si la variable está definida y con un valor
// distinto de null, false en caso contrario
if( isset($nueva_variable) ) echo "Está definida<br>";
else echo "La variable no se ha definido todavía<br>";

$variable_null = null;
if( isset($variable_null) ) echo "La variable_null está definida<br>";
else echo "La variable null no está definida<br>";

echo "</p>";

/* Funciones que comprueban el tipo de datos
  - is_bool()-> True si la expresión, es booleana
  - is_int() -> True si la expresión es entera
  - is_float() -> True si la expresión es float
  - is_string() -> True si la expresión es cadena
  - is_array() -> True si la expresión es un array

  En cualquier otro caso, devuelve false
  */

$edad = 25;
$mayor_edad = $edad > 18;
$numero_e = 2.71;
$mensaje = "El número e es {$numero_e}<br>";

if( is_int($edad) ) echo "\$edad es un entero<br>";
if( is_bool($mayor_edad) ) echo "\$mayor_edad es booleana<br>";
if( is_float($numero_e) ) echo "\$numero_e es float<br>";
if( is_string($mensaje) ) echo "\$mensaje es una cadena<br>";
?>
<h2>Constantes</h2>
<p>Una constante es un valor con nombre que no puede cambiar de valor a lo largo del programa.
  Se le asigna un valor en la declaración y permanece invariable. Hay 2 formas de definir una constante:
    <ul>
      <li>Mediante la función define()</li>
      <li>Mediante la palabra clave const</li>
    </ul>
</p>
<?php
// 1ª forma: Función define()
define("PI", 3.1415);
define("PRECIO_BASE", 1500);
define("DIRECTORIO_SUBIDAS", "/var/www/uploads/archivos");

echo "<p>El número PI es " . PI . "<br>";
$area_circulo = PI * 4 * 4;
echo "El área del círculo es $area_circulo<br>";

$path_archivo = DIRECTORIO_SUBIDAS . "/archivo.pdf";
echo "El archivo subido es $path_archivo<br>";

$precio_rebajado = PRECIO_BASE - PRECIO_BASE * 0.25;
echo "El precio rebajado es $precio_rebajado<br>";

// 2ª forma: mediante la palabra clave const
const SESION_USUARIO = 600;
echo "La sesión de usuario finaliza en " . SESION_USUARIO . " segundos<br>";

// Constantes predefinidas
echo "Este script es " . __FILE__ . " y está en el directorio " 
. __DIR__ . ". El número de línea es " . __LINE__ . "<br>";

?>


</body>
</html>