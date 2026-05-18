<?php
require_once __DIR__ . '/auth.php';

$host     = "aws-1-us-east-1.pooler.supabase.com";
$port     = "6543";
$dbname   = "postgres";
$user     = "postgres.cqddlkmbnoffykunauix";
$password = "Sebasrayo.a8895**";

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require",
        $user,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
