<?php include '../../config/db.php'; ?>
<?php
  $noticias = $pdo->query("SELECT * FROM noticias ORDER BY fecha_publicacion DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Noticias — LigaPro</title>
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
          <?= $_SESSION['admin_nombre'] ?>
        </span>
        <a href="/Instant_Football/php/admin/logout.php" class="nav-cta" style="background:rgba(255,255,255,0.1);">
          Cerrar sesión
        </a>
      <?php else: ?>
        <a href="/Instant_Football/php/admin/login.php" class="nav-cta">Admin</a>
      <?php endif; ?>
  </header>
</div>

<main class="modulo-wrap">

  <div class="modulo-header">
    <div>
      <h1 class="modulo-titulo">Noticias</h1>
      <p class="modulo-sub">Novedades y noticias de la liga</p>
    </div>
      <?php if (isAdmin()): ?>
        <a href="crear.php" class="btn-red">+ Nueva noticia</a>
      <?php endif; ?>
  </div>

  <?php if (isset($_GET['msg'])): ?>
    <div class="alerta <?= in_array($_GET['msg'], ['creada','editada']) ? 'alerta-ok' : 'alerta-error' ?>">
      <?php
        $msgs = ['creada' => '✅ Noticia publicada.', 'editada' => '✅ Noticia actualizada.', 'eliminada' => '🗑️ Noticia eliminada.'];
        echo $msgs[$_GET['msg']] ?? '';
      ?>
    </div>
  <?php endif; ?>

  <?php if (empty($noticias)): ?>
    <div class="empty-state glass">
      <span>📰</span>
      <p>No hay noticias publicadas aún.</p>
      <a href="crear.php" class="btn-red">Publicar noticia</a>
    </div>
  <?php else: ?>
    <div class="noticias-grid">
      <?php foreach ($noticias as $n): ?>
        <div class="noticia-card glass">

          <?php if ($n['imagen']): ?>
            <div class="noticia-img">
              <img src="/Instant_Football/img/noticias/<?= htmlspecialchars($n['imagen']) ?>" alt="<?= htmlspecialchars($n['titulo']) ?>">
            </div>
          <?php else: ?>
            <div class="noticia-img noticia-img-placeholder">📰</div>
          <?php endif; ?>

          <div class="noticia-body">
            <span class="noticia-fecha">📅 <?= date('d M Y', strtotime($n['fecha_publicacion'])) ?></span>
            <h2 class="noticia-titulo"><?= htmlspecialchars($n['titulo']) ?></h2>
            <p class="noticia-desc"><?= htmlspecialchars($n['descripcion']) ?></p>
          </div>

          <div class="noticia-acciones">
            <?php if (isAdmin()): ?>
              <a href="editar.php?id=<?= $n['id_noticia'] ?>" class="btn-icon btn-edit" title="Editar">✏️</a>
              <a href="eliminar.php?id=<?= $n['id_noticia'] ?>"
               class="btn-icon btn-del" title="Eliminar"
               onclick="return confirm('¿Eliminar esta noticia?')">🗑️</a>
            <?php endif; ?>
   
          </div>

        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</main>
</body>
</html>