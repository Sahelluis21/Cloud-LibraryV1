<?php
require __DIR__.'/../config/db_connect.php';
session_start();

function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        return round($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return round($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return round($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

function getDiskUsage() {
    $diskPath = '/';
    $totalSpace = disk_total_space($diskPath);
    $freeSpace = disk_free_space($diskPath);
    $usedSpace = $totalSpace - $freeSpace;
    
    return [
        'total' => $totalSpace,
        'free' => $freeSpace,
        'used' => $usedSpace,
        'used_percentage' => round(($usedSpace / $totalSpace) * 100, 2)
    ];
}

$diskUsage = getDiskUsage();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
   <head>
    <title>Cloud Library</title>
    <link rel="icon" href="../logos/logo-preta.png" type="image/png">
   </head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="../frontend/style.css">
    <script src="frontend/theme-switcher.js"></script>
</head>
<body>
  <div class="header">
    <div class="floating-title-logo">
        <h1 class="site-title">Cloud Library</h1>
        <div class="logo">
            <img src="../logos/lgo library.png" alt="Logo">
        </div>
    </div>
</div>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <button class="logout-btn" onclick="window.location.href='../configarq/logout.php'">Sair</button>
        <?php endif; ?>
    </div>
    
    <!-- Botão Sidebar -->
    <button class="sidebar-toggle">
        <i class="bi bi-list"></i>
    </button>
    
    <!-- Sidebar em forma de nuvem -->
    <div class="sidebar">
    <ul class="sidebar-menu">
        <li class="theme-menu-item">
            <div class="menu-header">
                <i class="bi bi-palette me-2"></i>
                Temas
                <i class="bi bi-chevron-down float-end toggle-icon"></i>
            </div>
            <ul class="theme-submenu">
                <li data-theme="light">
                    <i class="bi bi-sun me-2"></i>
                    Claro
                </li>
                <li data-theme="dark">
                    <i class="bi bi-moon me-2"></i>
                    Escuro
                </li>
            </ul>
        </li>
        <li>
            <i class="bi bi-translate me-2"></i>
            Idiomas
        </li>
        <li>
            <i class="bi bi-headset me-2"></i>
            Suporte
        </li>
    </ul>
</div>
    
    <!-- Conteúdo Principal -->
<!-- Conteúdo Principal -->
<div class="main-content">
    <div class="dual-layout-container">
        <!-- Lado Esquerdo -->
        <div class="left-side">
 <!-- Dois Containers Irmãos -->
<div class="dual-containers">
    <div class="help-container">
        <div class="help-area">

            <h5 class="text-center mb-4"><i></i>Olá! Esta é a <br><b>Cloud Library</b></br></h5>

            <h5 class="text-center mb-4"><i class="bi bi-question-circle me-2"></i>Ajuda Rápida</h5>

            <ul class="help-list">
                <li><i class="bi bi-plus-circle me-1"></i> Clique em "Adicionar Arquivo" para selecionar</li>
                <li><i class="bi bi-folder me-2"></i> Arquivos são organizados automaticamente</li>
                <li><i class="bi bi-download me-2"></i> Clique no ícone de download para salvar</li>
                <li><i class="bi bi-pie-chart me-1"></i> Logo Abaixo informações sobre seu armazenamento</li>
            </ul>
        </div>
    </div>

    <div class="upload-container">
        <div class="upload-area">
            <h5 class="text-center mb-4">Faça seu upload</h5>
            <form action="/configarq/upload.php" method="post" enctype="multipart/form-data">
                <div class="mb-4 text-center">
                    <input class="form-control d-none" type="file" id="fileToUpload" name="fileToUpload" required>
                    <div class="upload-buttons">
                        <button type="button" class="upload-btn" onclick="document.getElementById('fileToUpload').click()">
                            <i class="bi bi-plus-circle me-1"></i> Adicionar Arquivo
                        </button>
                        <button type="submit" class="upload-btn">
                            <i class="bi bi-upload me-1"></i> Enviar
                        </button>
                    </div>
                    <small id="file-name" class="text-muted d-block mt-2">Nenhum arquivo selecionado</small>
                </div>
            </form>
        </div>
    </div>
</div>
            <!-- Barra de Armazenamento -->
            <div class="storage-container">
                <div class="storage-bar">
                    <div class="storage-info">
                        <i class="bi bi-hdd me-2"></i> Espaço de Armazenamento
                    </div>
                    <div class="storage-progress">
                        <div class="storage-progress-filled" style="width: <?php echo $diskUsage['used_percentage']; ?>%"></div>
                    </div>
                    <div class="storage-details">
                        <span><i class="bi bi-pie-chart me-1"></i> <?php echo formatSizeUnits($diskUsage['used']); ?></span>
                        <span><?php echo formatSizeUnits($diskUsage['total']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Container de Ajuda (versão maior) -->
            <div class="info-container">
                <div class="info-area">
                    <h5><i class="bi bi-info-circle me-2"></i>Informações Importantes</h5>
                    <p>• Armazenamento seguro com criptografia</p>
                    <p>• Suporte a arquivos de até 2GB</p>
                    <p>• Compatível com todos os formatos comuns</p>
                </div>
            </div>
        </div>

        <!-- Lado Direito -->
        <div class="right-side">
            <div class="documents-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Arquivos Disponíveis</h5>
                    <div class="sort-options">
                        <select id="sortOrder" class="form-select form-select-sm" style="width: 180px;">
                            <option value="newest">Últimos adicionados</option>
                            <option value="oldest">Primeiros adicionados</option>
                        </select>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <tbody id="filesTableBody">
                             <?php
                                try {
                                    $stmt = $conn->query("SELECT * FROM uploaded_files ORDER BY upload_date DESC");
                                    if ($stmt->rowCount() > 0) {
                                        echo '<table class="table table-hover align-middle">';
                                        echo '<thead><tr>
                                                <th>Nome</th>
                                                <th>Tipo</th>
                                                <th>Tamanho</th>
                                                <th>Data</th>
                                                <th class="text-end">Ações</th>
                                            </tr></thead><tbody>';
                                        
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                // Determine a classe de filtro
                                                $filterClass = 'other';
                                                if (strpos($row['file_type'], 'image/') === 0) {
                                                    $filterClass = 'image';
                                                } elseif ($row['file_type'] === 'application/pdf') {
                                                    $filterClass = 'pdf';
                                                } elseif (strpos($row['file_type'], 'application/vnd.openxmlformats-officedocument') === 0) {
                                                    $filterClass = 'office';
                                                } elseif (strpos($row['file_type'], 'video/') === 0) {
                                                    $filterClass = 'video';
                                                }
                                            
                                            echo '<tr class="file-row ' . $filterClass . '">';
                                            echo '<td>
                                                    <span class="file-icon me-2">
                                                        <i class="bi bi-file-earmark-text"></i>
                                                    </span>
                                                    '.htmlspecialchars($row['file_name']).'
                                                </td>
                                                <td><span class="badge bg-light text-dark">'.htmlspecialchars($row['file_type']).'</span></td>
                                                <td>'.round($row['file_size'] / 1048576, 2).' MB</td>
                                                <td>'.date('d/m/Y H:i', strtotime($row['upload_date'])).'</td>
                                                <td class="text-end">
                                                    <a href="../configarq/download.php?id='.$row['id'].'" class="btn btn-sm btn-outline-dark me-1">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                    <a href="../configarq/delete.php?id='.$row['id'].'" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Tem certeza que deseja excluir?\')">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>';
                                        }
                                        
                                        echo '</tbody></table>';
                                    } else {
                                        echo '<div class="text-center py-4 text-muted">
                                            <i class="bi bi-folder-x" style="font-size: 2rem;"></i>
                                            <p class="mt-2">Nenhum arquivo encontrado</p>
                                        </div>';
                                    }
                                } catch(PDOException $e) {
                                    echo '<div class="alert alert-light">Erro ao carregar arquivos: '.$e->getMessage().'</div>';
                                }
                                ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
        
    <!-- Bootstrap JS -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Sidebar Toggle (seu original)
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if(sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }

    // 2. Nome do arquivo no upload (seu original)
    document.getElementById('fileToUpload')?.addEventListener('change', function(e) {
        document.getElementById('file-name').textContent = e.target.files[0]?.name || 'Nenhum arquivo selecionado';
    });

    // 3. Ordenação (novo)
    document.getElementById('sortOrder')?.addEventListener('change', function() {
        window.location.href = '?order=' + (this.value === 'oldest' ? 'oldest' : 'newest');
    });

    // 4. Notificações Toast (seu original)
    const toast = document.getElementById('uploadToast') || document.getElementById('errorToast');
    if(toast) {
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
            if(history.replaceState) {
                const url = new URL(window.location.href);
                url.searchParams.delete('success');
                url.searchParams.delete('error');
                history.replaceState(null, '', url);
            }
        }, 3000);
    }
});
</script>

    <!-- Toast Notification -->
    <div class="toast-container">
        <?php if(isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="toast" id="uploadToast">
                <i class="bi bi-check-circle-fill"></i>
                Arquivo enviado com sucesso!
            </div>
        <?php elseif(isset($_GET['error'])): ?>
            <div class="toast toast-error" id="errorToast">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?php echo ($_GET['error'] === 'database') ? 'Erro no banco de dados' : 'Erro no upload do arquivo'; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>