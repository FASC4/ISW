<?php
// ag_clientes.php
// Requiere que exista /includes/init.php con la conexión PDO ($pdo) a la BD `cotizador_msv`.
require __DIR__ . '/includes/init.php';

$alerta = null;
$alertaTipo = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Normalización
  $compania = trim($_POST['compania'] ?? '');
  $telefono = trim($_POST['telefono'] ?? '');
  $contacto = trim($_POST['contacto'] ?? '');
  $email    = trim($_POST['email'] ?? '');
  $rfc      = strtoupper(trim($_POST['rfc'] ?? ''));
  $calle    = trim($_POST['calle'] ?? '');
  $numero   = trim($_POST['numero'] ?? '');
  $colonia  = trim($_POST['colonia'] ?? '');
  $estado   = trim($_POST['estado'] ?? '');
  $pais     = trim($_POST['pais'] ?? '');
  $cp       = trim($_POST['cp'] ?? '');

  // Validaciones servidor
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $alerta = 'Correo electrónico inválido.';
    $alertaTipo = 'danger';
  } elseif (!preg_match('/^[0-9+\-\s()]{7,20}$/', $telefono)) {
    $alerta = 'Teléfono inválido (usa sólo números, espacios y símbolos + - ( ) ).';
    $alertaTipo = 'danger';
  } //elseif (!preg_match('/^([A-ZÑ&]{3,4})(\d{6})([A-Z\d]{2})([A\d])$/u', $rfc)) {
    //$alerta = 'RFC inválido. Debe ser de 12 o 13 caracteres en mayúsculas.';
    //$alertaTipo = 'danger';
    elseif ($compania==='' || $contacto==='' || $calle==='' || $numero==='' || $colonia==='' || $estado==='' || $pais==='' || $cp==='') {
    $alerta = 'Completa todos los campos requeridos.';
    $alertaTipo = 'danger';
  } else {
    try {
      $sql = "INSERT INTO clientes
        (compania, telefono, contacto, email, rfc, calle, numero, colonia, estado, pais, cp)
        VALUES
        (:compania, :telefono, :contacto, :email, :rfc, :calle, :numero, :colonia, :estado, :pais, :cp)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ':compania' => $compania,
        ':telefono' => $telefono,
        ':contacto' => $contacto,
        ':email'    => $email,
        ':rfc'      => $rfc,
        ':calle'    => $calle,
        ':numero'   => $numero,
        ':colonia'  => $colonia,
        ':estado'   => $estado,
        ':pais'     => $pais,
        ':cp'       => $cp,
      ]);

      $alerta = 'Cliente guardado correctamente.';
      $alertaTipo = 'success';

      // Limpiar POST para vaciar formulario tras guardar
      $_POST = [];
    } catch (PDOException $e) {
      if ((int)$e->getCode() === 23000) {
        $alerta = 'Ya existe un cliente con ese RFC.';
        $alertaTipo = 'warning';
      } else {
        $alerta = 'Error al guardar el cliente.';
        $alertaTipo = 'danger';
      }
    }
  }
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
            <li class="nav-item"><a class="nav-link active" href="ag_clientes.php">Agregar</a></li>
            <li class="nav-item"><a class="nav-link" href="ed_clientes.php">Editar</a></li>
          </ul>
        </div>

        <a class="nav-link mt-2" href="index.php">Cerrar Sesión</a>
      </aside>

      <!-- Panel derecho -->
      <main class="col-12 col-sm-8 col-md-9 col-lg-10 content-wrap">
        <div class="content">
          <h1 class="h5 mb-3">Datos del cliente</h1>

          <?php if (!empty($alerta)): ?>
            <div class="alert alert-<?= htmlspecialchars($alertaTipo, ENT_QUOTES, 'UTF-8') ?> mb-3">
              <?= htmlspecialchars($alerta, ENT_QUOTES, 'UTF-8') ?>
            </div>
          <?php endif; ?>

          <!-- FORMULARIO CON CAMPOS DE ENTRADA -->
          <form class="row gy-3 gx-3" method="POST" action="" novalidate>
            <div class="col-12 col-md-6">
              <label for="compania" class="form-label">Nombre de la compañía</label>
              <input id="compania" name="compania" type="text" class="form-control"
                     placeholder="Ej. MSV Metrología" required
                     value="<?= htmlspecialchars($_POST['compania'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="col-12 col-md-6">
              <label for="telefono" class="form-label">Teléfono</label>
              <input id="telefono" name="telefono" type="tel" class="form-control"
                     placeholder="Ej. 222-123-4567"
                     pattern="^[0-9+\-\s()]{7,20}$"
                     title="Usa solo números, espacios y símbolos + - ( )" required
                     value="<?= htmlspecialchars($_POST['telefono'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="col-12 col-md-6">
              <label for="contacto" class="form-label">Nombre de contacto</label>
              <input id="contacto" name="contacto" type="text" class="form-control"
                     placeholder="Ej. Juan Pérez" required
                     value="<?= htmlspecialchars($_POST['contacto'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="col-12 col-md-6">
              <label for="email" class="form-label">Correo Electronico</label>
              <input id="email" name="email" type="email" class="form-control"
                     placeholder="cliente@empresa.com" required
                     value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="col-12 col-md-6">
              <label for="rfc" class="form-label">RFC</label>
              <input
                id="rfc"
                name="rfc"
                type="text"
                class="form-control"
                placeholder="Ej. ABCD8012311H0"
                required
                style="text-transform:uppercase"
                value="<?= htmlspecialchars($_POST['rfc'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
              >
            </div>

            <!-- DIRECCIÓN FÍSICA (separada) -->
            <div class="col-12">
              <h2 class="h6 mb-2">Dirección física</h2>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
              <label for="calle" class="form-label">Calle</label>
              <input
                id="calle"
                name="calle"
                type="text"
                class="form-control"
                placeholder="Ej. 4 Sur"
                autocomplete="address-line1"
                required
                value="<?= htmlspecialchars($_POST['calle'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
              >
            </div>

            <div class="col-6 col-md-3 col-lg-2">
              <label for="numero" class="form-label">Número</label>
              <input
                id="numero"
                name="numero"
                type="text"
                class="form-control"
                placeholder="310"
                autocomplete="address-line2"
                required
                value="<?= htmlspecialchars($_POST['numero'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
              >
            </div>

            <div class="col-12 col-md-6 col-lg-4">
              <label for="colonia" class="form-label">Colonia</label>
              <input
                id="colonia"
                name="colonia"
                type="text"
                class="form-control"
                placeholder="Col. Centro"
                required
                value="<?= htmlspecialchars($_POST['colonia'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
              >
            </div>

            <div class="col-12 col-md-6 col-lg-4">
              <label for="estado" class="form-label">Estado</label>
              <input
                id="estado"
                name="estado"
                type="text"
                class="form-control"
                placeholder="Puebla"
                required
                value="<?= htmlspecialchars($_POST['estado'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
              >
            </div>

            <div class="col-12 col-md-6 col-lg-4">
              <label for="pais" class="form-label">País</label>
              <input
                id="pais"
                name="pais"
                type="text"
                class="form-control"
                placeholder="México"
                required
                value="<?= htmlspecialchars($_POST['pais'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
              >
            </div>

            <div class="col-6 col-md-3 col-lg-2">
              <label for="cp" class="form-label">Código postal</label>
              <input
                id="cp"
                name="cp"
                type="text"
                class="form-control"
                placeholder="72000"
                pattern="^[0-9]{4,10}$"
                title="Sólo números"
                required
                value="<?= htmlspecialchars($_POST['cp'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
              >
            </div>

            <!-- Acciones -->
            <div class="col-12">
              <button type="submit" class="btn btn-primary">Guardar</button>
              <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>

  <!-- Bootstrap JS (incluye Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
