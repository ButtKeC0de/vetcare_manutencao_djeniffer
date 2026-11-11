<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function exigirLogin() {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: /vetcare_manutencao_djeniffer/login.php');
        exit;
    }
}
