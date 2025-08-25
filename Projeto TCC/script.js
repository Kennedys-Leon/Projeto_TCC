document.addEventListener('DOMContentLoaded', () => {
    /* =========================
       TEMA ESCURO/CLARO
    ========================== */
    const toggleThemeHeader  = document.getElementById('toggle-theme');
    const toggleThemeSideBtn = document.getElementById('toggle-theme-sidebar');

    function setThemeLabels() {
        // input do header mostra só o ícone
        if (toggleThemeHeader) {
            toggleThemeHeader.value = document.body.classList.contains('dark-mode') ? '☾' : '☀';
        }
        // botão da sidebar mostra texto
        if (toggleThemeSideBtn) {
            toggleThemeSideBtn.textContent =
                document.body.classList.contains('dark-mode') ? '☀' : '☾';
        }
    }

    function toggleTheme() {
        document.body.classList.toggle('dark-mode');
        setThemeLabels();
        // (opcional) persistir tema
        localStorage.setItem('ma_theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
    }

    // inicia tema (padrão dark + recupera preferido)
    const saved = localStorage.getItem('ma_theme');
    if (saved === 'light') document.body.classList.remove('dark-mode');
    else document.body.classList.add('dark-mode');
    setThemeLabels();

    if (toggleThemeHeader)  toggleThemeHeader.addEventListener('click', toggleTheme);
    if (toggleThemeSideBtn) toggleThemeSideBtn.addEventListener('click', toggleTheme);


    /* =========================
       CARRINHO (MODAL)
    ========================== */
    let cartItems = JSON.parse(localStorage.getItem('cart')) || [];

    function saveCart() {
        localStorage.setItem('cart', JSON.stringify(cartItems));
    }

    function updateCartCount() {
        const cartIcon = document.getElementById('cart-icon');
        if (!cartIcon) return;
        cartIcon.setAttribute('data-count', cartItems.length);
    }

    function updateCartModal() {
        const list = document.querySelector('.cart-items');
        const emptyMsg = document.getElementById('cart-empty-message');
        const totalEl = document.getElementById('cart-total-price');

        if (!list || !emptyMsg || !totalEl) return;

        list.innerHTML = '';
        if (cartItems.length === 0) {
            emptyMsg.style.display = 'block';
            totalEl.textContent = 'R$ 0,00';
            return;
        }

        emptyMsg.style.display = 'none';
        let total = 0;

        cartItems.forEach((item, idx) => {
            const li = document.createElement('li');
            li.textContent = `${item.nome} — R$ ${item.preco.toFixed(2)}`;

            const rm = document.createElement('button');
            rm.textContent = 'Remover';
            rm.style.marginLeft = '8px';
            rm.addEventListener('click', () => {
                cartItems.splice(idx, 1);
                saveCart();
                updateCartCount();
                updateCartModal();
            });

            li.appendChild(rm);
            list.appendChild(li);
            total += item.preco;
        });

        totalEl.textContent = `R$ ${total.toFixed(2)}`;
    }

    const cartIcon   = document.getElementById('cart-icon');
    const cartLink   = document.getElementById('open-cart'); // link da sidebar
    const cartModal  = document.getElementById('cart-modal');
    const closeBtn   = document.querySelector('.cart-close-btn');

    function openCart(e) {
        if (e) e.preventDefault();
        if (!cartModal) return;
        cartModal.style.display = 'block';
        updateCartModal();
    }

    if (cartIcon) cartIcon.addEventListener('click', openCart);
    if (cartLink) cartLink.addEventListener('click', openCart);

    if (closeBtn) {
        closeBtn.addEventListener('click', () => cartModal.style.display = 'none');
    }
    window.addEventListener('click', (e) => {
        if (e.target === cartModal) cartModal.style.display = 'none';
    });

    // Botões de "R$ 25,00 +" adicionam item
    document.querySelectorAll('.btn-preco').forEach(btn => {
        btn.addEventListener('click', () => {
            const card = btn.closest('.card-produto');
            if (!card) return;
            const nome  = card.querySelector('p')?.textContent || 'Produto';
            // pega o número do botão (ex.: "R$ 25,00 +")
            const preco = parseFloat(btn.textContent.replace(/[^\d,]/g,'').replace(',', '.')) || 0;
            cartItems.push({ nome, preco });
            saveCart();
            updateCartCount();
            Swal.fire('Adicionado!', `${nome} foi adicionado ao carrinho.`, 'success');
        });
    });

    updateCartCount();


    /* =========================
       SIDEBAR (abre/fecha)
    ========================== */
    const menuToggle   = document.getElementById('menu-toggle');
    const sidebar      = document.getElementById('sidebar');
    const closeSidebar = document.getElementById('close-sidebar');

    function openSidebar()  { if (sidebar) sidebar.classList.add('active'); }
    function closeSide()    { if (sidebar) sidebar.classList.remove('active'); }

    if (menuToggle)   menuToggle.addEventListener('click', openSidebar);
    if (closeSidebar) closeSidebar.addEventListener('click', closeSide);

    // Clicar fora fecha
    document.addEventListener('click', (e) => {
        if (!sidebar || !sidebar.classList.contains('active')) return;
        const clickInsideSidebar = sidebar.contains(e.target);
        const clickedToggle = e.target === menuToggle;
        if (!clickInsideSidebar && !clickedToggle) closeSide();
    });

});
