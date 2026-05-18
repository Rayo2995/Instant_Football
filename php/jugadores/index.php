<?php include '../../config/db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jugadores — InstantFootball</title>
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
      <h1 class="modulo-titulo">Jugadores</h1>
      <p class="modulo-sub">Gestiona las plantillas de la liga</p>
    </div>
    <div style="display:flex;gap:0.75rem;">
      <a href="goleadores.php" class="btn-outline">🏆 Goleadores</a>
      <?php if (isAdmin()): ?>
        <a href="crear.php" class="btn-red">+ Nuevo jugador</a>
      <?php endif; ?>
    </div>
  </div>

  <?php if (isset($_GET['msg'])): ?>
    <div class="alerta <?= in_array($_GET['msg'], ['creado','editado']) ? 'alerta-ok' : 'alerta-error' ?>">
      <?php
        $msgs = [
          'creado'    => '✅ Jugador creado correctamente.',
          'editado'   => '✅ Jugador actualizado correctamente.',
          'eliminado' => '🗑️ Jugador eliminado.',
        ];
        echo $msgs[$_GET['msg']] ?? '';
      ?>
    </div>
  <?php endif; ?>

  <!-- Filtro por equipo -->
  <?php
    $equipos = $pdo->query("SELECT id_equipo, nombre FROM equipos ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
    $filtro  = isset($_GET['equipo']) ? (int)$_GET['equipo'] : 0;

    if ($filtro) {
        $stmt = $pdo->prepare("
            SELECT j.*, e.nombre AS equipo_nombre
            FROM jugadores j
            LEFT JOIN equipos e ON j.id_equipo = e.id_equipo
            WHERE j.id_equipo = ?
            ORDER BY j.goles DESC, j.nombre ASC
        ");
        $stmt->execute([$filtro]);
    } else {
        $stmt = $pdo->query("
            SELECT j.*, e.nombre AS equipo_nombre
            FROM jugadores j
            LEFT JOIN equipos e ON j.id_equipo = e.id_equipo
            ORDER BY j.goles DESC, j.nombre ASC
        ");
    }
    $jugadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>

  <div class="filtro-bar glass">
    <span class="filtro-label">Filtrar por equipo:</span>
    <div class="filtro-chips">
      <a href="index.php" class="chip <?= $filtro === 0 ? 'chip-active' : '' ?>">Todos</a>
      <?php foreach ($equipos as $eq): ?>
        <a href="index.php?equipo=<?= $eq['id_equipo'] ?>"
           class="chip <?= $filtro === (int)$eq['id_equipo'] ? 'chip-active' : '' ?>">
          <?= htmlspecialchars($eq['nombre']) ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>

 <?php if (empty($jugadores)): ?>
    <div class="empty-state glass">
      <span>🏃</span>
      <p>No hay jugadores registrados aún.</p>
      <a href="crear.php" class="btn-red">Agregar jugador</a>
    </div>
  <?php else: ?>
    <div class="cards-grid">
      <?php foreach ($jugadores as $i => $j): ?>
        <div class="jugador-card glass">

          <div class="jugador-top">
            <div class="jugador-avatar">
              <?= strtoupper(substr($j['nombre'], 0, 1)) ?>
            </div>
            <div class="jugador-acciones">
              <?php if (isAdmin()): ?>
                <a href="editar.php?id=<?= $j['id_jugador'] ?>" class="btn-icon btn-edit" title="Editar">✏️</a>
                <a href="eliminar.php?id=<?= $j['id_jugador'] ?>"
                  class="btn-icon btn-del" title="Eliminar"
                  onclick="return confirm('¿Eliminar a <?= htmlspecialchars($j['nombre']) ?>?')">🗑️</a>
              <?php endif; ?>
            </div>
          </div>

          <h2 class="jugador-nombre"><?= htmlspecialchars($j['nombre']) ?></h2>

          <div class="jugador-badges">
            <span class="badge-pos badge-<?= strtolower($j['posicion'] ?? 'otro') ?>">
              <?= htmlspecialchars($j['posicion'] ?? '—') ?>
            </span>
            <span class="badge-equipo">
              <?= htmlspecialchars($j['equipo_nombre'] ?? '—') ?>
            </span>
          </div>

          <div class="jugador-stats">
            <div class="jugador-stat">
              <span class="jstat-num red"><?= $j['goles'] ?></span>
              <span class="jstat-lbl">⚽ Goles</span>
            </div>
            <div class="jugador-stat-divider"></div>
            <div class="jugador-stat">
              <span class="jstat-num blue"><?= $j['asistencias'] ?></span>
              <span class="jstat-lbl">🎯 Asist.</span>
            </div>
            <div class="jugador-stat-divider"></div>
            <div class="jugador-stat">
              <span class="jstat-num"><?= $j['edad'] ?? '—' ?></span>
              <span class="jstat-lbl">Edad</span>
            </div>
          </div>

        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</main>
</body>
</html>