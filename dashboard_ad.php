<?php require __DIR__ . '/includes/init.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Cotizador MSV</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="global-ui.js"></script>
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
        <div class="content">
          <h1 class="h5 mb-3">Bienvenido</h1>

          
        </div>
      </main>
    </div>
  </div>

  <!-- Bootstrap JS (incluye Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
