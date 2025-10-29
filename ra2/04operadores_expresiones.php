<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Operadores y expresiones</title>
  </head>
  <body>
<h1>Expresiones, operadores y operandos</h1>
<p>Una expresión es una combinación de operandos y operadores que 
  arroja un resultado. Tiene un tipo de datos, que depende del tip 
  de datos de sus operandos y de la operación realizada.<br>
  Un operador es un símbolo formado por uno, dos o tres caracteres 
  que denota una operación.<br>
  Los peradores pueden ser:
  <ul>
    <li>Unarios -> Solo necesitan un operando.</li>
    <li>Binarios -> Necesitan dos operandos.</li>
    <li>Ternarios -> Utilizan 3 operandos.
  </ul>
  Un operando es una expresión en si misma, siendo la más simple un literal 
  o una variable, pero también puede ser un valor devuelto por una función 
  o el resultado de otra expresión.<br>
  Las operaciones de una expresión no se ejecutan a la vez, sino en un orden
  según la precedencia (prioridad) y asociatividad del operador. Esto se 
  puede cambiar a conveniencia.
</p>
<h2>Operadores</h2>
<h3>Asignación</h3>
<?php
// El operador de asignación = 
$numero = 45;
$resultado = $numero + 5 - 29;
$sin_valor = null;
?>
<h3>Aritméticos</h3>
<?php
  /*
    + Suma
    - Resta
    * Multiplicación
    / División
    % Módulo o resto de la división entera
    ** Expenciación

    Unarios
    + Conversión a entero
    - El opuesto
  */
  $numero1 = 15;
  $numero2 = 18;
  $suma = $numero1 + 10;
  $resta = 25 - $numero2;
  echo "<p>La suma de $numero1 y $numero2 es $suma, y la resta es $resta</p>";

  // El opuesto
  $opuesto = -$numero1;
  echo "<p>El opuesto de $numero1 es $opuesto</p>";

  $resta = $numero2 - 75;
  $opuesto = -$resta;
  echo "<p>El opuesta de $resta es $opuesto</p>";

  $multiplicacion = $numero1 * 3;
  $division = $numero1 / 3;

  $modulo = $numero1 % 4;

  $potencia = $numero1 ** 3;

  echo "<p>La multiplación de $numero1 por 3 es $multiplicacion</p>";
  echo "<p>La división de $numero1 entre 3 es $division</p>";
  echo "<p>El resto de dividir $numero1 entre 4 es $modulo</p>";
  echo "<p>$numero1 elevado a 3 es $potencia</p>";

  // Convertir a entero
  $numero3 = "35";
  $numero4 = +$numero3;
  echo "<p>El \$numero3 es $numero3 y su tipo es " . gettype($numero3) . "</p>";
  echo "<p>El \$numero4 es $numero4 y su tipo es " . gettype($numero4) . "</p>";

  // Con float
  $numero5 = 3.14159;
  $numero6 = +$numero5;
  echo "<p>El \$numero5 es $numero5 y su tipo es " . gettype($numero5) . "</p>";
  echo "<p>El \$numero6 es $numero6 y su tipo es " . gettype($numero6) . "</p>"; 
?>
<h3>Asignación aumentada</h3>
<?php
/* Operadores de asignación aumentada
   Además de una operación aritmética, hay una asignación

   ++ Incremento
   -- Decremento
   += Suma y asignación
   -= Resta y asignación

   *= Multiplicación y asignación
   /= División y asignación
   %= Módulo y asignación
*/

$numero = 4;
$numero++;   // Equivalente a $numero = $numero + 1;
echo "<p>El número incrementado es $numero</p>";
++$numero;
echo "<p>El número incrementado es $numero</p>";

$numero = 10;
$resultado = $numero++ * 2;
echo "<p>El resultado es $resultado y el número es $numero</p>";

$numero = 10;
$resultado = ++$numero * 2;
echo "<p>El resultado es $resultado y el número es $numero</p>";

$numero += 5; // Equivale a $numero = $numero + 5;
echo "<p>El numero es $numero</p>";
$numero -= 3; // Equivale a $numero = $numero - 3;
echo "<p>El numero es $numero</p>";

$numero *= 3; // Equivale a $numero = $numero * 3;
echo "<p>El numero es $numero</p>";

$numero %= 7; // Equivale a $numero = $numero % 7;
echo "<p>El numero es $numero</p>";
?>

<h2>Operadores relacionales</h2>
<?php
/*
  ==    Igual a 
  ===   Idéntico ( iguales y del mismo tipo)
  !=    Distinto
  !==   Distinto o distinto tipo
  >     Mayor
  <     Menor
  >=    Mayor o igual
  <=    Menor o igual
  <=>   Nave espacial
*/

$n1 = 5;
$cadena = "5";
$n2 = 8;

$resultado = $n1 == $n2;
echo "<p>Es n1 igual que n2 " . (int)$resultado . "</p>";

$resultado = $n1 == $cadena;
echo "<p>Es n1 igual a cadena " . (int)$resultado . "</p>";

$resultado = $n1 === $cadena;
echo "<p>Es n1 igual a cadena " . (int)$resultado . "</p>";

$resultado = $n1 != $n2;
echo "<p>Es n1 distinto de n2 " . (int)$resultado . "</p>";

// Operador !== True si son distintos o de diferente tipo, false en caso contrario
$resultado = $n1 !== $cadena;
echo "<p>Es n1 no idéntico a cadena " . (int)$resultado . "</p>";

// Nave espacial
/*
  Si n1 es mayor que n2 el resultado es 1
  Si n1 es igual que n2 el resultado es 0
  Si n1 es menor que n2 el resultado es -1

  Se emplea para evitar lo siguiente
  if( $n1 > $n2 ) {
  
  }
  elsif( $n1 < $n2 ) {
  
  }
  else {
    
  }
*/
$resultado = $n1 <=> $n2;
echo "<p>El resultado es $resultado</p>";

$nombre1 = "abcZacarias";
$nombre2 = "abcadela";
$resultado = $nombre1 > $nombre2;
// Sale falso por la comparación basada en código ASCII
echo "<p>El resultado es " . (int)$resultado . "</p>";

// Si necesitamos comparar sin tener en cuenta las mayúsculas

$resultado = strtoupper($nombre1) > strtoupper($nombre2);
echo "<p>El resultado es " . (int)$resultado ."</p>";

$nombre1 = "MariO";
$nombre2 = "Maria";
$resultado = $nombre1 > $nombre2;
echo "<p>El resultado es " . (int)$resultado ."</p>";

$resultado = strtolower($nombre1) > strtolower($nombre2);
echo "<p>El resultado es " . (int)$resultado ."</p>";
?>
<h2>Operadores lógicos</h2>
<p>Los operadores lógicos unen expresiones relacionales construidas
  con los operadores relacionales anteriores. Arrojan un resultado
  booleano en función de las tablas de verdad.
<?php
/*
  AND -> Operador lógico AND. True si ambas expresiones son true, false
         en caso contrario.
  OR  -> Operador lógico OR. True si alguna de las expresiones es true,
         false si ambas expresiones son false.
  XOR -> Operador lógico OR exclusivo. True si una, y solo una, de las expresiones
         es true, false ambas de la expresiones son true o false.
  &&  -> Similar a AND pero con mayor precedencia
  ||  -> Similar a OR pero con mayor precedencia
  !   -> Operador lógico NOT. Invierte el resultado booleano de una expresión.
*/

$n1 = 9;
$n2 = 5;
$n3 = 10;

$resultado = $n1 == $n2 or $n2 > $n3;
echo "<p>El resultado es " . (int)$resultado ."</p>";

$resultado = $n1 == $n2 and $n2 < $n3;
echo "<p>El resultado es " . (int)$resultado ."</p>";

$resultado = $n1 == 9 or $n2 < $n1 and $n3 > 10;
echo "<p>El resultado es " . (int)$resultado ."</p>";

$resultado = $n1 === 9 || $n2 < $n1 and $n3 > 10;
echo "<p>El resultado es " . (int)$resultado ."</p>";

$resultado = !($n1 === 9) || $n2 < $n1;
echo "<p>El resultado es " . (int)$resultado ."</p>";
?>
<h2>Precedencia y asociatividad</h2>
<p>En una expresión con múltiples operadores se ejecutan en un determinado orden dictado por 
  la precedencia de cada operador. Si hay más de un operador con la misma precedencia, 
  se aplica la asociatividad.
</p>
<?php
$resultado = $n1 + 5 / $n3 < $n1 ** 3 and $n3 / 5 + $n2 * 2 >= $n1 * $n2 / $n3 or $n1 - 3 % 2 == $n3 - 7;
/*
                   0,5        729          2           10        45 / 10                  1
                   0,5        729          2           10        4,5                      1
                     9,5   <  729                 12        >=   4,5                      8     ==      3     
                          true            and               true                  or            false
                                          true                                    or            false
                                                                                  true
*/

echo "<p>El resultado es " . (int)$resultado ."</p>";
$resultado = ($n1 + 5) / $n3 < $n1 ** 3 and $n3 / ((5 + $n2) * 2) >= $n1 * ($n2 / $n3) or ($n1 - 3) % 2 == $n3 - 7;

echo "<p>El resultado es " . (int)$resultado ."</p>";

?>

<!--
  ESTILOS DE CODIFICACIÓN

  Variables, métodos y variables de instancia     -> camelCase Ej. precioVenta, descuentoFinal

  Clases                                          -> PascalCase Ej. ClientePotencial Proveedor 

  Constantes                                      -> GREAT_SNAKE_CASE Ej. PORCENTAJE_IVA NUMERO_PI

  Propiedades de clase (variables de clase)       -> GREAT_SNAKE_CASE 

-->
</body>
</html>