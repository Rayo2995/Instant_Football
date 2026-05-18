<?php include 'config/db.php'; ?>
<?php
  $equipos   = $pdo->query("SELECT COUNT(*) FROM equipos")->fetchColumn();
  $partidos  = $pdo->query("SELECT COUNT(*) FROM partidos")->fetchColumn();
  $jugadores = $pdo->query("SELECT COUNT(*) FROM jugadores")->fetchColumn();
  $goles = $pdo->query("
    SELECT COALESCE(SUM(goles_local),0) + COALESCE(SUM(goles_visitante),0)
    FROM partidos
  ")->fetchColumn(); 
  $proximo   = $pdo->query("
    SELECT p.fecha, p.estadio,
           e1.nombre AS local, e2.nombre AS visitante
    FROM partidos p
    JOIN equipos e1 ON p.equipo_local = e1.id_equipo
    JOIN equipos e2 ON p.equipo_visitante = e2.id_equipo
    WHERE p.fecha >= CURRENT_DATE
    ORDER BY p.fecha ASC LIMIT 1
  ")->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Instant Football</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="bg-orbs">
  <div class="orb orb1"></div>
  <div class="orb orb2"></div>
  <div class="orb orb3"></div>
</div>

<div class="topbar-wrap">
  <header class="topbar">
    <div class="logo"><em>Instant</em>Football</div>
    <nav>
      <a href="index.php" class="active">Inicio</a>
      <a href="php/equipos/index.php">Equipos</a>
      <a href="php/jugadores/index.php">Jugadores</a>
      <a href="php/partidos/index.php">Partidos</a>
      <a href="php/tabla/index.php">Tabla</a>
      <a href="php/noticias/index.php">Noticias</a>
    </nav>
      <?php if (isAdmin()): ?>
        <span style="font-size:0.78rem;color:rgba(255,255,255,0.5);">
          <?= $_SESSION['admin_nombre'] ?>
        </span>
        <a href="/php/admin/logout.php" class="nav-cta" style="background:rgba(255,255,255,0.1);">
          Cerrar sesión
        </a>
      <?php else: ?>
        <a href="/php/admin/login.php" class="nav-cta">Admin</a>
      <?php endif; ?>
  </header>
</div>

<section class="hero">
  <div class="hero-pill">Temporada 2025</div>
  <h1>El fútbol<br>en tus <em>manos</em></h1>
  <p>Administra tu liga deportiva desde un solo lugar. Equipos, partidos y estadísticas en tiempo real.</p>
</section>

<div class="stats-bar">
  <div class="stat-card glass">
    <div class="stat-num red"><?= $equipos ?></div>
    <div class="stat-lbl">Equipos</div>
  </div>
  <div class="stat-card glass">
    <div class="stat-num red"><?= $partidos ?></div>
    <div class="stat-lbl">Partidos</div>
  </div>
  <div class="stat-card glass">
    <div class="stat-num red"><?= $jugadores ?></div>
    <div class="stat-lbl">Jugadores</div>
  </div>
  <div class="stat-card glass">
    <div class="stat-num"><?= $goles ?></div>
    <div class="stat-lbl">Goles</div>
  </div>
</div>

<?php if ($proximo): ?>
<div class="blue-strip">
  <p>
    <strong>Próximo partido:</strong>
    <?= htmlspecialchars($proximo['local']) ?> vs <?= htmlspecialchars($proximo['visitante']) ?>
    — <?= date('d M Y', strtotime($proximo['fecha'])) ?>
    · <?= htmlspecialchars($proximo['estadio']) ?>
  </p>
  <a href="php/partidos/index.php" class="btn-blue">Ver todos</a>
</div>
<?php endif; ?>

<section class="modules-section">
  <div class="modules-grid">
    <a class="module-card glass accent" href="php/equipos/index.php">
      <div class="mod-icon">🛡️</div>
      <span class="mod-label">Equipos</span>
      <span class="mod-desc">Registrar, editar y gestionar clubes</span>
    </a>
    <a class="module-card glass" href="php/jugadores/index.php">
      <div class="mod-icon">🏃</div>
      <span class="mod-label">Jugadores</span>
      <span class="mod-desc">Plantillas y estadísticas</span>
    </a>
    <a class="module-card glass" href="php/partidos/index.php">
      <div class="mod-icon">⚽</div>
      <span class="mod-label">Partidos</span>
      <span class="mod-desc">Programar y registrar resultados</span>
    </a>
    <a class="module-card glass" href="php/tabla/index.php">
      <div class="mod-icon">📊</div>
      <span class="mod-label">Tabla</span>
      <span class="mod-desc">Posiciones calculadas en vivo</span>
    </a>
    <a class="module-card glass" href="php/jugadores/goleadores.php">
      <div class="mod-icon">🏆</div>
      <span class="mod-label">Goleadores</span>
      <span class="mod-desc">Máximos anotadores de la liga</span>
    </a>
    <a class="module-card glass" href="php/noticias/index.php">
      <div class="mod-icon">📰</div>
      <span class="mod-label">Noticias</span>
      <span class="mod-desc">Novedades y noticias de la liga</span>
    </a>
  </div>
</section>

<footer class="footer">Instant Football &copy; 2025 — Fundación Universitaria Salesiana</footer>

</body>
</html>