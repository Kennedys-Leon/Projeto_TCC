// ===================
//  SIDEBAR
// ===================
document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const closeSidebar = document.getElementById('close-sidebar');
    const toggleThemeSidebar = document.getElementById('toggle-theme-sidebar');
    const toggleThemeInput = document.getElementById('toggle-theme'); // já existe
    const cartSidebar = document.getElementById('cart-sidebar');
    const cartIcon = document.getElementById('cart-icon');

    if (menuToggle && sidebar && closeSidebar) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.add('active');
        });

        closeSidebar.addEventListener('click', () => {
            sidebar.classList.remove('active');
        });

        // Fecha clicando fora
        window.addEventListener('click', (e) => {
            if (e.target === sidebar) {
                sidebar.classList.remove('active');
            }
        });
    }

    // Alternar tema também pela sidebar
    if (toggleThemeSidebar) {
        toggleThemeSidebar.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');

            if (document.body.classList.contains('dark-mode')) {
                toggleThemeInput.value = '☀️';
                toggleThemeSidebar.textContent = '☀️ Tema';
            } else {
                toggleThemeInput.value = '🌙';
                toggleThemeSidebar.textContent = '🌙 Tema';
            }
        });
    }

    // Abrir carrinho também pelo menu lateral
    if (cartSidebar && cartIcon) {
        cartSidebar.addEventListener('click', (e) => {
            e.preventDefault();
            cartIcon.click();
            sidebar.classList.remove('active');
        });
    }
});
