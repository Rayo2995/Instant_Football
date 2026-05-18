<?php include '../../config/db.php'; ?>
<?php
  $partidos = $pdo->query("
    SELECT p.*,
           e1.nombre AS local,
           e2.nombre AS visitante
    FROM partidos p
    JOIN equipos e1 ON p.equipo_local    = e1.id_equipo
    JOIN equipos e2 ON p.equipo_visitante = e2.id_equipo
    ORDER BY p.fecha DESC
  ")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Partidos — InstantFootball</title>
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
      <a href="index.php" class="active">Partidos</a>
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
      <h1 class="modulo-titulo">Partidos</h1>
      <p class="modulo-sub">Resultados y enfrentamientos de la liga</p>
    </div>
    <?php if (isAdmin()): ?>
      <a href="crear.php" class="btn-red">+ Nuevo partido</a>
    <?php endif; ?>
  </div>

  <?php if (isset($_GET['msg'])): ?>
    <div class="alerta <?= in_array($_GET['msg'], ['creado','editado']) ? 'alerta-ok' : 'alerta-error' ?>">
      <?php
        $msgs = ['creado' => '✅ Partido registrado.', 'editado' => '✅ Partido actualizado.', 'eliminado' => '🗑️ Partido eliminado.'];
        echo $msgs[$_GET['msg']] ?? '';
      ?>
    </div>
  <?php endif; ?>

  <?php if (empty($partidos)): ?>
    <div class="empty-state glass">
      <span>⚽</span>
      <p>No hay partidos registrados aún.</p>
      <a href="crear.php" class="btn-red">Registrar partido</a>
    </div>
  <?php else: ?>
    <div class="partidos-list">
      <?php foreach ($partidos as $p):
        $jugado = ($p['goles_local'] !== null && $p['goles_visitante'] !== null);
        $local_gana    = $jugado && $p['goles_local'] > $p['goles_visitante'];
        $visitante_gana= $jugado && $p['goles_visitante'] > $p['goles_local'];
        $empate        = $jugado && $p['goles_local'] == $p['goles_visitante'];
      ?>
        <div class="partido-card glass">

          <div class="partido-fecha">
            <span>📅 <?= date('d M Y', strtotime($p['fecha'])) ?></span>
            <?php if ($p['estadio']): ?>
              <span>🏟️ <?= htmlspecialchars($p['estadio']) ?></span>
            <?php endif; ?>
          </div>

          <div class="partido-enfrentamiento">
            <span class="partido-equipo <?= $local_gana ? 'ganador' : '' ?>">
              <?= htmlspecialchars($p['local']) ?>
            </span>

            <div class="partido-marcador">
              <?php if ($jugado): ?>
                <span class="marcador"><?= $p['goles_local'] ?> — <?= $p['goles_visitante'] ?></span>
                <?php if ($empate): ?>
                  <span class="resultado-badge badge-empate">Empate</span>
                <?php endif; ?>
              <?php else: ?>
                <span class="marcador-vs">VS</span>
              <?php endif; ?>
            </div>

            <span class="partido-equipo partido-visitante <?= $visitante_gana ? 'ganador' : '' ?>">
              <?= htmlspecialchars($p['visitante']) ?>
            </span>
          </div>

          <div class="partido-acciones">

            <?php if (isAdmin()): ?>
              <a href="editar.php?id=<?= $p['id_partido'] ?>" class="btn-icon btn-edit" title="Editar">✏️</a>
              <a href="eliminar.php?id=<?= $p['id_partido'] ?>"
                class="btn-icon btn-del" title="Eliminar"
                onclick="return confirm('¿Eliminar este partido?')">🗑️</a>
            <?php endif; ?>
          </div>

        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</main>
</body>
</html>