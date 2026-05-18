<?php include '../../config/db.php'; ?>
<?php
  $goleadores = $pdo->query("
      SELECT j.nombre, j.goles, j.asistencias, e.nombre AS equipo
      FROM jugadores j
      LEFT JOIN equipos e ON j.id_equipo = e.id_equipo
      ORDER BY j.goles DESC, j.asistencias DESC
      LIMIT 10
  ")->fetchAll(PDO::FETCH_ASSOC);

  $asistentes = $pdo->query("
      SELECT j.nombre, j.asistencias, j.goles, e.nombre AS equipo
      FROM jugadores j
      LEFT JOIN equipos e ON j.id_equipo = e.id_equipo
      ORDER BY j.asistencias DESC, j.goles DESC
      LIMIT 10
  ")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Goleadores — InstantFootball</title>
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
      <a href="index.php" class="active">Jugadores</a>
      <a href="../partidos/index.php">Partidos</a>
      <a href="../tabla/index.php">Tabla</a>
      <a href="../noticias/index.php">Noticias</a>
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
      <h1 class="modulo-titulo">Estadísticas</h1>
      <p class="modulo-sub">Goleadores y asistentes de la liga</p>
    </div>
    <a href="index.php" class="btn-outline">← Jugadores</a>
  </div>

  <div class="rankings-grid">

    <!-- Goleadores -->
    <div class="ranking-card glass">
      <h2 class="ranking-titulo">⚽ Máximos Goleadores</h2>
      <div class="ranking-list">
        <?php foreach ($goleadores as $i => $j): ?>
          <div class="ranking-row <?= $i === 0 ? 'ranking-top' : '' ?>">
            <span class="ranking-pos"><?= $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : $i + 1)) ?></span>
            <div class="ranking-info">
              <span class="ranking-nombre"><?= htmlspecialchars($j['nombre']) ?></span>
              <span class="ranking-equipo"><?= htmlspecialchars($j['equipo'] ?? '—') ?></span>
            </div>
            <span class="ranking-stat red"><?= $j['goles'] ?><small>goles</small></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Asistentes -->
    <div class="ranking-card glass">
      <h2 class="ranking-titulo">🎯 Máximos Asistentes</h2>
      <div class="ranking-list">
        <?php foreach ($asistentes as $i => $j): ?>
          <div class="ranking-row <?= $i === 0 ? 'ranking-top' : '' ?>">
            <span class="ranking-pos"><?= $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : $i + 1)) ?></span>
            <div class="ranking-info">
              <span class="ranking-nombre"><?= htmlspecialchars($j['nombre']) ?></span>
              <span class="ranking-equipo"><?= htmlspecialchars($j['equipo'] ?? '—') ?></span>
            </div>
            <span class="ranking-stat blue"><?= $j['asistencias'] ?><small>asist.</small></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>

</main>
</body>
</html>