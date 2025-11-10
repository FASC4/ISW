<?php
require __DIR__ . '/includes/init.php';

// Consultar los clientes de la base de datos
$clientes = [];
try {
    $sql = "SELECT id, compania FROM clientes ORDER BY compania";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $clientes = $stmt->fetchAll();
} catch (PDOException $e) {
    $alerta = 'Error al cargar los clientes: ' . $e->getMessage();
    $alertaTipo = 'danger';
}
$instrumentos = [];
try {
    $sql = "SELECT id, nombre FROM instrumentos ORDER BY nombre";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $instrumentos = $stmt->fetchAll();
} catch (PDOException $e) {
    $alerta = 'Error al cargar los instrumentos: ' . $e->getMessage();
    $alertaTipo = 'danger';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Cotizador MSV — Generar cotización</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Tu CSS -->
  <link rel="stylesheet" href="style.css">
  <style>
    .sidebar-toggle{position:fixed;left:8px;top:8px;z-index:1200}
    .layout.collapsed .sidebar{width:68px!important;overflow:hidden}
    .inst-row .btn{padding:.25rem .45rem}
    .inst-row .form-control,.inst-row .form-select{min-width:140px}
  </style>

  <script src="global-ui.js"></script>
</head>
<body>
  <!-- Botón flotante para colapsar/expandir sidebar -->
  <button class="sidebar-toggle btn btn-light shadow-sm" type="button" aria-label="Alternar menú" aria-expanded="true">⟨</button>

  <div class="container-fluid layout" id="layoutRoot">
    <div class="row g-0">
      <!-- Sidebar -->
      <aside class="col-12 col-sm-4 col-md-3 col-lg-2 sidebar p-3">
        <a class="nav-link mb-2" href="dashboard_ve.php">Inicio</a>
        <button class="btn btn-toggle mb-2 w-100" data-bs-toggle="collapse" data-bs-target="#cotizaciones" aria-expanded="true">
          Cotizaciones
        </button>
        <div class="collapse show" id="cotizaciones">
          <ul class="nav flex-column submenu mb-2">
            <li class="nav-item"><a class="nav-link" href="generar.php">Generar</a></li>
            <li class="nav-item"><a class="nav-link" href="gestionar.php">Gestionar</a></li>
          </ul>
        </div>

        <a class="nav-link mb-2" href="historial_ve.php">Historial</a>

        <button class="btn btn-toggle mb-2 w-100" data-bs-toggle="collapse" data-bs-target="#clientes" aria-expanded="false">
          Clientes
        </button>
        <div class="collapse" id="clientes">
          <ul class="nav flex-column submenu mb-2">
            <li class="nav-item"><a class="nav-link" href="ag_clientes.php">Agregar</a></li>
            <li class="nav-item"><a class="nav-link" href="ed_clientes.php">Editar</a></li>
          </ul>
        </div>

        <a class="nav-link mt-2" href="index.php">Cerrar Sesión</a>
      </aside>

      <!-- Panel derecho -->
      <main class="col-12 col-sm-8 col-md-9 col-lg-10 content-wrap">
        <div class="content p-4">
          <h1 class="h5 mb-4">Generar cotización</h1>

          <form id="formCotizacion" class="row gy-4 gx-3" novalidate>
            <!-- Cliente -->
            <div class="col-12 col-md-6">
              <label for="cliente" class="form-label">Cliente</label>
              <select id="cliente" name="cliente" class="form-select" required>
                <option value="" selected disabled>Selecciona un cliente</option>
                <?php foreach ($clientes as $cliente): ?>
                  <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['compania'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Tiempo de entrega -->
            <div class="col-12 col-md-6">
              <label for="tiempo_entrega" class="form-label">Tiempo de entrega</label>
              <select id="tiempo_entrega" name="tiempo_entrega" class="form-select" required>
                <option value="" selected disabled>Selecciona</option>
                <option value="normal">Normal</option>
                <option value="urgente">Urgente</option>
                <option value="express">Express</option>
              </select>
            </div>

            <!-- Tipo de servicio -->
            <div class="col-12 col-md-6">
              <label for="tipo_servicio" class="form-label">Tipo de servicio</label>
              <select id="tipo_servicio" name="tipo_servicio" class="form-select" required>
                <option value="" selected disabled>Selecciona</option>
                <option>Calibración</option>
              </select>
            </div>

            <!-- Vigencia -->
            <div class="col-12 col-md-6">
              <label for="vigencia" class="form-label">Vigencia</label>
              <select id="vigencia" name="vigencia" class="form-select" required>
                <option value="" selected disabled>Selecciona vigencia</option>
                <option>7 días</option>
                <option>15 días</option>
                <option>30 días</option>
                <option>60 días</option>
              </select>
            </div>

            <!-- Instrumentos (lista dinámica) -->
            <div class="col-12">
              <label class="form-label mb-0">Instrumentos</label>

              <div id="instrumentosList" class="mt-3"></div>

              <div class="mt-2">
                <button type="button" id="btnAddInst" class="btn btn-sm btn-primary">
                  <i class="bi bi-plus-lg"></i> Agregar instrumento
                </button>
              </div>

              <!-- Plantilla oculta -->
              <template id="tplInstRow">
                <div class="row g-2 inst-row pb-2 border-bottom pt-2">
                  <!-- Instrumentos -->
                  <div class="col-12 col-lg-5">
                    <div class="row g-2">
                       <!-- Magnitud -->
                      <div class="col-12 col-md-6">
                        <label for="magnitud" class="form-label">Magnitud</label>
                        <select id="magnitud" name="magnitud" class="form-select" required>
                          <option value="" selected disabled>Selecciona una magnitud</option>
                          <option value="temperatura">Temperatura</option>
                          <option value="densidad">Densidad</option>
                          <option value="volumen">Volumen</option>
                          <option value="presion">Presión</option>
                        </select>
                      </div>

                      <div class="col-12 col-md-6">
                        <label for="instrumento" class="form-label">Instrumento</label>
                        <select id="instrumento" name="instrumento" class="form-select" required>
                          <option value="" selected disabled>Selecciona un instrumento</option>
                          <!-- Los instrumentos se cargarán aquí mediante AJAX -->
                        </select>
                      </div>

                      <div class="col-12">
                        <label class="form-label">Puntos a calibrar</label>
                        <input
                          type="number"
                          class="form-control inst-punto"
                          min="1"
                          step="1"
                          value="1"
                          required
                        >
                      </div>
                    </div>
                  </div>

                  <div class="col-6 col-lg-3">
                    <label class="form-label"># de serie</label>
                    <input type="text" class="form-control inst-serie" placeholder="Ej. SN-12345" maxlength="40" required>
                  </div>

                  <div class="col-6 col-lg-2">
                    <label class="form-label">Identificador</label>
                    <input type="text" class="form-control inst-ident" placeholder="Ej. INV-009" maxlength="30" required>
                  </div>

                  <div class="col-6 col-lg-1 qty-col">
                    <label class="form-label">Cantidad</label>
                    <input type="number" class="form-control inst-cantidad" min="1" step="1" value="1" required>
                  </div>

                  <div class="col-auto trash-col ms-auto text-end d-grid align-self-end">
                    <button type="button" class="btn btn-outline-danger btnRemove mt-4 mt-lg-0" title="Eliminar fila">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>

                  <div class="col-12 mt-2">
                    <label class="form-label">Puntos a calibrar (lista / rangos / valores)</label>
                    <textarea
                      class="form-control inst-detalle-puntos"
                      rows="3"
                      maxlength="400"
                      placeholder="Ej. 0°C, 25°C, 50°C, 75°C, 100°C
o 0 bar, 5 bar, 10 bar
o 1 g, 50 g, 100 g, 200 g"
                      required
                    ></textarea>
                    <div class="form-text">
                      Especifica los valores objetivo donde se va a verificar el equipo.
                    </div>
                  </div>
                </div>
              </template>
            </div>

            <!-- Tipo de persona -->
            <div class="col-12 col-md-6">
              <label for="tipo_persona" class="form-label">Tipo de persona</label>
              <select id="tipo_persona" name="tipo_persona" class="form-select" required>
                <option value="" selected disabled>Selecciona tipo</option>
                <option value="fisica">Persona física</option>
                <option value="moral">Persona moral</option>
              </select>
            </div>

            <!-- Acciones -->
            <div class="col-12 d-flex gap-2">
              <button type="submit" class="btn btn-success">Guardar cotización</button>
              <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Lógica de UI -->
  <script>
    // ---- Instrumentos dinámicos ----
    const list = document.getElementById('instrumentosList');
    const tpl  = document.getElementById('tplInstRow');
    const addBtn = document.getElementById('btnAddInst');

    function initRow(scope) {
      // Eliminar fila con animación
      scope.querySelector('.btnRemove').addEventListener('click', (e) => {
        const row = e.currentTarget.closest('.inst-row');
        row.style.transition = 'opacity .2s ease, height .2s ease, margin .2s ease';
        row.style.opacity = '0';
        row.style.height = '0';
        row.style.margin = '0';
        setTimeout(() => row.remove(), 220);
      });
    }

    function addInstrumentRow() {
      const nodeFrag = tpl.content.cloneNode(true);
      const rowEl = nodeFrag.querySelector('.inst-row');
      initRow(rowEl);
      list.appendChild(nodeFrag);
    }

    // Agregar primera fila por defecto
    addInstrumentRow();
    addBtn.addEventListener('click', addInstrumentRow);

    // Validación simple
    document.getElementById('formCotizacion').addEventListener('submit', (e) => {
      if (!e.target.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
        e.target.classList.add('was-validated');
        return;
      }
      e.preventDefault();
      alert('Cotización guardada (simulado).');
    });
  </script>
  <!-- Lógica de UI -->
  <script>
    // Filtrar instrumentos según magnitud seleccionada
    document.getElementById('magnitud').addEventListener('change', function() {
      const magnitud = this.value;

      // Realizar solicitud AJAX para obtener instrumentos filtrados
      fetch('get_instrumentos.php?magnitud=' + magnitud)
        .then(response => response.json())
        .then(data => {
          const instrumentoSelect = document.getElementById('instrumento');
          instrumentoSelect.innerHTML = '<option value="" selected disabled>Selecciona un instrumento</option>'; // Limpiar opciones previas

          if (data.error) {
            alert(data.error);
          } else {
            data.forEach(instrumento => {
              const option = document.createElement('option');
              option.value = instrumento.id;
              option.textContent = instrumento.nombre;
              instrumentoSelect.appendChild(option);
            });
          }
        })
        .catch(error => {
          alert('Error al cargar los instrumentos.');
        });
    });
  </script>
</body>
</html>
