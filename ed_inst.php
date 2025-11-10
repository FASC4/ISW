<?php
require __DIR__ . '/includes/init.php';

// Inicialización de alerta
$alerta = null;
$alertaTipo = 'success';

// Recibir los filtros de búsqueda
$nombreFiltro = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$marcaFiltro = isset($_GET['marca']) ? $_GET['marca'] : '';
$modeloFiltro = isset($_GET['modelo']) ? $_GET['modelo'] : '';
$magnitudFiltro = isset($_GET['magnitud']) ? $_GET['magnitud'] : '';

// Consultar los instrumentos de la base de datos con los filtros
$instrumentos = [];
try {
    $sql = "SELECT id, nombre, marca, modelo FROM instrumentos WHERE 1=1";

    // Agregar los filtros a la consulta SQL
    if ($nombreFiltro) {
        $sql .= " AND nombre LIKE :nombre";
    }
    if ($marcaFiltro) {
        $sql .= " AND marca LIKE :marca";
    }
    if ($modeloFiltro) {
        $sql .= " AND modelo LIKE :modelo";
    }
    if ($magnitudFiltro) {
        $sql .= " AND magnitud LIKE :magnitud";
    }

    $sql .= " ORDER BY nombre";

    $stmt = $pdo->prepare($sql);

    // Asignar valores a los parámetros de la consulta
    if ($nombreFiltro) {
        $stmt->bindValue(':nombre', '%' . $nombreFiltro . '%');
    }
    if ($marcaFiltro) {
        $stmt->bindValue(':marca', '%' . $marcaFiltro . '%');
    }
    if ($modeloFiltro) {
        $stmt->bindValue(':modelo', '%' . $modeloFiltro . '%');
    }
    if ($magnitudFiltro) {
        $stmt->bindValue(':magnitud', '%' . $magnitudFiltro . '%');
    }

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
  <title>Cotizador MSV — Instrumentos</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Tu CSS -->
  <link rel="stylesheet" href="style.css">
  <style>
    .instrument-item { align-items: center; }
    .instrument-meta { font-size:.9rem; color:#6c757d; }
    .instrument-actions .btn { padding:.25rem .45rem; border-radius:.35rem; }
    .sidebar-toggle { position: fixed; left: 8px; top: 8px; z-index: 1200; }
    .layout.collapsed .sidebar { width: 68px !important; overflow: hidden; }
  </style>

  <script src="global-ui.js"></script>
</head>
<body>
  <!-- Botón flotante para colapsar/expandir sidebar -->
  <button class="sidebar-toggle btn btn-light shadow-sm" type="button" aria-label="Alternar menú" aria-expanded="true">⟨</button>

  <div class="container-fluid layout" id="layoutRoot">
    <div class="row g-0">
      <aside class="col-12 col-sm-4 col-md-3 col-lg-2 sidebar p-3">
        <!-- Historial (link simple) -->
        <a class="nav-link mb-2 active" href="dashboard_ad.php">Inicio</a>

        <!-- Historial (link simple) -->
        <a class="nav-link mb-2 active" href="historial_ad.php">Historial</a>

        <!-- Historial (link simple) -->
        <a class="nav-link mb-2 active" href="ad_usuarios.php">Administrar Usuarios</a>

        <!-- Clientes (colapsable) -->
         <!-- Historial (link simple) -->
        <a class="nav-link mb-2" href="ver_clientes.php">Ver clientes</a>

        <!-- Instrumentos (colapsable) -->
        <button class="btn btn-toggle mb-2 w-100" data-bs-toggle="collapse" data-bs-target="#instrumentos" aria-expanded="false">
          Instrumentos
        </button>
        <div class="collapse" id="instrumentos">
          <ul class="nav flex-column submenu mb-2">
            <li class="nav-item"><a class="nav-link" href="ag_inst.php">Agregar</a></li>
            <li class="nav-item"><a class="nav-link" href="ed_inst.php">Gestionar</a></li>
          </ul>
        </div>

        <!-- Cerrar sesión -->
        <a class="nav-link mt-2" href="index.php">Cerrar Sesión</a>
      </aside>

      <!-- Panel derecho -->
      <main class="col-12 col-sm-8 col-md-9 col-lg-10 content-wrap">
        <div class="content p-4">
          <h1 class="h5 mb-4">Instrumentos</h1>

          <!-- Filtro de búsqueda -->
          <form method="GET" action="ed_inst.php" class="mb-4">
            <div class="row g-3">
              <div class="col-md-3">
                <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($nombreFiltro, ENT_QUOTES, 'UTF-8') ?>" placeholder="Buscar por nombre">
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" name="marca" value="<?= htmlspecialchars($marcaFiltro, ENT_QUOTES, 'UTF-8') ?>" placeholder="Buscar por marca">
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" name="modelo" value="<?= htmlspecialchars($modeloFiltro, ENT_QUOTES, 'UTF-8') ?>" placeholder="Buscar por modelo">
              </div>
              <div class="col-md-3">
                <!-- Lista desplegable para el filtro de magnitud -->
                <select class="form-control" name="magnitud">
                  <option value="">Buscar por magnitud</option>
                  <option value="Temperatura" <?= $magnitudFiltro == 'Temperatura' ? 'selected' : '' ?>>Temperatura</option>
                  <option value="Densidad" <?= $magnitudFiltro == 'Densidad' ? 'selected' : '' ?>>Densidad</option>
                  <option value="Presión" <?= $magnitudFiltro == 'Presión' ? 'selected' : '' ?>>Presión</option>
                  <option value="Volumen" <?= $magnitudFiltro == 'Volumen' ? 'selected' : '' ?>>Volumen</option>
                </select>
              </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Buscar</button>
          </form>

          <!-- Mensaje de alerta -->
          <?php if (!empty($alerta)): ?>
            <div class="alert alert-<?= htmlspecialchars($alertaTipo, ENT_QUOTES, 'UTF-8') ?> mb-3">
              <?= htmlspecialchars($alerta, ENT_QUOTES, 'UTF-8') ?>
            </div>
          <?php endif; ?>

          <!-- Listado de instrumentos -->
          <div class="list-group">
            <?php foreach ($instrumentos as $instrumento): ?>
              <div class="list-group-item d-flex justify-content-between instrument-item">
                <div>
                  <div class="fw-semibold"><?= htmlspecialchars($instrumento['nombre'], ENT_QUOTES, 'UTF-8') ?></div>
                  <div class="instrument-meta">Marca: <?= htmlspecialchars($instrumento['marca'], ENT_QUOTES, 'UTF-8') ?></div>
                </div>
                <div class="d-flex gap-2 instrument-actions">
                  <!-- Enviar solicitud de eliminación -->
                  <a href="eliminar_inst.php?id=<?= $instrumento['id'] ?>" class="btn btn-sm btn-outline-danger" title="Eliminar" aria-label="Eliminar <?= htmlspecialchars($instrumento['nombre'], ENT_QUOTES, 'UTF-8') ?>" data-id="<?= $instrumento['id'] ?>">
                    <i class="bi bi-trash"></i>
                  </a>
                  <!-- Botón de edición (ahora redirige a modificar_inst.php con el id) -->
                  <a href="modificar_inst.php?id=<?= $instrumento['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Editar" aria-label="Editar <?= htmlspecialchars($instrumento['nombre'], ENT_QUOTES, 'UTF-8') ?>" data-id="<?= $instrumento['id'] ?>">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <!-- Botón de ver detalles -->
                  <button class="btn btn-sm btn-outline-info" title="Ver detalles" aria-label="Ver <?= htmlspecialchars($instrumento['nombre'], ENT_QUOTES, 'UTF-8') ?>" data-bs-toggle="modal" data-bs-target="#modalVerInstrumento" data-id="<?= $instrumento['id'] ?>">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Modal para ver información del instrumento -->
  <div class="modal fade" id="modalVerInstrumento" tabindex="-1" aria-labelledby="modalVerInstrumentoLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalVerInstrumentoLabel">Detalles del Instrumento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body" id="instrumentoDetalle">
          <!-- Aquí se cargará la información del instrumento -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS (incluye Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Scripts: acciones simuladas + toggle sidebar -->
  <script>
    // Ver detalles de un instrumento
    document.querySelectorAll('.instrument-actions .btn-outline-info').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const id = e.target.closest('button').dataset.id;

        // Hacer una solicitud AJAX para obtener los detalles del instrumento
        fetch(`ver_inst.php?id=${id}`)
          .then(response => response.json())
          .then(data => {
            // Mostrar los detalles en el modal
            const detalleHTML = `
              <p><strong>Nombre:</strong> ${data.nombre}</p>
              <p><strong>Marca:</strong> ${data.marca}</p>
              <p><strong>Modelo:</strong> ${data.modelo}</p>
              <p><strong>Alcance:</strong> ${data.alcance}</p>
              <p><strong>Unidades:</strong> ${data.unidades}</p>
              <p><strong>Puntos a calibrar:</strong> ${data.puntos_minimos}</p>
              <p><strong>Magnitud:</strong> ${data.magnitud}</p>
            `;
            document.getElementById('instrumentoDetalle').innerHTML = detalleHTML;
          })
          .catch(error => console.error('Error al cargar los detalles:', error));
      });
    });
  </script>
</body>
</html>
