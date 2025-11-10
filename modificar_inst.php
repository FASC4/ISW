<?php
require __DIR__ . '/includes/init.php';

// Inicialización de alerta
$alerta = null;
$alertaTipo = 'success';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir los datos del formulario
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $alcance = $_POST['alcance'];
    $unidades = $_POST['unidades'];
    $puntos_minimos = $_POST['puntos_minimos'];
    $magnitud = $_POST['magnitud'];

    try {
        // Actualizar el instrumento en la base de datos
        $sql = "UPDATE instrumentos SET nombre = ?, marca = ?, modelo = ?, alcance = ?, unidades = ?, puntos_minimos = ?, magnitud = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $marca, $modelo, $alcance, $unidades, $puntos_minimos, $magnitud, $id]);

        $alerta = 'Instrumento actualizado correctamente.';
        $alertaTipo = 'success';
        // Redirigir a la página de gestión de instrumentos (ed_inst.php)
        header('Location: ed_inst.php');
        exit();
    } catch (PDOException $e) {
        $alerta = 'Error al actualizar el instrumento: ' . $e->getMessage();
        $alertaTipo = 'danger';
    }
} else {
    // Obtener los datos del instrumento para editar
    $id = $_GET['id'];
    $instrumento = null;
    try {
        $sql = "SELECT * FROM instrumentos WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $instrumento = $stmt->fetch();
    } catch (PDOException $e) {
        $alerta = 'Error al cargar los datos del instrumento: ' . $e->getMessage();
        $alertaTipo = 'danger';
    }
}

// Si no se encuentra el instrumento, redirigir
if (!$instrumento) {
    header('Location: ed_inst.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Editar Instrumento - Cotizador MSV</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Tu CSS -->
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container mt-4">
    <h1>Editar Instrumento</h1>

    <!-- Mensaje de alerta -->
    <?php if (!empty($alerta)): ?>
      <div class="alert alert-<?= htmlspecialchars($alertaTipo, ENT_QUOTES, 'UTF-8') ?> mb-3">
        <?= htmlspecialchars($alerta, ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>

    <!-- Formulario de edición -->
    <form action="modificar_inst.php" method="POST">
      <input type="hidden" name="id" value="<?= htmlspecialchars($instrumento['id'], ENT_QUOTES, 'UTF-8') ?>">

      <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($instrumento['nombre'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="mb-3">
        <label for="marca" class="form-label">Marca</label>
        <input type="text" class="form-control" id="marca" name="marca" value="<?= htmlspecialchars($instrumento['marca'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="mb-3">
        <label for="modelo" class="form-label">Modelo</label>
        <input type="text" class="form-control" id="modelo" name="modelo" value="<?= htmlspecialchars($instrumento['modelo'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="mb-3">
        <label for="alcance" class="form-label">Alcance</label>
        <input type="text" class="form-control" id="alcance" name="alcance" value="<?= htmlspecialchars($instrumento['alcance'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="mb-3">
        <label for="unidades" class="form-label">Unidades</label>
        <input type="text" class="form-control" id="unidades" name="unidades" value="<?= htmlspecialchars($instrumento['unidades'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="mb-3">
        <label for="puntos_minimos" class="form-label">Puntos Mínimos</label>
        <input type="text" class="form-control" id="puntos_minimos" name="puntos_minimos" value="<?= htmlspecialchars($instrumento['puntos_minimos'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="mb-3">
        <label for="magnitud" class="form-label">Magnitud</label>
        <input type="text" class="form-control" id="magnitud" name="magnitud" value="<?= htmlspecialchars($instrumento['magnitud'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <!-- Botones: Guardar y Cancelar -->
      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="ed_inst.php" class="btn btn-secondary">Cancelar</a>
      </div>
    </form>
  </div>

  <!-- Bootstrap JS (incluye Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
