<?php
// config/db_connect.php
header('Content-Type: text/html; charset=utf-8');

$host = "localhost";
$dbname = "webupload";
$user = "webuser";
$password = "ifsp"; // Use a senha correta aqui

try {
    $conn = new PDO(
        "pgsql:host=localhost;dbname=webupload", 
        "webuser", 
        "ifsp",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    // Teste opcional para verificar a conexão
    $conn->exec("SELECT 1");
    error_log("Conexão com PostgreSQL estabelecida com sucesso");
    
} catch (PDOException $e) {
    error_log("ERRO DB: " . $e->getMessage());
    die("Erro de conexão com o banco de dados. Contate o administrador.");
}
?>