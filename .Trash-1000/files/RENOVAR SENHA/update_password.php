<?php

require __DIR__.'/../config/db_connect.php';

$senha_plain = "if21"; // A senha que você quer definir
$username = "Sahel";  // O usuário que vai receber a nova senha

// Gerar o hash correto
$hash_correto = password_hash($senha_plain, PASSWORD_DEFAULT);

// Atualizar no banco de dados
$stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE username = ?");
$stmt->execute([$hash_correto, $username]);

echo "Senha atualizada com sucesso! Hash gerado: " . $hash_correto;

// REMOVA ESTE ARQUIVO APÓS USAR!
?>