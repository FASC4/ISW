<?php require __DIR__ . '/includes/init.php'; 

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
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Cotizador MSV</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Tu CSS -->
  <link rel="stylesheet" href="style.css">
  <style>
    .sidebar-toggle { position: fixed; left: 8px; top: 8px; z-index: 1200; }
    .layout.collapsed .sidebar { width: 68px !important; overflow: hidden; }
    .client-item { align-items: center; }
    .client-actions .btn { padding: .25rem .45rem; border-radius: .35rem; }
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
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center gap-2">
              <h1 class="h5 mb-0">Clientes</h1>
              <span class="badge text-bg-secondary" id="clientCount"><?= count($clientes) ?></span>
            </div>
          </div>

          <!-- Buscador local -->
          <div class="input-group mb-3">
            <span class="input-group-text" id="lblBuscar">Buscar</span>
            <input type="search" class="form-control" id="searchInput" placeholder="Filtra por nombre..." aria-describedby="lblBuscar">
          </div>

          <!-- Listado de clientes (solo lectura) -->
          <div class="list-group" id="clientList">
            <?php foreach ($clientes as $cliente): ?>
                <div class="list-group-item d-flex justify-content-between client-item">
                    <div class="fw-semibold"><?= htmlspecialchars($cliente['compania'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="d-flex gap-2 client-actions">
                        <!-- Botón de visualizar -->
                        <a href="#" class="btn btn-sm btn-outline-info" title="Ver detalles" aria-label="Visualizar <?= htmlspecialchars($cliente['compania'], ENT_QUOTES, 'UTF-8') ?>" data-id="<?= $cliente['id'] ?>" onclick="mostrarDetalles(<?= $cliente['id'] ?>)">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
          </div>

        </div>
      </main>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Scripts: colapsar otros submenús + toggle sidebar + filtro local -->
  <script>
    // Contador y filtro local de clientes
    (function () {
      const list = document.getElementById('clientList');
      const items = Array.from(list.querySelectorAll('.list-group-item'));
      const countEl = document.getElementById('clientCount');
      const search = document.getElementById('searchInput');

      function updateCount() {
        const visibles = items.filter(it => it.style.display !== 'none');
        countEl.textContent = visibles.length;
      }

      // Inicial
      updateCount();

      // Filtro por texto
      search.addEventListener('input', () => {
        const q = search.value.trim().toLowerCase();
        items.forEach(it => {
          const name = it.querySelector('.fw-semibold').textContent.trim().toLowerCase();
          it.style.display = name.includes(q) ? '' : 'none';
        });
        updateCount();
      });
    })();
  </script>
</body>
</html>
