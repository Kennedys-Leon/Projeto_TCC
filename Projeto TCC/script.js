document.addEventListener('DOMContentLoaded', () => {
    // ---- Funcionalidade do Tema Escuro/Claro ----
    const toggleThemeInput = document.getElementById('toggle-theme');

    if (toggleThemeInput) {
        // Inicializa o tema escuro ao carregar
        document.body.classList.add('dark-mode');
        toggleThemeInput.value = '☀️';

        toggleThemeInput.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');

            if (document.body.classList.contains('dark-mode')) {
                toggleThemeInput.value = '☀️';
            } else {
                toggleThemeInput.value = '🌙';
            }
        });
    }

    // ---- Funcionalidade do Carrinho de Compras (Modal) ----
    const cartIcon = document.getElementById('cart-icon');
    const cartModal = document.getElementById('cart-modal');
    const closeBtn = document.querySelector('.cart-close-btn');

    if (cartIcon && cartModal && closeBtn) {
        // Abre o modal do carrinho
        cartIcon.addEventListener('click', (event) => {
            event.preventDefault(); // Evita que a página recarregue
            cartModal.style.display = 'block';
        });

        // Fecha o modal do carrinho pelo botão 'X'
        closeBtn.addEventListener('click', () => {
            cartModal.style.display = 'none';
        });

        // Fecha o modal se o usuário clicar fora dele
        window.addEventListener('click', (event) => {
            if (event.target === cartModal) {
                cartModal.style.display = 'none';
            }
        });
    }
});