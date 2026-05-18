<?php include '../../config/db.php'; ?>
<?php
  $id      = (int)$_GET['id'];
  $partido = $pdo->prepare("SELECT * FROM partidos WHERE id_partido = ?");
  $partido->execute([$id]);
  $partido = $partido->fetch(PDO::FETCH_ASSOC);
  if (!$partido) { header('Location: index.php'); exit; }

  $equipos = $pdo->query("SELECT id_equipo, nombre FROM equipos ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $local     = (int)$_POST['equipo_local'];
      $visitante = (int)$_POST['equipo_visitante'];
      $goles_l   = $_POST['goles_local']     !== '' ? (int)$_POST['goles_local']     : null;
      $goles_v   = $_POST['goles_visitante'] !== '' ? (int)$_POST['goles_visitante'] : null;
      $fecha     = $_POST['fecha'];
      $estadio   = trim($_POST['estadio']);

      $stmt = $pdo->prepare("UPDATE partidos SET equipo_local=?, equipo_visitante=?, goles_local=?, goles_visitante=?, fecha=?, estadio=? WHERE id_partido=?");
      $stmt->execute([$local, $visitante, $goles_l, $goles_v, $fecha, $estadio ?: null, $id]);
      header('Location: index.php?msg=editado');
      exit;
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Partido — InstantFooball</title>
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
    <a href="../../index.php" class="logo"><em>Instant</em>Fooball</a>
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
      <h1 class="modulo-titulo">Editar Partido</h1>
      <p class="modulo-sub">Actualiza resultado o información</p>
    </div>
    <a href="index.php" class="btn-outline">← Volver</a>
  </div>

  <div class="form-card glass">
    <form method="POST">

      <div class="form-row">
        <div class="form-group">
          <label>Equipo Local *</label>
          <select name="equipo_local" required>
            <?php foreach ($equipos as $eq): ?>
              <option value="<?= $eq['id_equipo'] ?>"
                <?= $partido['equipo_local'] == $eq['id_equipo'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($eq['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Equipo Visitante *</label>
          <select name="equipo_visitante" required>
            <?php foreach ($equipos as $eq): ?>
              <option value="<?= $eq['id_equipo'] ?>"
                <?= $partido['equipo_visitante'] == $eq['id_equipo'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($eq['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Goles Local</label>
          <input type="number" name="goles_local" min="0"
                 value="<?= $partido['goles_local'] ?? '' ?>" placeholder="—">
        </div>
        <div class="form-group">
          <label>Goles Visitante</label>
          <input type="number" name="goles_visitante" min="0"
                 value="<?= $partido['goles_visitante'] ?? '' ?>" placeholder="—">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Fecha *</label>
          <input type="date" name="fecha" required value="<?= $partido['fecha'] ?>">
        </div>
        <div class="form-group">
          <label>Estadio</label>
          <input type="text" name="estadio" value="<?= htmlspecialchars($partido['estadio'] ?? '') ?>">
        </div>
      </div>

      <div class="form-actions">
        <a href="index.php" class="btn-outline">Cancelar</a>
        <button type="submit" class="btn-red">Guardar cambios</button>
      </div>

    </form>
  </div>

</main>
</body>
</html>