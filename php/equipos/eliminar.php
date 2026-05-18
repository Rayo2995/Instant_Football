<?php include '../../config/db.php'; ?>
<?php
  $id     = (int)$_GET['id'];
  $equipo = $pdo->prepare("SELECT escudo FROM equipos WHERE id_equipo = ?");
  $equipo->execute([$id]);
  $equipo = $equipo->fetch(PDO::FETCH_ASSOC);

  if ($equipo) {
      // Borrar escudo del servidor si existe
      if ($equipo['escudo'] && file_exists('../../img/escudos/' . $equipo['escudo'])) {
          unlink('../../img/escudos/' . $equipo['escudo']);
      }
      $stmt = $pdo->prepare("DELETE FROM equipos WHERE id_equipo = ?");
      $stmt->execute([$id]);
  }

  header('Location: index.php?msg=eliminado');
  exit;
?>