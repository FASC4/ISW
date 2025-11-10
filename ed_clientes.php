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
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Cotizador MSV — Clientes</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="global-ui.js"></script>

  <!-- Tu CSS -->
  <link rel="stylesheet" href="style.css">
  <style>
    .client-item { align-items: center; }
    .client-actions .btn { padding: .25rem .45rem; border-radius: .35rem; }
    .sidebar-toggle { position: fixed; left: 8px; top: 8px; z-index: 1200; }
    .layout.collapsed .sidebar { width: 68px !important; overflow: hidden; }
  </style>
</head>
<body>
  <!-- Botón flotante para colapsar/expandir sidebar -->
  <button class="sidebar-toggle btn btn-light shadow-sm" type="button" aria-label="Alternar menú" aria-expanded="true">⟨</button>

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
            <li class="nav-item"><a class="nav-link active" href="ed_clientes.php">Editar</a></li>
          </ul>
        </div>

        <!-- Cerrar sesión -->
        <a class="nav-link mt-2" href="index.php">Cerrar Sesión</a>
      </aside>

      <!-- Panel derecho -->
      <main class="col-12 col-sm-8 col-md-9 col-lg-10 content-wrap">
        <div class="content p-4">
          <h1 class="h5 mb-4">Clientes</h1>

          <!-- Mensaje de alerta -->
          <?php if (!empty($alerta)): ?>
            <div class="alert alert-<?= htmlspecialchars($alertaTipo, ENT_QUOTES, 'UTF-8') ?> mb-3">
              <?= htmlspecialchars($alerta, ENT_QUOTES, 'UTF-8') ?>
            </div>
          <?php endif; ?>

          <!-- Listado de clientes -->
<div class="list-group">
    <?php foreach ($clientes as $cliente): ?>
        <div class="list-group-item d-flex justify-content-between client-item">
            <div class="fw-semibold"><?= htmlspecialchars($cliente['compania'], ENT_QUOTES, 'UTF-8') ?></div>
            <div class="d-flex gap-2 client-actions">
                <!-- Botón de edición (redirige a modificar_cliente.php con el id) -->
                <a href="modificar_cliente.php?id=<?= $cliente['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Editar cliente" aria-label="Editar <?= htmlspecialchars($cliente['compania'], ENT_QUOTES, 'UTF-8') ?>" data-id="<?= $cliente['id'] ?>">
                    <i class="bi bi-pencil"></i>
                </a>
                <!-- Botón de eliminación -->
                <a href="#" class="btn btn-sm btn-outline-danger" title="Eliminar cliente" aria-label="Eliminar <?= htmlspecialchars($cliente['compania'], ENT_QUOTES, 'UTF-8') ?>" data-id="<?= $cliente['id'] ?>" onclick="confirmarEliminacion(<?= $cliente['id'] ?>)">
                    <i class="bi bi-trash"></i>
                </a>
                <!-- Botón de visualizar -->
                <a href="#" class="btn btn-sm btn-outline-info" title="Ver detalles" aria-label="Visualizar <?= htmlspecialchars($cliente['compania'], ENT_QUOTES, 'UTF-8') ?>" data-id="<?= $cliente['id'] ?>" onclick="mostrarDetalles(<?= $cliente['id'] ?>)">
                    <i class="bi bi-eye"></i>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal para mostrar detalles del cliente -->
<div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalleModalLabel">Detalles del Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detalleContenido">
                <!-- Detalles cargados por JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script JavaScript -->
<script>
    function mostrarDetalles(clienteId) {
        // Realizar una solicitud AJAX para obtener los detalles del cliente
        fetch('visualizar_cliente.php?id=' + clienteId)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    // Mostrar los detalles en el modal
                    const detalles = `
                        <p><strong>Compañía:</strong> ${data.compania}</p>
                        <p><strong>Contacto:</strong> ${data.contacto}</p>
                        <p><strong>Teléfono:</strong> ${data.telefono}</p>
                        <p><strong>Email:</strong> ${data.email}</p>
                        <p><strong>Dirección:</strong> ${data.rfc}</p>
                        <p><strong>Dirección:</strong> ${data.calle} ${data.numero}, ${data.colonia}, ${data.estado}, ${data.pais}, ${data.cp}</p>
                    `;
                    document.getElementById('detalleContenido').innerHTML = detalles;

                    // Mostrar el modal
                    const modal = new bootstrap.Modal(document.getElementById('detalleModal'));
                    modal.show();
                }
            })
            .catch(error => {
                alert('Error al cargar los detalles del cliente.');
            });
    }

    function confirmarEliminacion(clienteId) {
        // Mostrar un mensaje de confirmación
        if (confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
            // Si el usuario confirma, redirigir al archivo de eliminación
            window.location.href = 'eliminar_cliente.php?id=' + clienteId;
        }
    }
</script>

</body>
</html>
