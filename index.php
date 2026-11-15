<?php
require_once __DIR__ . '/funciones.php';
$inmuebles = cargarInmuebles();

// Obtener ciudades y tipos únicos desde los datos generales.
$ciudades = array_unique(array_column($inmuebles, 'Ciudad'));
$tipos = array_unique(array_column($inmuebles, 'Tipo'));
sort($ciudades);
sort($tipos);

// Valores iniciales del rango de precios.
$precioMinimoDatos = 0;
$precioMaximoDatos = 100000;
$ciudadElegida = '';
$tipoElegido = '';
$precioMinimo = $precioMinimoDatos;
$precioMaximo = $precioMaximoDatos;
$resultados = [];
$seBusco = false;

// Mostrar todos se activa con el botón correspondiente.
if (isset($_GET['todos'])) {
    $seBusco = true;
    $resultados = $inmuebles;
}

// Procesar filtros enviados desde el formulario.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seBusco = true;
    $ciudadElegida = $_POST['ciudad'] ?? '';
    $tipoElegido = $_POST['tipo'] ?? '';
    $rango = $_POST['precio'] ?? '';

    // El control ionRangeSlider envía normalmente dos valores separados por punto y coma.
    if (strpos($rango, ';') !== false) {
        $partes = explode(';', $rango);
        $precioMinimo = (float) ($partes[0] ?? 0);
        $precioMaximo = (float) ($partes[1] ?? 100000);
    } elseif ($rango !== '') {
        $precioMinimo = 0;
        $precioMaximo = (float) $rango;
    }

    if ($precioMinimo > $precioMaximo) {
        $temporal = $precioMinimo;
        $precioMinimo = $precioMaximo;
        $precioMaximo = $temporal;
    }

    foreach ($inmuebles as $inmueble) {
        $precio = convertirPrecio($inmueble['Precio']);
        $coincideCiudad = ($ciudadElegida === '' || $inmueble['Ciudad'] === $ciudadElegida);
        $coincideTipo = ($tipoElegido === '' || $inmueble['Tipo'] === $tipoElegido);
        $coincidePrecio = ($precio >= $precioMinimo && $precio <= $precioMaximo);

        if ($coincideCiudad && $coincideTipo && $coincidePrecio) {
            $resultados[] = $inmueble;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buscador de bienes raíces</title>
  <link rel="stylesheet" href="css/materialize.min.css">
  <link rel="stylesheet" href="css/customColors.css">
  <link rel="stylesheet" href="css/ion.rangeSlider.css">
  <link rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css">
  <link rel="stylesheet" href="css/index.css">
</head>
<body>
  <div class="contenedor">
    <div class="card rowTitulo"><h1>Buscador de bienes raíces</h1></div>
    <aside class="colFiltros">
      <form action="buscador.php" method="post" id="formulario">
        <div class="filtrosContenido">
          <div class="tituloFiltros"><h5>Realiza una búsqueda personalizada</h5></div>
          <div class="filtroCiudad input-field">
            <label for="selectCiudad">Ciudad:</label>
            <select name="ciudad" id="selectCiudad">
              <option value="">Todas las ciudades</option>
              <?php foreach ($ciudades as $ciudad): ?>
                <option value="<?= limpiar($ciudad) ?>" <?= $ciudadElegida === $ciudad ? 'selected' : '' ?>><?= limpiar($ciudad) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="filtroTipo input-field">
            <label for="selectTipo">Tipo:</label>
            <select name="tipo" id="selectTipo">
              <option value="">Todos los tipos</option>
              <?php foreach ($tipos as $tipo): ?>
                <option value="<?= limpiar($tipo) ?>" <?= $tipoElegido === $tipo ? 'selected' : '' ?>><?= limpiar($tipo) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="filtroPrecio">
            <label for="rangoPrecio">Rango de precio:</label>
            <input type="text" id="rangoPrecio" name="precio" value="<?= limpiar($precioMinimo . ';' . $precioMaximo) ?>">
          </div>
          <div class="botonField"><input type="submit" class="btn white" value="Buscar" id="submitButton"></div>
        </div>
      </form>
    </aside>
    <main class="colContenido">
      <div class="tituloContenido card">
        <h5>Resultados de la búsqueda:</h5>
        <div class="divider"></div>
        <p class="resumen">
          <?php if ($seBusco): ?>Se encontraron <?= count($resultados) ?> inmueble(s).<?php else: ?>Selecciona los filtros o muestra todos los inmuebles.<?php endif; ?>
        </p>
        <a href="buscador.php?todos=1" class="btn-flat waves-effect botonTodos">Mostrar todos</a>
      </div>
      <?php if ($seBusco && count($resultados) === 0): ?>
        <div class="card-panel">No se encontraron inmuebles con esos filtros. Intenta cambiar el rango de precios o los criterios.</div>
      <?php endif; ?>
      <?php foreach ($resultados as $inmueble): ?>
        <div class="itemMostrado card horizontal">
          <div class="card-image"><img src="img/home.jpg" alt="Imagen de inmueble"></div>
          <div class="card-stacked">
            <div class="card-content">
              <span class="precioTexto"><?= limpiar($inmueble['Precio']) ?></span>
              <p><strong><?= limpiar($inmueble['Tipo']) ?></strong></p>
              <p><?= limpiar($inmueble['Direccion']) ?></p>
              <p><?= limpiar($inmueble['Ciudad']) ?>, C.P. <?= limpiar($inmueble['Codigo_Postal']) ?></p>
              <p>Teléfono: <?= limpiar($inmueble['Telefono']) ?></p>
            </div>
            <div class="card-action">Registro #<?= limpiar($inmueble['Id']) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </main>
  </div>
  <script src="js/jquery-3.0.0.js"></script>
  <script src="js/ion.rangeSlider.min.js"></script>
  <script src="js/materialize.min.js"></script>
  <script>
    $(document).ready(function () {
      $('select').material_select();
      $('#rangoPrecio').ionRangeSlider({
        type: 'double', min: 0, max: 100000,
        from: <?= (float)$precioMinimo ?>,
        to: <?= (float)$precioMaximo ?>,
        step: 500, prefix: '$', grid: true
      });
    });
  </script>
</body>
</html>
