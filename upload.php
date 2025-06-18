<?php
// Ativar exibição de erros para debug (remova em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/config/db_connect.php';

$uploadDir = 'uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["fileToUpload"])) {
    $fileName = basename($_FILES["fileToUpload"]["name"]);
    $targetFile = $uploadDir . $fileName;
    $fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $fileSize = $_FILES["fileToUpload"]["size"];
    $fileMimeType = $_FILES["fileToUpload"]["type"]; // Pegar o MIME type real do arquivo
    
    // Verificar se o arquivo já existe e adicionar um sufixo se necessário
    $counter = 1;
    while (file_exists($targetFile)) {
        $fileName = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_FILENAME) . "_$counter." . $fileExtension;
        $targetFile = $uploadDir . $fileName;
        $counter++;
    }
    
    // Mover arquivo para o diretório de uploads
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
        try {
            $stmt = $conn->prepare("INSERT INTO uploaded_files (file_name, file_path, file_size, file_type) VALUES (:name, :path, :size, :type)");
            $stmt->bindParam(':name', $fileName);
            $stmt->bindParam(':path', $targetFile);
            $stmt->bindParam(':size', $fileSize);
            $stmt->bindParam(':type', $fileMimeType); // Usar o MIME type aqui
            $stmt->execute();
            
            header("Location: index.php?success=1");
            exit();
        } catch(PDOException $e) {
            unlink($targetFile); // Remove o arquivo se houver erro no banco de dados
            header("Location: index.php?error=database");
            exit();
        }
    } else {
        header("Location: index.php?error=upload");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>