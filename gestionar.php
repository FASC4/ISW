<?php require __DIR__ . '/includes/init.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Cotizador MSV</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons (para lápiz/bote) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Tu CSS -->
  <link rel="stylesheet" href="style.css">

  <style>
    /* mini ajustes locales para el historial embebido */
    .quote-item { align-items: center; }
    .quote-meta { font-size: .9rem; color: #6c757d; }
    .quote-actions .btn { padding: .25rem .45rem; border-radius: .35rem; }
  </style>

  <script src="global-ui.js"></script>
</head>
<body>
  <!-- Botón flotante para colapsar/expandir sidebar (todas las resoluciones) -->
  <button class="sidebar-toggle" type="button" aria-label="Alternar menú" aria-expanded="true">⟨</button>

  <div class="container-fluid layout" id="layoutRoot">
    <div class="row g-0">
      <!-- Sidebar -->
      <aside class="col-12 col-sm-4 col-md-3 col-lg-2 sidebar p-3">
        <a class="nav-link mb-2" href="dashboard_ve.php">Inicio</a>
        <!-- Cotizaciones (colapsable) -->
        <button class="btn btn-toggle mb-2 w-100" data-bs-toggle="collapse" data-bs-target="#cotizaciones" aria-expanded="true">
          Cotizaciones
        </button>
        <div class="collapse show" id="cotizaciones">
          <ul class="nav flex-column submenu mb-2">
            <li class="nav-item"><a class="nav-link" href="generar.php">Generar</a></li>
            <li class="nav-item"><a class="nav-link" href="gestionar.php">Gestionar</a></li>
          </ul>
        </div>

        <!-- Historial (link simple) -->
        <a class="nav-link mb-2" href="historial_ve.php">Historial</a>

        <!-- Clientes (colapsable) -->
        <button class="btn btn-toggle mb-2 w-100" data-bs-toggle="collapse" data-bs-target="#clientes" aria-expanded="false">
          Clientes
        </button>
        <div class="collapse" id="clientes">
          <ul class="nav flex-column submenu mb-2">
            <li class="nav-item"><a class="nav-link" href="ag_clientes.php">Agregar</a></li>
            <li class="nav-item"><a class="nav-link" href="ed_clientes.php">Editar</a></li>
          </ul>
        </div>

        <!-- Cerrar sesión -->
        <a class="nav-link mt-2" href="index.php">Cerrar Sesión</a>
      </aside>

      <!-- Panel derecho -->
      <main class="col-12 col-sm-8 col-md-9 col-lg-10 content-wrap">
        <div class="content p-4">
          <h1 class="h5 mb-3">Filtros</h1>

          <form class="row gy-3 gx-3">
            <div class="col-12 col-md-6">
              <label for="cliente" class="form-label">Cliente</label>
              <select id="cliente" class="form-select">
                <option selected disabled>Selecciona un cliente</option>
                <option>Cliente A</option>
                <option>Cliente B</option>
                <option>Cliente C</option>
              </select>
            </div>

            <div class="col-12 col-md-6">
              <label for="instrumento" class="form-label">Instrumento</label>
              <select id="instrumento" class="form-select">
                <option selected disabled>Selecciona un instrumento</option>
                <option>Calibrador</option>
                <option>Balanza</option>
                <option>Termómetro</option>
              </select>
            </div>

            <div class="col-12 col-md-6">
              <label for="estado" class="form-label">Estado</label>
              <select id="estado" class="form-select">
                <option selected disabled>Selecciona estado</option>
                <option>Pendiente</option>
                <option>En proceso</option>
                <option>Finalizada</option>
              </select>
            </div>

            <div class="col-12 col-md-6">
              <label for="rango" class="form-label">Rango de fecha</label>
              <select id="rango" class="form-select">
                <option selected disabled>Selecciona un rango</option>
                <option>Última semana</option>
                <option>Último mes</option>
                <option>Últimos 3 meses</option>
              </select>
            </div>

            <!-- Botón Buscar -->
            <div class="col-12 text-md-end">
              <button type="button" class="btn btn-primary px-4" id="btnBuscar">
                Buscar
              </button>
            </div>
          </form>

          <!-- Historial rápido (preview) -->
          <hr class="my-4"/>

          <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h6 mb-0">Resultados recientes</h2>
            <a class="small text-decoration-none" href="historial_ve.php">Ver todo →</a>
          </div>

          <div class="list-group" id="miniHistorial">
            <div class="list-group-item d-flex justify-content-between quote-item">
              <div>
                <div class="fw-semibold">Cotización 27 — Cliente A</div>
                <div class="quote-meta">Fecha: 2025-10-20 · Estado: En proceso</div>
              </div>
              <div class="d-flex gap-2 align-items-center quote-actions">
                <button class="btn btn-sm btn-outline-secondary" title="Editar cotización" aria-label="Editar Cotización 27" data-id="27">
                  <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" title="Eliminar cotización" aria-label="Eliminar Cotización 27" data-id="27">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>

            <div class="list-group-item d-flex justify-content-between quote-item">
              <div>
                <div class="fw-semibold">Cotización 26 — Cliente B</div>
                <div class="quote-meta">Fecha: 2025-10-18 · Estado: Finalizada</div>
              </div>
              <div class="d-flex gap-2 align-items-center quote-actions">
                <button class="btn btn-sm btn-outline-secondary" title="Editar cotización" aria-label="Editar Cotización 26" data-id="26">
                  <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" title="Eliminar cotización" aria-label="Eliminar Cotización 26" data-id="26">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>

            <div class="list-group-item d-flex justify-content-between quote-item">
              <div>
                <div class="fw-semibold">Cotización 25 — Cliente C</div>
                <div class="quote-meta">Fecha: 2025-10-15 · Estado: Pendiente</div>
              </div>
              <div class="d-flex gap-2 align-items-center quote-actions">
                <button class="btn btn-sm btn-outline-secondary" title="Editar cotización" aria-label="Editar Cotización 25" data-id="25">
                  <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" title="Eliminar cotización" aria-label="Eliminar Cotización 25" data-id="25">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
          </div>

        </div>
      </main>
    </div>
  </div>

  <!-- Bootstrap JS (incluye Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Acordeón suave + Toggle sidebar + acciones historial -->
  <script>
    // --- Acciones del historial (simulado igual que en historial_ve) ---
    // Eliminar
    document.querySelectorAll('#miniHistorial .btn-outline-danger').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        if (confirm('¿Eliminar la cotización #' + id + '? (simulado)')) {
          const item = btn.closest('.list-group-item');
          item.classList.add('fade-out');
          item.style.transition = 'opacity .25s ease, height .25s ease, margin .25s ease';
          item.style.opacity = '0';
          item.style.height = '0';
          item.style.margin = '0';
          setTimeout(() => item.remove(), 300);
        }
      });
    });

    // Editar
    document.querySelectorAll('#miniHistorial .btn-outline-secondary').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        alert('Editar cotización #' + id + ' (simulado).');
      });
    });
  </script>
</body>
</html>
