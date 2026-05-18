<?php include '../../config/db.php'; ?>
<?php
  $id      = (int)$_GET['id'];
  $jugador = $pdo->prepare("SELECT * FROM jugadores WHERE id_jugador = ?");
  $jugador->execute([$id]);
  $jugador = $jugador->fetch(PDO::FETCH_ASSOC);
  if (!$jugador) { header('Location: index.php'); exit; }

  $equipos = $pdo->query("SELECT id_equipo, nombre FROM equipos ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nombre      = trim($_POST['nombre']);
      $posicion    = trim($_POST['posicion']);
      $edad        = (int)$_POST['edad'];
      $goles       = (int)$_POST['goles'];
      $asistencias = (int)$_POST['asistencias'];
      $id_equipo   = (int)$_POST['id_equipo'];

      $stmt = $pdo->prepare("UPDATE jugadores SET nombre=?, posicion=?, edad=?, goles=?, asistencias=?, id_equipo=? WHERE id_jugador=?");
      $stmt->execute([$nombre, $posicion, $edad, $goles, $asistencias, $id_equipo ?: null, $id]);
      header('Location: index.php?msg=editado');
      exit;
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Jugador — InstantFootball</title>
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
    <a href="../admin/login.php" class="nav-cta">Admin</a>
  </header>
</div>

<main class="modulo-wrap">

  <div class="modulo-header">
    <div>
      <h1 class="modulo-titulo">Editar Jugador</h1>
      <p class="modulo-sub"><?= htmlspecialchars($jugador['nombre']) ?></p>
    </div>
    <a href="index.php" class="btn-outline">← Volver</a>
  </div>

  <div class="form-card glass">
    <form method="POST">

      <div class="form-group">
        <label>Nombre completo *</label>
        <input type="text" name="nombre" required value="<?= htmlspecialchars($jugador['nombre']) ?>">
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Posición</label>
          <select name="posicion">
            <option value="">— Seleccionar —</option>
            <?php foreach (['Portero','Defensa','Mediocampista','Delantero'] as $pos): ?>
              <option value="<?= $pos ?>" <?= $jugador['posicion'] === $pos ? 'selected' : '' ?>>
                <?= $pos ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Edad</label>
          <input type="number" name="edad" min="15" max="50" value="<?= $jugador['edad'] ?>">
        </div>
      </div>

      <div class="form-group">
        <label>Equipo</label>
        <select name="id_equipo">
          <option value="">— Sin equipo —</option>
          <?php foreach ($equipos as $eq): ?>
            <option value="<?= $eq['id_equipo'] ?>"
              <?= $jugador['id_equipo'] == $eq['id_equipo'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($eq['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>⚽ Goles</label>
          <input type="number" name="goles" min="0" value="<?= $jugador['goles'] ?>">
        </div>
        <div class="form-group">
          <label>🎯 Asistencias</label>
          <input type="number" name="asistencias" min="0" value="<?= $jugador['asistencias'] ?>">
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