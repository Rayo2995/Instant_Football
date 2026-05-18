<?php include '../../config/db.php'; ?>
<?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $titulo      = trim($_POST['titulo']);
      $descripcion = trim($_POST['descripcion']);
      $fecha       = $_POST['fecha_publicacion'];
      $imagen      = null;

      if (!empty($_FILES['imagen']['name'])) {
          $ext     = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
          $allowed = ['jpg','jpeg','png','webp'];
          if (in_array(strtolower($ext), $allowed)) {
              $filename = uniqid('noticia_') . '.' . $ext;
              $destino  = __DIR__ . '/../../img/noticias/' . $filename;
              if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                  $imagen = $filename;
              }
          }
      }

      $stmt = $pdo->prepare("INSERT INTO noticias (titulo, descripcion, fecha_publicacion, imagen) VALUES (?,?,?,?)");
      $stmt->execute([$titulo, $descripcion, $fecha, $imagen]);
      header('Location: index.php?msg=creada');
      exit;
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nueva Noticia — InstantFootball</title>
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
      <a href="../equipos/index.php">Equipos</a>
      <a href="../jugadores/index.php">Jugadores</a>
      <a href="../partidos/index.php">Partidos</a>
      <a href="../tabla/index.php">Tabla</a>
      <a href="index.php" class="active">Noticias</a>
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
      <h1 class="modulo-titulo">Nueva Noticia</h1>
      <p class="modulo-sub">Publica una novedad de la liga</p>
    </div>
    <a href="index.php" class="btn-outline">← Volver</a>
  </div>

  <div class="form-card glass">
    <form method="POST" enctype="multipart/form-data">

      <div class="form-group">
        <label>Título *</label>
        <input type="text" name="titulo" required placeholder="Ej: Atlético FC golea en su debut"
               value="<?= htmlspecialchars($_POST['titulo'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label>Descripción</label>
        <textarea name="descripcion" placeholder="Escribe el contenido de la noticia..."><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
      </div>

      <div class="form-group">
        <label>Fecha de publicación *</label>
        <input type="date" name="fecha_publicacion" required
               value="<?= $_POST['fecha_publicacion'] ?? date('Y-m-d') ?>">
      </div>

      <div class="form-group">
        <label>Imagen <small style="text-transform:none;letter-spacing:0">(opcional)</small></label>
        <input type="file" name="imagen" id="imagenInput" accept="image/*"
               style="color:rgba(255,255,255,0.6);padding:0.5rem 0;">
        <img id="preview" src="" alt=""
             style="display:none;margin-top:1rem;max-height:160px;border-radius:14px;object-fit:cover;width:100%;">
      </div>

      <div class="form-actions">
        <a href="index.php" class="btn-outline">Cancelar</a>
        <button type="submit" class="btn-red">Publicar noticia</button>
      </div>

    </form>
  </div>

</main>

<script>
  const input   = document.getElementById('imagenInput');
  const preview = document.getElementById('preview');
  input.addEventListener('change', () => {
    if (input.files[0]) {
      preview.src = URL.createObjectURL(input.files[0]);
      preview.style.display = 'block';
    }
  });
</script>
</body>
</html>