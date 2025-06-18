<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirecionamento se logado
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require __DIR__.'/config/db_connect.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Por favor, preencha todos os campos";
    } else {
        try {
            error_log("Tentativa de login com usuário: " . $username);
            
            $stmt = $conn->prepare("SELECT id, username, password_hash FROM users WHERE username = :username LIMIT 1");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            
            if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                error_log("Usuário encontrado: " . print_r($user, true));
                
                if (password_verify($password, $user['password_hash'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    session_regenerate_id(true);
                    header("Location: index.php");
                    exit;
                } else {
                    error_log("Falha na verificação da senha para usuário: " . $username);
                }
            } else {
                error_log("Usuário não encontrado: " . $username);
            }
            
            $error = "Credenciais inválidas";
            
        } catch (PDOException $e) {
            error_log("ERRO DE LOGIN: " . $e->getMessage());
            $error = "Erro no sistema. Por favor, tente novamente mais tarde.";
            
            if (ini_get('display_errors')) {
                $error .= "<br>Detalhes: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cloud Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: rgb(243, 243, 243);  
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 2rem;
            background: rgb(246, 247, 245);
            border-radius: 40px;
            box-shadow: 2px 2px 900px rgb(192, 191, 191);
            
        }
        
        .brand-container {
            background-color: white;
            border-radius: 20px;
            padding: 1rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .logo-circle {
            width: 60px;
            height: 60px;
            background-color: rgb(126, 165, 74); /* Verde */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .logo-circle img {
            width: 80%;
            height: auto;
        }
        
        .brand-name {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: 1.8rem;
            color:rgb(126, 165, 74); /* Verde */
            letter-spacing: 1px;
            margin: 0;
        }

        .form-control {
            background-color: #fafafa;
            border: 1px solid #e0e0e0;
            padding: 10px 15px;
            margin-bottom: 1rem;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #b0b0b0;
        }
        .btn-login {
            background-color: rgb(255, 255, 255);
            border: none;
            padding: 12px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border-radius: 20px;
            color: #2d3748;
        }
        .btn-login:hover {
            background-color: rgb(140, 190, 80);
            transform: translateY(-2px);
        }
        .btn-login:active {
            background-color: rgb(255, 51, 0);
            transform: translateY(1px);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .btn-login:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgb(234, 236, 71);
        }
        .alert {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-container">
            <div class="logo-circle">
                <img src="lgo library.png" alt="logo">
            </div>
            <h1 class="brand-name">Cloud Library</h1>
        </div>
        
        <?php if (isset($error) && $error !== null): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Usuário" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Senha" required>
            </div>
            <button type="submit" class="btn btn-login btn-primary w-100">Entrar</button>
        </form>
    </div>
</body>
</html>