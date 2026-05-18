<?php include '../../config/db.php'; ?>

<?php
  $tabla = $pdo->query("
    SELECT * FROM tabla_posiciones
  ")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tabla de Posiciones — InstantFootball</title>

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

    <a href="../../index.php" class="logo">
      <em>Instant</em>Football
    </a>

    <nav>
      <a href="../../index.php">Inicio</a>
      <a href="../equipos/index.php">Equipos</a>
      <a href="../jugadores/index.php">Jugadores</a>
      <a href="../partidos/index.php">Partidos</a>
      <a href="index.php" class="active">Tabla</a>
      <a href="../noticias/index.php">Noticias</a>
    </nav>

    <?php if (isAdmin()): ?>
      <span style="font-size:0.78rem;color:rgba(255,255,255,0.5);">
        <?= $_SESSION['admin_nombre'] ?>
      </span>

      <a href="/php/admin/logout.php"
         class="nav-cta"
         style="background:rgba(255,255,255,0.1);">
        Cerrar sesión
      </a>

    <?php else: ?>

      <a href="/php/admin/login.php" class="nav-cta">
        Admin
      </a>

    <?php endif; ?>

  </header>
</div>

<main class="modulo-wrap">

  <div class="modulo-header">
    <div>
      <h1 class="modulo-titulo">Tabla de Posiciones</h1>
      <p class="modulo-sub">
        Clasificación actualizada automáticamente
      </p>
    </div>
  </div>

  <?php if (empty($tabla)): ?>

    <div class="empty-state glass">
      <span>📊</span>
      <p>No hay datos suficientes para mostrar la tabla.</p>

      <a href="../partidos/crear.php" class="btn-red">
        Registrar partido
      </a>
    </div>

  <?php else: ?>

    <div class="tabla-wrap glass">

      <table class="tabla">

        <thead>
          <tr>
            <th>#</th>
            <th>Equipo</th>
            <th title="Partidos Jugados">PJ</th>
            <th title="Victorias">V</th>
            <th title="Empates">E</th>
            <th title="Derrotas">D</th>
            <th title="Goles a Favor">GF</th>
            <th title="Goles en Contra">GC</th>
            <th title="Diferencia de Goles">DG</th>
            <th title="Puntos">Pts</th>
          </tr>
        </thead>

        <tbody>

          <?php foreach ($tabla as $i => $eq):

            $pos = $i + 1;
            $dg  = $eq['diferencia_goles'];

            $dgClass = $dg > 0
              ? 'dg-pos'
              : ($dg < 0 ? 'dg-neg' : 'dg-neu');

          ?>

            <tr class="<?= $pos === 1 ? 'fila-lider' : ($pos <= 3 ? 'fila-top' : '') ?>">

              <td>
                <span class="pos-badge <?= $pos === 1 ? 'pos-1' : ($pos === 2 ? 'pos-2' : ($pos === 3 ? 'pos-3' : '')) ?>">

                  <?= $pos === 1
                    ? ' 1 '
                    : ($pos === 2
                        ? ' 2 '
                        : ($pos === 3 ? ' 3 ' : $pos)) ?>

                </span>
              </td>

              <td class="td-nombre">

                <?php if ($eq['escudo']): ?>

                  <img
                    src="/img/escudos/<?= htmlspecialchars($eq['escudo']) ?>"
                    alt="Escudo"
                    style="width:24px;
                           height:24px;
                           border-radius:6px;
                           object-fit:cover;
                           vertical-align:middle;
                           margin-right:0.5rem;"
                  >

                <?php endif; ?>

                <?= htmlspecialchars($eq['equipo']) ?>

              </td>

              <td><?= $eq['partidos_jugados'] ?></td>

              <td class="td-v">
                <?= $eq['victorias'] ?>
              </td>

              <td><?= $eq['empates'] ?></td>

              <td class="td-d">
                <?= $eq['derrotas'] ?>
              </td>

              <td><?= $eq['goles_favor'] ?></td>

              <td><?= $eq['goles_contra'] ?></td>

              <td>
                <span class="dg <?= $dgClass ?>">
                  <?= $dg > 0 ? '+' : '' ?><?= $dg ?>
                </span>
              </td>

              <td>
                <span class="pts">
                  <?= $eq['puntos'] ?>
                </span>
              </td>

            </tr>

          <?php endforeach; ?>

        </tbody>

      </table>

    </div>

    <div class="tabla-leyenda">

      <span class="leyenda-item">
        <span class="leyenda-dot dot-lider"></span>
        Líder
      </span>

      <span class="leyenda-item">
        <span class="leyenda-dot dot-top"></span>
        Top 3
      </span>

      <span class="leyenda-sep">·</span>

      <span class="leyenda-info">
        PJ: Partidos Jugados ·
        V: Victorias ·
        E: Empates ·
        D: Derrotas ·
        GF: Goles Favor ·
        GC: Goles Contra ·
        DG: Diferencia ·
        Pts: Puntos
      </span>

    </div>

  <?php endif; ?>

</main>

</body>
</html>