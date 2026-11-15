<?php
// Lee los bienes raíces desde el archivo JSON proporcionado por el profesor.
function cargarInmuebles() {
    $archivo = __DIR__ . '/data-1.json';
    $contenido = file_get_contents($archivo);
    $datos = json_decode($contenido, true);
    return is_array($datos) ? $datos : [];
}

// Convierte precios como "$30,746" en números para poder compararlos.
function convertirPrecio($precio) {
    return (float) str_replace(["$", ","], "", $precio);
}

// Protege el contenido mostrado en HTML.
function limpiar($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}
?>
