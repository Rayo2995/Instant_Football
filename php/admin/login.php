<?php
require_once '../../config/auth.php';

// Si ya está logueado redirigir al inicio
if (isAdmin()) {
    header('Location: /Instant_Football/index.php');
    exit;
}

require_once '../../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario  = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_id']     = $admin['id_admin'];
        $_SESSION['admin_nombre'] = $admin['nombre'];
        $_SESSION['admin_usuario']= $admin['usuario'];
        header('Location: /Instant_Football/index.php');
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — InstantFootball</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../css/modulos.css">
</head>
<body>

<div class="bg-orbs">
  <div class="orb orb1"></div>
  <div class="orb orb2"></div>
  <div class="orb orb3"></div>
</div>

<div class="login-wrap">
  <div class="login-card glass">

    <div class="login-logo"><em>Instant</em>Football</div>
    <h1 class="login-titulo">Panel Admin</h1>
    <p class="login-sub">Ingresa tus credenciales para continuar</p>

    <?php if ($error): ?>
      <div class="alerta alerta-error">⚠️ <?= $error ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'acceso'): ?>
      <div class="alerta alerta-error">🔒 Debes iniciar sesión para acceder.</div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Usuario</label>
        <input type="text" name="usuario" required placeholder="admin"
               value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Contraseña</label>
        <input type="password" name="password" required placeholder="••••••••">
      </div>
      <button type="submit" class="btn-red" style="width:100%;justify-content:center;padding:0.85rem;">
        Iniciar sesión
      </button>
    </form>

    <a href="/Instant_Football/index.php" class="login-volver">← Volver al sitio</a>

  </div>
</div>

</body>
</html>