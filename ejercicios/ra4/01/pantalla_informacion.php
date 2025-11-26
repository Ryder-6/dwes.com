<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');

inicio_html("01 Pantalla informacion", ['/estilos/formulario.css', '/estilos/general.css', '/estilos/tabla.css']);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Directorio elegido en pantalla inicial
    $directorioRel = $_COOKIE['directorio'];                 // ej: /ejercicios/ra4/01/archivos
    $directorioAbs = $_SERVER['DOCUMENT_ROOT'] . $directorioRel;

    // Nombre del archivo seleccionado
    $archivo = $_POST['fichero'];

    // Ruta absoluta del archivo
    $rutaAbs = $directorioAbs . "/" . $archivo;

    // URL pública para descarga
    $rutaUrl = $directorioRel . "/" . $archivo;

    if (is_file($rutaAbs)) {

        $size = filesize($rutaAbs);
        $type = mime_content_type($rutaAbs);

        ?>
        <h2>Nombre: <?= htmlspecialchars($archivo) ?></h2>
        <h2>Tipo: <?= htmlspecialchars($type) ?></h2>
        <h2>Tamaño: <?= $size ?> bytes</h2>

        <p>
            Descargar:
            <a href="<?= $rutaUrl ?>" download>Descargar archivo</a>
        </p>

        <a href="/ejercicios/ra4/01/pantalla_lista.php">Volver</a>

        <?php  } else {
  ?>
    <h3>error no es un fichero</h3>
    <a href="/ejercicios/ra4/01/pantalla_inicial.php"></a>
<?php
  }
}

fin_html();
?>