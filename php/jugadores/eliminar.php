<?php include '../../config/db.php'; ?>
<?php
  $id   = (int)$_GET['id'];
  $stmt = $pdo->prepare("DELETE FROM jugadores WHERE id_jugador = ?");
  $stmt->execute([$id]);
  header('Location: index.php?msg=eliminado');
  exit;
?>