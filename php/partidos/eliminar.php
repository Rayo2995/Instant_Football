<?php include '../../config/db.php'; ?>
<?php
  $id   = (int)$_GET['id'];
  $stmt = $pdo->prepare("DELETE FROM partidos WHERE id_partido = ?");
  $stmt->execute([$id]);
  header('Location: index.php?msg=eliminado');
  exit;
?>