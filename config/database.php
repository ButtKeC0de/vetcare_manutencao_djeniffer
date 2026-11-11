<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$dbNames = ['vetcare_djeniffer', 'vetcare'];
$pdo = null;

foreach ($dbNames as $db) {
    $dsn = "mysql:host=$host;port=3306;dbname=$db;charset=$charset";
    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        $test = $pdo->query("SHOW TABLES LIKE 'chamados'")->fetch();
        if ($test) {
            break;
        }

        $pdo = null;
    } catch (PDOException $e) {
        $pdo = null;
    }
}

if (!$pdo) {
    die('Erro ao conectar ao banco. Verifique o nome do banco e se o MySQL está ativo.');
}
