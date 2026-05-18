<?php include '../../config/db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Equipos — Instant Football</title>
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

<main class="modulo-wrap">

  <div class="modulo-header">
    <div>
      <h1 class="modulo-titulo">Equipos</h1>
      <p class="modulo-sub">Gestiona los clubes de la liga</p>
    </div>
    <?php if (isAdmin()): ?>
      <a href="crear.php" class="btn-red">+ Nuevo equipo</a>
    <?php endif; ?>
  </div>

  <?php if (isset($_GET['msg'])): ?>
    <div class="alerta <?= $_GET['msg'] === 'creado' || $_GET['msg'] === 'editado' ? 'alerta-ok' : 'alerta-error' ?>">
      <?php
        $msgs = [
          'creado'    => '✅ Equipo creado correctamente.',
          'editado'   => '✅ Equipo actualizado correctamente.',
          'eliminado' => '🗑️ Equipo eliminado.',
        ];
        echo $msgs[$_GET['msg']] ?? '';
      ?>
    </div>
  <?php endif; ?>

  <?php
    $equipos = $pdo->query("SELECT * FROM equipos ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
  ?>

  <?php if (empty($equipos)): ?>
    <div class="empty-state glass">
      <span>🛡️</span>
      <p>No hay equipos registrados aún.</p>
      <a href="crear.php" class="btn-red">Crear primer equipo</a>
    </div>
  <?php else: ?>
    <div class="cards-grid">
      <?php foreach ($equipos as $e): ?>
        <div class="equipo-card glass">
          <div class="equipo-escudo">
            <?php if ($e['escudo']): ?>
              <img src="/img/escudos/<?= htmlspecialchars($e['escudo']) ?>" alt="Escudo">
            <?php else: ?>
              <div class="escudo-placeholder">🛡️</div>
            <?php endif; ?>
          </div>
          <div class="equipo-info">
            <h2><?= htmlspecialchars($e['nombre']) ?></h2>
            <p class="equipo-ciudad">📍 <?= htmlspecialchars($e['ciudad']) ?></p>
            <p class="equipo-dt">👤 <?= htmlspecialchars($e['director_tecnico'] ?? '—') ?></p>
          </div>
          <div class="equipo-acciones">
            <?php if (isAdmin()): ?> 
              <a href="editar.php?id=<?= $e['id_equipo'] ?>" class="btn-icon btn-edit" title="Editar">✏️</a>
              <a href="eliminar.php?id=<?= $e['id_equipo'] ?>" class="btn-icon btn-del" title="Eliminar"
                onclick="return confirm('¿Eliminar este equipo? Esta acción no se puede deshacer.')">🗑️</a>
            <?php endif; ?>
            </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</main>
</body>
</html>