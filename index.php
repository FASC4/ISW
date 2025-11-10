<?php
// --- Bootstrap mínimo ---
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
date_default_timezone_set('America/Mexico_City');

// Si ya está logueado, mándalo a su dashboard
if (!empty($_SESSION['user']['rol'])) {
  if ($_SESSION['user']['rol'] === 'admin') {
    header('Location: dashboard_ad.php'); exit;
  }
  if ($_SESSION['user']['rol'] === 'vendedor') {
    header('Location: dashboard_ve.php'); exit;
  }
}

$error = '';

// --- Manejo del formulario ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = trim($_POST['username'] ?? '');
  $pass = trim($_POST['password'] ?? '');

  // Demo de credenciales (luego se cambia por DB)
  if ($user === 'admin' && $pass === '1234') {
    $_SESSION['user'] = ['username' => $user, 'rol' => 'admin'];
    header('Location: dashboard_ad.php'); exit;
  } elseif ($user === 'vendedor' && $pass === 'abcd') {
    $_SESSION['user'] = ['username' => $user, 'rol' => 'vendedor'];
    header('Location: dashboard_ve.php'); exit;
  } else {
    $error = 'Usuario o contraseña incorrectos';
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cotizador MSV</title>
  <link rel="stylesheet" href="login.css"/>
  <script src="login.js"></script>
  <style>
    .alert {background:#ffe1e1;color:#a40000;border:1px solid #f5b5b5;padding:.75rem;border-radius:.5rem;margin-bottom:1rem}
  </style>
</head>
<body>
  <div class="login-container">
    <?php if ($error): ?>
      <div class="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST" class="loginForm" onsubmit="return validarLogin()">
      <h2>Iniciar Sesión</h2>

      <div class="form-row">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required/>
      </div>

      <div class="form-row">
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required/>
      </div>

      <button type="submit" class="submit-btn">Ingresar</button>
    </form>
  </div>
</body>
</html>
