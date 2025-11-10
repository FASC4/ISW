<?php
require __DIR__ . '/includes/init.php';

$alerta = null;
$alertaTipo = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Recoger datos del formulario
  $nombre = trim($_POST['nombre'] ?? '');
  $marca = trim($_POST['marca'] ?? '');
  $modelo = trim($_POST['modelo'] ?? '');
  $alcance = trim($_POST['alcance'] ?? '');
  $unidades = trim($_POST['unidades'] ?? '');
  $puntos_minimos = (int) ($_POST['puntos'] ?? 0);
  $magnitud = trim($_POST['magnitud'] ?? '');

  // Variables adicionales dependiendo de la magnitud
  $temp_rango_min = ($magnitud === 'temperatura') ? (float) ($_POST['temp_rango_min'] ?? 0) : null;
  $temp_rango_max = ($magnitud === 'temperatura') ? (float) ($_POST['temp_rango_max'] ?? 0) : null;
  $temp_calibracion = ($magnitud === 'temperatura') ? trim($_POST['temp_calibracion'] ?? '') : null;

  $densidad_min = ($magnitud === 'densidad') ? (float) ($_POST['densidad_min'] ?? 0) : null;
  $densidad_max = ($magnitud === 'densidad') ? (float) ($_POST['densidad_max'] ?? 0) : null;
  $volumen_min = ($magnitud === 'volumen') ? (float) ($_POST['volumen_min'] ?? 0) : null;
  $volumen_max = ($magnitud === 'volumen') ? (float) ($_POST['volumen_max'] ?? 0) : null;

  // Validación básica
  if ($nombre === '' || $marca === '' || $modelo === '' || $alcance === '' || $unidades === '' || $puntos_minimos <= 0 || $magnitud === '') {
    $alerta = 'Por favor, complete todos los campos requeridos.';
    $alertaTipo = 'danger';
  } else {
    try {
      // Insertar datos en la tabla de instrumentos
      $sql = "INSERT INTO instrumentos
        (nombre, marca, modelo, alcance, unidades, puntos_minimos, magnitud,
        temp_rango_min, temp_rango_max, temp_calibracion, 
        densidad_min, densidad_max, volumen_min, volumen_max)
        VALUES
        (:nombre, :marca, :modelo, :alcance, :unidades, :puntos_minimos, :magnitud,
        :temp_rango_min, :temp_rango_max, :temp_calibracion, 
        :densidad_min, :densidad_max, :volumen_min, :volumen_max)";

      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ':nombre' => $nombre,
        ':marca' => $marca,
        ':modelo' => $modelo,
        ':alcance' => $alcance,
        ':unidades' => $unidades,
        ':puntos_minimos' => $puntos_minimos,
        ':magnitud' => $magnitud,
        ':temp_rango_min' => $temp_rango_min,
        ':temp_rango_max' => $temp_rango_max,
        ':temp_calibracion' => $temp_calibracion,
        ':densidad_min' => $densidad_min,
        ':densidad_max' => $densidad_max,
        ':volumen_min' => $volumen_min,
        ':volumen_max' => $volumen_max,
      ]);

      $alerta = 'Instrumento agregado correctamente.';
      $alertaTipo = 'success';

      // Limpiar el formulario
      $_POST = [];
    } catch (PDOException $e) {
      $alerta = 'Error al guardar el instrumento.';
      $alertaTipo = 'danger';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Cotizador MSV — Instrumentos</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Global UI -->
  <script src="global-ui.js" defer></script>

  <!-- Tu CSS -->
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Botón flotante para colapsar/expandir sidebar (todas las resoluciones) -->
  <button class="sidebar-toggle" type="button" aria-label="Alternar menú" aria-expanded="true">⟨</button>

  <div class="container-fluid layout" id="layoutRoot">
    <div class="row g-0">
      <!-- Sidebar -->
      <aside class="col-12 col-sm-4 col-md-3 col-lg-2 sidebar p-3">
        <a class="nav-link mb-2 active" href="dashboard_ad.php">Inicio</a>
        <a class="nav-link mb-2 active" href="historial_ad.php">Historial</a>
        <a class="nav-link mb-2 active" href="ad_usuarios.php">Administrar Usuarios</a>
        <a class="nav-link mb-2" href="ver_clientes.php">Ver clientes</a>

        <button class="btn btn-toggle mb-2 w-100" data-bs-toggle="collapse" data-bs-target="#instrumentos" aria-expanded="false">
          Instrumentos
        </button>
        <div class="collapse" id="instrumentos">
          <ul class="nav flex-column submenu mb-2">
            <li class="nav-item"><a class="nav-link" href="ag_inst.php">Agregar</a></li>
            <li class="nav-item"><a class="nav-link" href="ed_inst.php">Gestionar</a></li>
          </ul>
        </div>

        <a class="nav-link mt-2" href="index.php">Cerrar Sesión</a>
      </aside>

      <!-- Panel derecho -->
      <main class="col-12 col-sm-8 col-md-9 col-lg-10 content-wrap">
        <div class="content p-4">
          <h1 class="h5 mb-3">Datos del instrumento</h1>

          <!-- Mensaje de alerta -->
          <?php if (!empty($alerta)): ?>
            <div class="alert alert-<?= htmlspecialchars($alertaTipo, ENT_QUOTES, 'UTF-8') ?> mb-3">
              <?= htmlspecialchars($alerta, ENT_QUOTES, 'UTF-8') ?>
            </div>
          <?php endif; ?>

          <form class="row gy-3 gx-3" method="POST" action="" novalidate>
            <!-- Nombre del instrumento -->
            <div class="col-12">
              <label for="nombre" class="form-label">Nombre del instrumento</label>
              <input id="nombre" name="nombre" type="text" class="form-control"
                     placeholder="Ej. Balanza Analítica, Calibrador Vernier, etc."
                     maxlength="80" required
                     value="<?= htmlspecialchars($_POST['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="col-12 col-md-6">
              <label for="marca" class="form-label">Marca</label>
              <input id="marca" name="marca" type="text" class="form-control" placeholder="Ej. Ohaus" required
                     value="<?= htmlspecialchars($_POST['marca'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="col-12 col-md-6">
              <label for="modelo" class="form-label">Modelo</label>
              <input id="modelo" name="modelo" type="text" class="form-control" placeholder="Ej. PR224/E" required
                     value="<?= htmlspecialchars($_POST['modelo'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            
            <div class="col-12 col-md-6">
              <label for="alcance" class="form-label">Alcance</label>
              <input id="alcance" name="alcance" type="text" class="form-control" placeholder="Ej. 80" required
                     value="<?= htmlspecialchars($_POST['alcance'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="col-12 col-md-6">
              <label for="unidades" class="form-label">Unidades</label>
              <input id="unidades" name="unidades" type="text" class="form-control" placeholder="Ej. ml" required
                     value="<?= htmlspecialchars($_POST['unidades'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="col-12 col-md-6">
              <label for="puntos" class="form-label">Puntos mínimos a calibrar</label>
              <input id="puntos" name="puntos" type="number" class="form-control" placeholder="Ej. 1" required
                     value="<?= htmlspecialchars($_POST['puntos'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="col-12 col-md-6">
              <label for="magnitud" class="form-label">Magnitud</label>
              <select id="magnitud" name="magnitud" class="form-select" required>
                <option value="" selected disabled>Selecciona magnitud</option>
                <option value="densidad" <?= ($_POST['magnitud'] ?? '') === 'densidad' ? 'selected' : '' ?>>Densidad</option>
                <option value="presion" <?= ($_POST['magnitud'] ?? '') === 'presion' ? 'selected' : '' ?>>Presión</option>
                <option value="temperatura" <?= ($_POST['magnitud'] ?? '') === 'temperatura' ? 'selected' : '' ?>>Temperatura</option>
                <option value="volumen" <?= ($_POST['magnitud'] ?? '') === 'volumen' ? 'selected' : '' ?>>Volumen</option>
              </select>
            </div>

            <!-- CONTENEDOR DINÁMICO NIVEL 1 -->
            <div class="col-12" id="dynamicStep1"></div>

            <!-- CONTENEDOR DINÁMICO NIVEL 2 -->
            <div class="col-12" id="dynamicStep2"></div>

            <div class="col-12">
              <button type="submit" class="btn btn-primary">Guardar</button>
              <button type="reset" class="btn btn-outline-secondary" id="btnLimpiar">Limpiar</button>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>

  <!-- Bootstrap JS (incluye Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- LÓGICA DINÁMICA -->
  <script src="global-ui.js"></script>
  <script>
    // refs a contenedores
    const magnitudSelect = document.getElementById('magnitud');
    const step1 = document.getElementById('dynamicStep1');
    const step2 = document.getElementById('dynamicStep2');
    const btnLimpiar = document.getElementById('btnLimpiar');

    // helper: limpia un contenedor
    function clear(el) {
      el.innerHTML = '';
    }

    // helper: genera bloque HTML con innerHTML fácil
    // helper: genera bloque HTML con innerHTML fácil
    function renderStep1_temperatura() {
      step1.innerHTML = `
        <fieldset class="border rounded p-3">
          <legend class="float-none w-auto px-2 h6 mb-3">Tipo de instrumento</legend>

          <div class="form-check">
            <input class="form-check-input"
                  type="radio"
                  name="temp_instrumento"
                  id="temp_lv"
                  value="lv"
                  required>
            <label class="form-check-label" for="temp_lv">Líquido en vidrio</label>
          </div>

          <div class="form-check">
            <input class="form-check-input"
                  type="radio"
                  name="temp_instrumento"
                  id="temp_ld"
                  value="ld"
                  required>
            <label class="form-check-label" for="temp_ld">Lectura directa</label>
          </div>
        </fieldset>
      `;
    }

    // según la opción elegida en el step1 de temperatura, mostramos step2
    function renderStep2_temperatura(selectedTipo) {
      if (selectedTipo === 'lv') {
        step2.innerHTML = `
          <fieldset class="border rounded p-3">
            <legend class="float-none w-auto px-2 h6 mb-3">Tipo de termómetro</legend>

            <div class="form-check">
              <input class="form-check-input"
                    type="radio"
                    name="temp_termometro"
                    id="temp_lv_astm"
                    value="lv_astm"
                    required>
              <label class="form-check-label" for="temp_lv_astm">ASTM</label>
            </div>

            <div class="form-check">
              <input class="form-check-input"
                    type="radio"
                    name="temp_termometro"
                    id="temp_lv_grl"
                    value="lv_grl"
                    required>
              <label class="form-check-label" for="temp_lv_grl">General</label>
            </div>
          </fieldset>
        `;
      } else {
        step2.innerHTML = '';
      }
    }

    function renderStep1_volumen(selectedTipo) {
      step1.innerHTML = `
        <fieldset class="border rounded p-3">
          <legend class="float-none w-auto px-2 h6 mb-3">Tipo de instrumento</legend>

          <div class="form-check">
            <input class="form-check-input"
                  type="radio"
                  name="tipo_volumen"
                  id="vol_volumetrico"
                  value="volumetrico"
                  required>
            <label class="form-check-label" for="vol_volumetrico">Volumétrico</label>
          </div>

          <div class="form-check">
            <input class="form-check-input"
                  type="radio"
                  name="tipo_volumen"
                  id="vol_graduado"
                  value="graduado"
                  required>
            <label class="form-check-label" for="vol_graduado">Graduado</label>
          </div>
        </fieldset>
      `;
    }

    // SWITCH principal cuando cambia magnitud
    // SWITCH principal cuando cambia magnitud
    magnitudSelect.addEventListener('change', e => {
      const value = e.target.value;

      // siempre limpiar subniveles al cambiar magnitud
      clear(step1);
      clear(step2);

      switch (value) {
        case 'temperatura':
          renderStep1_temperatura();

          // escuchar selección dentro de step1 (temp_instrumento)
          step1.addEventListener('change', evt => {
            if (evt.target && evt.target.name === 'temp_instrumento') {
              renderStep2_temperatura(evt.target.value);
            }
          });

          break;

        case 'densidad':
          step1.innerHTML = `
            
          `;
          // sin step2
          break;

        case 'presion':
          
          step1.innerHTML = `
         
          `;
          break;

        case 'volumen':
          renderStep1_volumen();

          // escuchar selección dentro de step1 (temp_instrumento)
          step1.addEventListener('change', evt => {
            if (evt.target && evt.target.name === 'temp_instrumento') {
              renderStep2_temperatura(evt.target.value);
            }
          });
          break;

        default:
          // nada seleccionado
          break;
      }
    });

    // Limpiar todo dinámico cuando el usuario da "Limpiar"
    btnLimpiar.addEventListener('click', () => {
      clear(step1);
      clear(step2);
      magnitudSelect.value = "";
    });
  </script>
</body>
</html>
