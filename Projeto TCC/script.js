document.addEventListener('DOMContentLoaded', () => {
    /* =========================
       TEMA ESCURO/CLARO
    ========================== */
    const toggleThemeHeader  = document.getElementById('toggle-theme');
    const toggleThemeSideBtn = document.getElementById('toggle-theme-sidebar');

    function getImgPath(img) {
        // Detecta se está em subpasta (ex: painel_vendedor) ou na raiz
        if (window.location.pathname.includes('painel_vendedor/')) {
            return `../img/${img}`;
        } else {
            return `img/${img}`;
        }
    }

    function setThemeLabels() {
        if (toggleThemeHeader) {
            const isDark = document.body.classList.contains('dark-mode');
            toggleThemeHeader.innerHTML = isDark
                ? `<img src="${getImgPath('sol.png')}" alt="Tema claro" style="width:22px; vertical-align:middle;">`
                : `<img src="${getImgPath('lua.png')}" alt="Tema escuro" style="width:22px; vertical-align:middle;">`;
        }
        // Botão da sidebar: igual ao anterior
        if (toggleThemeSideBtn) {
            const isDark = document.body.classList.contains('dark-mode');
            const icon = isDark
                ? `<img src="${getImgPath('sol.png')}" alt="Tema claro" style="width:18px; vertical-align:middle; margin-right:6px;">`
                : `<img src="${getImgPath('lua.png')}" alt="Tema escuro" style="width:18px; vertical-align:middle; margin-right:6px;">`;
            toggleThemeSideBtn.innerHTML = `
                ${icon}
                ${isDark ? ' Tema claro' : ' Tema escuro'}
            `;
        }
    }

    function toggleTheme() {
        // Adiciona um leve delay antes de alternar o tema
        setTimeout(() => {
            const isDark = document.body.classList.contains('dark-mode');
            if (isDark) {
                // Se estiver no modo escuro, remove com delay
                document.body.classList.remove('dark-mode');
            } else {
                // Se estiver no modo claro, adiciona com delay
                document.body.classList.add('dark-mode');
            }
            setThemeLabels();
            // (opcional) persistir tema
            localStorage.setItem('ma_theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
        }, 200); // 200ms de delay
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
        const totalItens = cartItems.reduce((acc, item) => acc + (item.quantidade || 1), 0);
        cartIcon.setAttribute('data-count', totalItens);
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
            li.className = 'cart-item';
            li.innerHTML = `
                <span class="cart-item-nome">${item.nome}</span>
                <span class="cart-item-valor">R$ ${item.preco.toFixed(2)} × ${item.quantidade}</span>
            `;

            const rm = document.createElement('button');
            rm.textContent = 'Remover';
            // aplicar mesmas classes que o CSS espera
            rm.className = 'cart-remove-btn remove-btn';
            rm.setAttribute('data-index', idx);

            li.appendChild(rm);
            list.appendChild(li);
            total += item.preco * item.quantidade;
        });

        totalEl.textContent = `R$ ${total.toFixed(2)}`;
    }

    // Seletores
    const cartIcon  = document.getElementById('cart-icon');
    const cartLink  = document.getElementById('open-cart');
    const cartModal = document.getElementById('cart-modal');
    const closeBtn  = document.querySelector('.cart-close-btn');

    // Abrir carrinho
    function openCart(e) {
        if (e) e.preventDefault();
        if (!cartModal) return;
        cartModal.style.display = 'block';
        updateCartModal();
    }

    if (cartIcon) cartIcon.addEventListener('click', openCart);
    if (cartLink) cartLink.addEventListener('click', openCart);

    // Fechar modal
    if (closeBtn) closeBtn.addEventListener('click', () => cartModal.style.display = 'none');
    window.addEventListener('click', (e) => {
        if (e.target === cartModal) cartModal.style.display = 'none';
    });

    // Adicionar item ao carrinho
    document.querySelectorAll('.btn-preco').forEach(btn => {
        btn.addEventListener('click', () => {
            const card = btn.closest('.card-produto');
            if (!card) return;
            const id = btn.dataset.id ?? null;
            const nome = btn.dataset.nome || card.querySelector('p')?.textContent || 'Produto';
            const preco = parseFloat((btn.dataset.preco ?? btn.textContent).toString().replace(/[^\d,.-]/g, '').replace(',', '.')) || 0;

            if (!nome || preco <= 0) return; // <-- agora está no lugar certo

            let existente;
            if (id) existente = cartItems.find(item => item.id == id);
            else existente = cartItems.find(item => item.nome === nome && item.preco === preco);

            if (existente) {
                existente.quantidade = (existente.quantidade || 1) + 1;
            } else {
                cartItems.push({ id: id ? id : null, nome, preco, quantidade: 1 });
            }

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
