<?php
// Ativar exibição de erros para debug (remova em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../config/db_connect.php';

// Caminho absoluto para a pasta de uploads
$uploadDir = __DIR__ . '/../uploads/';

// Criar diretório se não existir
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["fileToUpload"])) {
    $originalName = basename($_FILES["fileToUpload"]["name"]);
    $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $fileSize = $_FILES["fileToUpload"]["size"];
    $fileMimeType = $_FILES["fileToUpload"]["type"];

    $fileName = pathinfo($originalName, PATHINFO_FILENAME);
    $targetFile = $uploadDir . $originalName;

    // Adicionar sufixo numérico se o arquivo já existir
    $counter = 1;
    while (file_exists($targetFile)) {
        $newFileName = $fileName . "_$counter." . $fileExtension;
        $targetFile = $uploadDir . $newFileName;
        $counter++;
    }

    // Move o arquivo para o destino
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
        // Caminho absoluto final
        $absolutePath = realpath($targetFile);
        $finalFileName = basename($targetFile); // pode ter sufixo _1, _2 etc.

        try {
            $stmt = $conn->prepare("
                INSERT INTO uploaded_files (file_name, file_path, file_size, file_type)
                VALUES (:name, :path, :size, :type)
            ");
            $stmt->bindParam(':name', $finalFileName);
            $stmt->bindParam(':path', $absolutePath);
            $stmt->bindParam(':size', $fileSize);
            $stmt->bindParam(':type', $fileMimeType);
            $stmt->execute();

            header("Location: ../home/index.php?success=1");
            exit();
        } catch (PDOException $e) {
            // Remove o arquivo físico se falhar ao salvar no banco
            unlink($absolutePath);
            header("Location: ../home/index.php?error=database");
            exit();
        }
    } else {
        header("Location: ../home/index.php?error=upload");
        exit();
    }
} else {
    header("Location: ../home/index.php");
    exit();
}
?>
