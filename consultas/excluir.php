<?php
require_once __DIR__.'/../config/database.php';require_once __DIR__.'/../config/auth.php';exigirLogin();
$id=(int)($_GET['id']??0);$s=$pdo->prepare("DELETE FROM consultas WHERE id=?");$s->execute([$id]); 
header('Location: index.php');exit;
