<?php include '../../config/db.php'; ?>
<?php
  $equipos = $pdo->query("SELECT id_equipo, nombre FROM equipos ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $local      = (int)$_POST['equipo_local'];
      $visitante  = (int)$_POST['equipo_visitante'];
      $goles_l    = $_POST['goles_local']     !== '' ? (int)$_POST['goles_local']     : null;
      $goles_v    = $_POST['goles_visitante'] !== '' ? (int)$_POST['goles_visitante'] : null;
      $fecha      = $_POST['fecha'];
      $estadio    = trim($_POST['estadio']);

      if ($local === $visitante) {
          $error = 'El equipo local y visitante no pueden ser el mismo.';
      } else {
          $stmt = $pdo->prepare("INSERT INTO partidos (equipo_local, equipo_visitante, goles_local, goles_visitante, fecha, estadio) VALUES (?,?,?,?,?,?)");
          $stmt->execute([$local, $visitante, $goles_l, $goles_v, $fecha, $estadio ?: null]);
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
  <title>Nuevo Partido — InstantFooball</title>
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
    <a href="../admin/login.php" class="nav-cta">Admin</a>
  </header>
</div>

<main class="modulo-wrap">

  <div class="modulo-header">
    <div>
      <h1 class="modulo-titulo">Nuevo Partido</h1>
      <p class="modulo-sub">Programa o registra un resultado</p>
    </div>
    <a href="index.php" class="btn-outline">← Volver</a>
  </div>

  <?php if (isset($error)): ?>
    <div class="alerta alerta-error">⚠️ <?= $error ?></div>
  <?php endif; ?>

  <div class="form-card glass">
    <form method="POST">

      <div class="form-row">
        <div class="form-group">
          <label>Equipo Local *</label>
          <select name="equipo_local" required>
            <option value="">— Seleccionar —</option>
            <?php foreach ($equipos as $eq): ?>
              <option value="<?= $eq['id_equipo'] ?>"
                <?= ($_POST['equipo_local'] ?? '') == $eq['id_equipo'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($eq['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Equipo Visitante *</label>
          <select name="equipo_visitante" required>
            <option value="">— Seleccionar —</option>
            <?php foreach ($equipos as $eq): ?>
              <option value="<?= $eq['id_equipo'] ?>"
                <?= ($_POST['equipo_visitante'] ?? '') == $eq['id_equipo'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($eq['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="partido-preview-form glass">
        <span id="prev-local">Local</span>
        <span class="prev-vs">VS</span>
        <span id="prev-visitante">Visitante</span>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Goles Local <small style="text-transform:none;letter-spacing:0">(dejar vacío si no se ha jugado)</small></label>
          <input type="number" name="goles_local" min="0" value="<?= $_POST['goles_local'] ?? '' ?>" placeholder="—">
        </div>
        <div class="form-group">
          <label>Goles Visitante</label>
          <input type="number" name="goles_visitante" min="0" value="<?= $_POST['goles_visitante'] ?? '' ?>" placeholder="—">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Fecha *</label>
          <input type="date" name="fecha" required value="<?= $_POST['fecha'] ?? '' ?>">
        </div>
        <div class="form-group">
          <label>Estadio</label>
          <input type="text" name="estadio" placeholder="Ej: El Campín" value="<?= htmlspecialchars($_POST['estadio'] ?? '') ?>">
        </div>
      </div>

      <div class="form-actions">
        <a href="index.php" class="btn-outline">Cancelar</a>
        <button type="submit" class="btn-red">Guardar partido</button>
      </div>

    </form>
  </div>

</main>

<script>
  const selLocal     = document.querySelector('select[name="equipo_local"]');
  const selVisitante = document.querySelector('select[name="equipo_visitante"]');
  const prevLocal    = document.getElementById('prev-local');
  const prevVisit    = document.getElementById('prev-visitante');

  function update() {
    prevLocal.textContent = selLocal.options[selLocal.selectedIndex]?.text || 'Local';
    prevVisit.textContent = selVisitante.options[selVisitante.selectedIndex]?.text || 'Visitante';
  }
  selLocal.addEventListener('change', update);
  selVisitante.addEventListener('change', update);
</script>
</body>
</html>