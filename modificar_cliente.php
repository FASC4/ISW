<?php
require __DIR__ . '/includes/init.php';

// Verificar si se recibió un ID válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    $cliente = [];

    try {
        // Obtener los detalles del cliente desde la base de datos
        $sql = "SELECT * FROM clientes WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $cliente = $stmt->fetch();

        if (!$cliente) {
            // Si no se encuentra el cliente, redirigir
            header('Location: ed_clientes.php?error=Cliente no encontrado');
            exit;
        }
    } catch (PDOException $e) {
        // Error en la base de datos
        header('Location: ed_clientes.php?error=Error al cargar el cliente: ' . $e->getMessage());
        exit;
    }

    // Procesar el formulario cuando se envíe
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener datos del formulario
        $compania = $_POST['compania'];
        $contacto = $_POST['contacto'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $rfc = $_POST['rfc'];
        $calle = $_POST['calle'];
        $numero = $_POST['numero'];
        $colonia = $_POST['colonia'];
        $estado = $_POST['estado'];
        $pais = $_POST['pais'];
        $cp = $_POST['cp'];
        
        try {
            // Actualizar los detalles del cliente en la base de datos
            $sql = "UPDATE clientes SET compania = :compania, contacto = :contacto, telefono = :telefono, email = :email, rfc = :rfc, calle = :calle, numero = :numero, colonia = :colonia, estado = :estado, pais = :pais, cp = :cp WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':compania' => $compania,
                ':contacto' => $contacto,
                ':telefono' => $telefono,
                ':email' => $email,
                ':rfc' => $rfc,
                ':calle' => $calle,
                ':numero' => $numero,
                ':colonia' => $colonia,
                ':estado' => $estado,
                ':pais' => $pais,
                ':cp' => $cp,
                ':id' => $id
            ]);

            // Redirigir con mensaje de éxito
            header('Location: ed_clientes.php?message=Cliente actualizado con éxito');
            exit;
        } catch (PDOException $e) {
            // Error en la actualización
            $error = 'Error al actualizar el cliente: ' . $e->getMessage();
        }
    }
} else {
    // Si no se recibe un ID válido
    header('Location: ed_clientes.php?error=ID inválido');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Editar Cliente</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="global-ui.js"></script>

  <!-- Tu CSS -->
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container mt-5">
    <h1 class="h3">Editar Cliente</h1>

    <!-- Mensajes de error o éxito -->
    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php elseif (isset($_GET['message'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form action="" method="POST">
      <div class="mb-3">
        <label for="compania" class="form-label">Compañía</label>
        <input type="text" class="form-control" id="compania" name="compania" value="<?= htmlspecialchars($cliente['compania'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="mb-3">
        <label for="contacto" class="form-label">Contacto</label>
        <input type="text" class="form-control" id="contacto" name="contacto" value="<?= htmlspecialchars($cliente['contacto'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" class="form-control" id="telefono" name="telefono" value="<?= htmlspecialchars($cliente['telefono'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($cliente['email'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="mb-3">
        <label for="rfc" class="form-label">RFC</label>
        <input type="text" class="form-control" id="rfc" name="rfc" value="<?= htmlspecialchars($cliente['rfc'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="mb-3">
        <label for="calle" class="form-label">Calle</label>
        <input type="text" class="form-control" id="calle" name="calle" value="<?= htmlspecialchars($cliente['calle'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="mb-3">
        <label for="numero" class="form-label">Número</label>
        <input type="text" class="form-control" id="numero" name="numero" value="<?= htmlspecialchars($cliente['numero'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="mb-3">
        <label for="colonia" class="form-label">Colonia</label>
        <input type="text" class="form-control" id="colonia" name="colonia" value="<?= htmlspecialchars($cliente['colonia'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="mb-3">
        <label for="estado" class="form-label">Estado</label>
        <input type="text" class="form-control" id="estado" name="estado" value="<?= htmlspecialchars($cliente['estado'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="mb-3">
        <label for="pais" class="form-label">País</label>
        <input type="text" class="form-control" id="pais" name="pais" value="<?= htmlspecialchars($cliente['pais'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <div class="mb-3">
        <label for="cp" class="form-label">Código Postal</label>
        <input type="text" class="form-control" id="cp" name="cp" value="<?= htmlspecialchars($cliente['cp'], ENT_QUOTES, 'UTF-8') ?>" required>
      </div>

      <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
