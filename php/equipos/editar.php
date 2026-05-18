<?php include '../../config/db.php'; ?>
<?php
  $id     = (int)$_GET['id'];
  $equipo = $pdo->prepare("SELECT * FROM equipos WHERE id_equipo = ?");
  $equipo->execute([$id]);
  $equipo = $equipo->fetch(PDO::FETCH_ASSOC);
  if (!$equipo) { header('Location: index.php'); exit; }

  $error = null;

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nombre = trim($_POST['nombre']);
      $ciudad = trim($_POST['ciudad']);
      $dt     = trim($_POST['director_tecnico']);
      $escudo = $equipo['escudo'];

      if (!empty($_FILES['escudo']['name'])) {
          $ext     = pathinfo($_FILES['escudo']['name'], PATHINFO_EXTENSION);
          $allowed = ['jpg','jpeg','png','webp','gif'];
          if (!in_array(strtolower($ext), $allowed)) {
              $error = 'Formato de imagen no permitido.';
          } else {
              if ($escudo && file_exists(__DIR__ . '/../../img/escudos/' . $escudo)) {
                  unlink(__DIR__ . '/../../img/escudos/' . $escudo);
              }
              $filename = uniqid('escudo_') . '.' . $ext;
              $destino  = __DIR__ . '/../../img/escudos/' . $filename;
              if (move_uploaded_file($_FILES['escudo']['tmp_name'], $destino)) {
                  $escudo = $filename; // ← esto faltaba
              } else {
                  $error = 'Error al subir la imagen.';
              }
          }
      }

      if (!$error) {
          $stmt = $pdo->prepare("UPDATE equipos SET nombre=?, ciudad=?, director_tecnico=?, escudo=? WHERE id_equipo=?");
          $stmt->execute([$nombre, $ciudad, $dt, $escudo, $id]);
          header('Location: index.php?msg=editado');
          exit;
      }
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Equipo — InstantFootball</title>
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

<div class="topbar-wrap">
  <header class="topbar">
    <a href="../../index.php" class="logo"><em>Instant</em>Football</a>
    <nav>
      <a href="../../index.php">Inicio</a>
      <a href="index.php" class="active">Equipos</a>
      <a href="../jugadores/index.php">Jugadores</a>
      <a href="../partidos/index.php">Partidos</a>
      <a href="../tabla/index.php">Tabla</a>
      <a href="../noticias/index.php">Noticias</a>
    </nav>
    <?php if (isAdmin()): ?>
      <span style="font-size:0.78rem;color:rgba(255,255,255,0.5);">
        👤 <?= $_SESSION['admin_nombre'] ?>
      </span>
      <a href="/php/admin/logout.php" class="nav-cta" style="background:rgba(255,255,255,0.1);">
        Cerrar sesión
      </a>
    <?php else: ?>
      <a href="/php/admin/login.php" class="nav-cta">Admin</a>
    <?php endif; ?>
  </header>
</div>

<main class="modulo-wrap">

  <div class="modulo-header">
    <div>
      <h1 class="modulo-titulo">Editar Equipo</h1>
      <p class="modulo-sub"><?= htmlspecialchars($equipo['nombre']) ?></p>
    </div>
    <a href="index.php" class="btn-outline">← Volver</a>
  </div>

  <?php if ($error): ?>
    <div class="alerta alerta-error">⚠️ <?= $error ?></div>
  <?php endif; ?>

  <div class="form-card glass">
    <form method="POST" enctype="multipart/form-data">

      <div class="form-group">
        <label>Nombre del equipo *</label>
        <input type="text" name="nombre" required
               value="<?= htmlspecialchars($equipo['nombre']) ?>">
      </div>

      <div class="form-group">
        <label>Ciudad *</label>
        <input type="text" name="ciudad" required
               value="<?= htmlspecialchars($equipo['ciudad']) ?>">
      </div>

      <div class="form-group">
        <label>Director técnico</label>
        <input type="text" name="director_tecnico"
               value="<?= htmlspecialchars($equipo['director_tecnico'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label>Escudo del equipo</label>
        <?php if ($equipo['escudo']): ?>
          <img src="/Instant_Football/img/escudos/<?= htmlspecialchars($equipo['escudo']) ?>"
               alt="Escudo actual" id="preview"
               style="max-height:100px; border-radius:12px; margin-bottom:0.75rem; display:block;">
        <?php else: ?>
          <img id="preview" src="" alt="" style="display:none; max-height:100px; border-radius:12px; margin-bottom:0.75rem;">
        <?php endif; ?>
        <div class="file-drop">
          <input type="file" name="escudo" id="escudoInput" accept="image/*">
          <div class="file-drop-content">
            <span>🖼️</span>
            <p>Cambiar escudo — haz clic o arrastra</p>
            <small>JPG, PNG, WEBP</small>
          </div>
        </div>
      </div>

      <div class="form-actions">
        <a href="index.php" class="btn-outline">Cancelar</a>
        <button type="submit" class="btn-red">Guardar cambios</button>
      </div>

    </form>
  </div>

</main>

<script>
  const input   = document.getElementById('escudoInput');
  const preview = document.getElementById('preview');
  input.addEventListener('change', () => {
    const file = input.files[0];
    if (file) {
      preview.src = URL.createObjectURL(file);
      preview.style.display = 'block';
    }
  });
</script>

<script src="../../js/main.js"></script>
</body>
</html>