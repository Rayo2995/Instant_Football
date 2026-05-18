<?php include '../../config/db.php'; ?>
<?php
  $id      = (int)$_GET['id'];
  $noticia = $pdo->prepare("SELECT imagen FROM noticias WHERE id_noticia = ?");
  $noticia->execute([$id]);
  $noticia = $noticia->fetch(PDO::FETCH_ASSOC);

  if ($noticia) {
      if ($noticia['imagen'] && file_exists(__DIR__ . '/../../img/noticias/' . $noticia['imagen'])) {
          unlink(__DIR__ . '/../../img/noticias/' . $noticia['imagen']);
      }
      $stmt = $pdo->prepare("DELETE FROM noticias WHERE id_noticia = ?");
      $stmt->execute([$id]);
  }

  header('Location: index.php?msg=eliminada');
  exit;
?>