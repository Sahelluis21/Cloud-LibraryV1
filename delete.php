<?php
require __DIR__.'/config/db_connect.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    // Primeiro obtém o caminho do arquivo
    $stmt = $conn->prepare("SELECT file_path FROM uploaded_files WHERE id = ?");
    $stmt->execute([$id]);
    $file = $stmt->fetch();
    
    if($file) {
        // Remove do banco de dados
        $conn->prepare("DELETE FROM uploaded_files WHERE id = ?")->execute([$id]);
        // Remove o arquivo físico
        if(file_exists($file['file_path'])) {
            unlink($file['file_path']);
        }
    }
}

if ($stmt->execute()) {
    unlink($filePath); // Remove o arquivo fisicamente
    header("Location: index.php?deleted=1"); // Recarrega a página
    exit();
}

header("Location: index.php");
?>

