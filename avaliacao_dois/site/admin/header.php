<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .user-icon-btn {
            background: none;
            border: none;
            color: #f1c40f;
            font-size: 1.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            padding: 0;
            margin-right: 15px;
        }
        
        .user-icon-btn:hover {
            color: #ffd700;
            transform: scale(1.05);
        }
        
        /* Aside */
        .aside-menu {
            position: fixed;
            top: 0;
            left: -400px;
            width: 350px;
            height: 100vh;
            background: linear-gradient(145deg, #1e1e1e, #161616);
            border-right: 2px solid #f1c40f;
            transition: left 0.4s ease;
            z-index: 1000;
            padding: 20px;
            box-shadow: 5px 0 30px rgba(0,0,0,0.5);
            overflow-y: auto;
        }
        
        .aside-menu.open {
            left: 0;
        }
        
        .aside-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #f1c40f;
            margin-bottom: 20px;
        }
        
        .aside-header h3 {
            color: #f1c40f;
            margin: 0;
            font-size: 1.3rem;
        }
        
        .close-aside {
            background: none;
            border: none;
            color: #f1c40f;
            font-size: 1.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .close-aside:hover {
            color: #ffd700;
            transform: scale(1.1);
        }
        
        .menu-option {
            padding: 15px;
            margin-bottom: 10px;
            background: #252525;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .menu-option:hover {
            background: #333;
            transform: translateX(5px);
            border-right: 3px solid #f1c40f;
        }
        
        .menu-option a {
            color: #e0e0e0;
            text-decoration: none;
            flex: 1;
            font-size: 1rem;
        }
        
        .menu-option i {
            color: #f1c40f;
            font-size: 1.2rem;
            width: 25px;
        }
        
        .menu-divider {
            height: 1px;
            background: #333;
            margin: 15px 0;
        }
        
        .user-info-sidebar {
            background: rgba(241,196,15,0.1);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .user-info-sidebar p {
            margin: 5px 0;
            color: #e0e0e0;
        }
        
        .user-info-sidebar strong {
            color: #f1c40f;
        }
        
        .logout-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background-color: #c82333;
            transform: scale(1.02);
        }
        
        /* Overlay */
        .aside-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }
        
        .aside-overlay.active {
            display: block;
        }
        
        @media (max-width: 768px) {
            .aside-menu {
                width: 280px;
            }
        }
    </style>
</head>

<body>

<!-- Overlay -->
<div id="asideOverlay" class="aside-overlay" onclick="toggleAside()"></div>

<!-- Menu -->
<div id="asideMenu" class="aside-menu">
    <div class="aside-header">
        <h3><i class="fas fa-cog"></i> MENU</h3>
        <button class="close-aside" onclick="toggleAside()">×</button>
    </div>
    
    <div class="user-info-sidebar">
        <i class="fas fa-user-circle" style="font-size: 3rem; color: #f1c40f;"></i>
        <p><strong><?php echo isset($_SESSION['usuario_nome']) ? htmlspecialchars($_SESSION['usuario_nome']) : 'Visitante'; ?></strong></p>
        <p><?php echo isset($_SESSION['usuario_email']) ? htmlspecialchars($_SESSION['usuario_email']) : 'email@exemplo.com'; ?></p>
    </div>
    
    <div class="menu-divider"></div>
    
    <div class="menu-option">
        <i class="fas fa-users"></i>
        <a href="../../usuario/UsuarioList.php">GERENCIAR USUÁRIOS</a>
    </div>
    
    <div class="menu-option">
        <i class="fas fa-newspaper"></i>
        <a href="../../noticia/NoticiaList.php">GERENCIAR NOTÍCIAS</a>
    </div>
    
    <div class="menu-option">
        <i class="fas fa-ticket-alt"></i>
        <a href="../../ingresso/IngressoList.php">GERENCIAR INGRESSOS</a>
    </div>
    
    <div class="menu-option">
        <i class="fas fa-microphone-alt"></i>
        <a href="../../artista/ArtistaList.php">GERENCIAR ARTISTAS</a>
    </div>
      
    <div class="menu-divider"></div>
    
    <form method="POST" action="../logout.php" style="margin-top: 20px;">
        <button type="submit" class="logout-btn">..
            <i class="fas fa-sign-out-alt"></i> SAIR
        </button>
    </form>
</div>

<div class="row d-flex justify-content-center align-items-center rounded bg-dark p-3 text-white shadow-lg">

    <div class="col-3 d-flex align-items-center">
        <button class="user-icon-btn" onclick="toggleAside()">
            <i class="fas fa-user-circle"></i>
        </button>
        <header class="d-flex rounded justify-content-left">
            <a class="text-decoration-none" href="#"
                style="font-family: sans-serif; font-size: 2rem; color: #e0e0d1; letter-spacing: 0.2em; font-weight: 700; text-shadow: 2px 2px 0px rgba(0, 0, 0, 1); margin: 0;">POST
                MORTEM
            </a>
        </header>
    </div>

    <div class="col-3"></div>
    <div class="col-6">
        <nav class="navbar d-flex justify-content-between rounded">
            <div class="nav-scroller d-flex justify-content-around w-100"
                style="font-family: sans-serif; font-size: 1rem; color: #e0e0d1; letter-spacing: 0.1em; font-weight: 700; text-shadow: 2px 2px 0px rgba(0, 0, 0, 1); margin: 0;">
                <a class="text-decoration-none" href="index.php" style="color: #e0e0d1;">PÁGINA INICIAL</a>
                <a class="text-decoration-none" href="sobre.php" style="color: #e0e0d1;">SOBRE NÓS</a>
                <a class="text-decoration-none" href="contato.php" style="color: #e0e0d1;">CONTATO</a>
                <a class="text-decoration-none" href="diversa.php" style="color: #e0e0d1;">DIVERSA</a>
            
            </div>
        </nav>
    </div>
</div>

<script>
    function toggleAside() {
        const aside = document.getElementById('asideMenu');
        const overlay = document.getElementById('asideOverlay');
        
        aside.classList.toggle('open');
        overlay.classList.toggle('active');
        
        if (aside.classList.contains('open')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const aside = document.getElementById('asideMenu');
            if (aside.classList.contains('open')) {
                toggleAside();
            }
        }
    });
</script>


</body>
</html>