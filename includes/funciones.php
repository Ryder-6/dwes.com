<?php
function inicio_html(string $titulo, array $hojas_estilo) { ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, intial-scale=1">
        <title><?=$titulo?></title>
<?php
    $raiz_servidor = $_SERVER['DOCUMENT_ROOT'];
    foreach($hojas_estilo as $hoja) {
        echo "\t\t<link type='text/css' rel='stylesheet' href='$hoja'>\n";
    }
?>
    </head>
    <body>
<?php        
}

function fin_html() { ?>
    </body>
</html>
<?php
}
?>