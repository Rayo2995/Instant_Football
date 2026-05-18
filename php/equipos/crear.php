<?php include '../../config/db.php'; ?>
<?php

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $ciudad = trim($_POST['ciudad']);
    $dt     = trim($_POST['director_tecnico']);
    $escudo = null;

    if (!empty($_FILES['escudo']['name'])) {
        $ext     = pathinfo($_FILES['escudo']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg','jpeg','png','webp','gif'];
        if (!in_array(strtolower($ext), $allowed)) {
            $error = 'Formato de imagen no permitido.';
        } else {
            $filename = uniqid('escudo_') . '.' . $ext;
            $destino  = __DIR__ . '/../../img/escudos/' . $filename;
            if (move_uploaded_file($_FILES['escudo']['tmp_name'], $destino)) {
                $escudo = $filename;
            } else {
                $error = 'Error al subir la imagen.';
            }
        }
    }

    if (!$error) {
        $stmt = $pdo->prepare("INSERT INTO equipos (nombre, ciudad, director_tecnico, escudo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $ciudad, $dt, $escudo]);
        header('Location: index.php?msg=creado');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nuevo Equipo — InstantFootball</title>
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
      <h1 class="modulo-titulo">Nuevo Equipo</h1>
      <p class="modulo-sub">Completa los datos del club</p>
    </div>
    <a href="index.php" class="btn-outline">← Volver</a>
  </div>

  <?php if (isset($error) && $error): ?>
    <div class="alerta alerta-error">⚠️ <?= $error ?></div>
  <?php endif; ?>

  <div class="form-card glass">
    <form method="POST" enctype="multipart/form-data">

      <div class="form-group">
        <label>Nombre del equipo *</label>
        <input type="text" name="nombre" required placeholder="Ej: Atlético FC"
               value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label>Ciudad *</label>
        <input type="text" name="ciudad" required placeholder="Ej: Bogotá"
               value="<?= htmlspecialchars($_POST['ciudad'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label>Director técnico</label>
        <input type="text" name="director_tecnico" placeholder="Ej: Carlos Ruiz"
               value="<?= htmlspecialchars($_POST['director_tecnico'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label>Escudo del equipo</label>
        <div class="file-drop" id="fileDrop">
          <input type="file" name="escudo" id="escudoInput" accept="image/*">
          <div class="file-drop-content" id="fileLabel">
            <span>🖼️</span>
            <p>Haz clic o arrastra una imagen aquí</p>
            <small>JPG, PNG, WEBP — máx. 2MB</small>
          </div>
        </div>
        <img id="preview" src="" alt="Preview" style="display:none; margin-top:1rem; max-height:120px; border-radius:12px;">
      </div>

      <div class="form-actions">
        <a href="index.php" class="btn-outline">Cancelar</a>
        <button type="submit" class="btn-red">Guardar equipo</button>
      </div>

    </form>
  </div>

</main>

<script>
  const input   = document.getElementById('escudoInput');
  const preview = document.getElementById('preview');
  const label   = document.getElementById('fileLabel');

  input.addEventListener('change', () => {
    const file = input.files[0];
    if (file) {
      const url = URL.createObjectURL(file);
      preview.src = url;
      preview.style.display = 'block';
      label.querySelector('p').textContent = file.name;
    }
  });
</script>

</body>
</html>