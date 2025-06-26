
// Seu código JavaScript aqui
document.addEventListener('DOMContentLoaded', function() {
    // Alternar visibilidade do submenu
    const themeMenuItem = document.querySelector('.theme-menu-item');
    if (themeMenuItem) {
        themeMenuItem.addEventListener('click', function(e) {
            if (e.target.closest('.theme-submenu')) return;
            this.classList.toggle('active');
        });
    }

    // Seleção de tema
    const themeOptions = document.querySelectorAll('.theme-submenu li');
    themeOptions.forEach(option => {
        option.addEventListener('click', function() {
            const selectedTheme = this.getAttribute('data-theme');
            document.documentElement.setAttribute('data-theme', selectedTheme);
            localStorage.setItem('theme', selectedTheme);
            themeMenuItem?.classList.remove('active');
            highlightActiveTheme();
        });
    });

    // Carregar tema salvo
    function loadTheme() {
        const savedTheme = localStorage.getItem('theme') || 
                         (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', savedTheme);
        highlightActiveTheme();
    }

    // Destacar tema ativo
    function highlightActiveTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        themeOptions.forEach(option => {
            option.classList.toggle('active', 
                option.getAttribute('data-theme') === currentTheme);
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
    // Carrega o tema salvo ou preferência do sistema
    function loadTheme() {
        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const theme = savedTheme || (prefersDark ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', theme);
    }
    
    // Alternar tema
    function toggleTheme() {
        const html = document.documentElement;
        const newTheme = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    }
    
    // Configura os listeners dos botões
    const themeOptions = document.querySelectorAll('.theme-submenu li');
    themeOptions.forEach(option => {
        option.addEventListener('click', function() {
            const theme = this.getAttribute('data-theme');
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
        });
    });
    
    // Inicializa o tema
    loadTheme();
});

    loadTheme();
});
